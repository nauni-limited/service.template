#!/bin/bash

files=$(grep -L "MockeryTestCase" $(grep -L "Mockery::close()" $(grep -lr "Mockery::mock" --include=*.php --exclude=functions.php tests)))
if [[ "$files" != "" ]]; then
  echo "The following files are mocking classes without using Mockery::close() in tearDown():"
  echo $files
  exit 161;
fi

exit 0
