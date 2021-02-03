#!/bin/bash

set -e

if [[ "$1" == "" ]]; then
  echo "You must provide the name of a test suite to run (from the phpdoc @suite tags)"
  exit 255
fi

printf "Dumping autoload... "
composer composer dump-autoload --optimize --classmap-authoritative

printf "Done \nGenerating Suite Config... "
src=$(tests/scripts/get-suite-files.php $1 src)
test=$(tests/scripts/get-suite-files.php $1 test)

# Generate a phpunit configuration that only runs over relevant files
phpunitxml=/tmp/phpunit.xml
srcfiles=""
for file in $src; do
  srcfiles+="<file>$file</file>"
done
testfiles=""
for file in $test; do
  testfiles+="<file>$file</file>"
done
cat > $phpunitxml <<EOL
<phpunit bootstrap="/stream/tests/bootstrap.php" failOnRisky="true">
  <testsuite name="test">
    $testfiles
  </testsuite>
  <coverage processUncoveredFiles="true">
    <include>
      $srcfiles
    </include>
  </coverage>
</phpunit>
EOL
printf "Done \n"

phpunit \
  --coverage-text \
  --coverage-xml=/tmp/coverage/coverage-xml \
  --coverage-html=coverage \
  --log-junit=/tmp/coverage/junit.xml \
  --configuration=$phpunitxml

test/script/check-mock-cleanup.sh
test/script/check-phpunit-cleanup.php

infection \
  --only-covered \
  --min-covered-msi=100 \
  -j$(nproc) \
  --filter=$(tests/scripts/get-suite-files.php $1 src | paste -sd "," -) \
  --ignore-msi-with-no-mutations \
  --coverage=/tmp/coverage \
  --skip-initial-tests \
  --test-framework-options="--configuration=$phpunitxml"

# Run coding standards over changed files (use Kernel.php to ensure that no files doesn't cause all files to be processed)
phpcs src/Kernel.php $src $test

test/script/check-migration-diff.sh

composer validate --strict
