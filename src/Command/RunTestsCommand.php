<?php

namespace App\Command;

use App\Attribute\Suite;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function file_get_contents;
use function is_dir;
use function iterator_to_array;
use function sprintf;
use function substr;
use function token_get_all;
use function usort;

class RunTestsCommand extends Command
{

    protected static $defaultName = 'app:run-tests';

    protected function configure(): void
    {
        $this
            ->setDescription('Runs all tests for a suite')
            ->setHelp('This command allows run your tests for a given suite')
            ->addArgument('suite', InputArgument::REQUIRED, 'Suite');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $suite = $input->getArgument('suite');

        if (!is_dir($dir = '/service/src')) {
            return Command::FAILURE;
        }

        $collection = [];

        new DirectoryResource($dir, '/\.php$/');
        $files = iterator_to_array(new \RecursiveIteratorIterator(
                   new \RecursiveCallbackFilterIterator(
                       new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS),
                       function (\SplFileInfo $current) {
                           return '.' !== substr($current->getBasename(), 0, 1);
                       }
                   ),
                   \RecursiveIteratorIterator::LEAVES_ONLY
               ));
        usort($files, function (\SplFileInfo $a, \SplFileInfo $b) {
            return (string) $a <=> (string) $b;
        });

        foreach ($files as $file) {
            if (!$file->isFile() || '.php' !== substr($file->getFilename(), -4)) {
                continue;
            }

            if ($class = $this->findClass($file)) {
                $refl = new \ReflectionClass($class);
                if ($refl->isAbstract()) {
                    continue;
                }

                foreach($refl->getAttributes() as $attribute) {
                    if ($attribute->getName() === Suite::class) {
                        if ($attribute->newInstance()->getSuite() === $suite) {
                            $collection[] = $refl->getFileName();
                        }
                    }
                }
            }
        }

        dd($collection);

        return Command::SUCCESS;
    }

    protected function findClass(string $file)
    {
        $class = false;
        $namespace = false;
        $tokens = token_get_all(file_get_contents($file));

        if (1 === \count($tokens) && \T_INLINE_HTML === $tokens[0][0]) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain PHP code. Did you forgot to add the "<?php" start tag at the beginning of the file?', $file));
        }

        $nsTokens = [\T_NS_SEPARATOR => true, \T_STRING => true];
        if (\defined('T_NAME_QUALIFIED')) {
            $nsTokens[\T_NAME_QUALIFIED] = true;
        }

        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];

            if (!isset($token[1])) {
                continue;
            }

            if (true === $class && \T_STRING === $token[0]) {
                return $namespace.'\\'.$token[1];
            }

            if (true === $namespace && isset($nsTokens[$token[0]])) {
                $namespace = $token[1];
                while (isset($tokens[++$i][1], $nsTokens[$tokens[$i][0]])) {
                    $namespace .= $tokens[$i][1];
                }
                $token = $tokens[$i];
            }

            if (\T_CLASS === $token[0]) {
                // Skip usage of ::class constant and anonymous classes
                $skipClassToken = false;
                for ($j = $i - 1; $j > 0; --$j) {
                    if (!isset($tokens[$j][1])) {
                        break;
                    }

                    if (\T_DOUBLE_COLON === $tokens[$j][0] || \T_NEW === $tokens[$j][0]) {
                        $skipClassToken = true;
                        break;
                    } elseif (!\in_array($tokens[$j][0], [\T_WHITESPACE, \T_DOC_COMMENT, \T_COMMENT])) {
                        break;
                    }
                }

                if (!$skipClassToken) {
                    $class = true;
                }
            }

            if (\T_NAMESPACE === $token[0]) {
                $namespace = true;
            }
        }

        return false;
    }
}