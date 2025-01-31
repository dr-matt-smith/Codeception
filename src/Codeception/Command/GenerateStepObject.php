<?php

declare(strict_types=1);

namespace Codeception\Command;

use Codeception\Configuration;
use Codeception\Lib\Generator\StepObject as StepObjectGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use function ucfirst;

/**
 * Generates StepObject class. You will be asked for steps you want to implement.
 *
 * * `codecept g:stepobject acceptance AdminSteps`
 * * `codecept g:stepobject acceptance UserSteps --silent` - skip action questions
 *
 */
class GenerateStepObject extends Command
{
    use Shared\FileSystemTrait;
    use Shared\ConfigTrait;

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('suite', InputArgument::REQUIRED, 'Suite for StepObject'),
            new InputArgument('step', InputArgument::REQUIRED, 'StepObject name'),
            new InputOption('silent', '', InputOption::VALUE_NONE, 'skip verification question'),
        ]);
    }

    public function getDescription(): string
    {
        return 'Generates empty StepObject class';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $suite = (string)$input->getArgument('suite');
        $step = $input->getArgument('step');
        $config = $this->getSuiteConfig($suite);

        $class = $this->getShortClassName($step);

        $path = $this->createDirectoryFor(Configuration::supportDir() . 'Step' . DIRECTORY_SEPARATOR . ucfirst($suite), $step);

        $dialog = $this->getHelperSet()->get('question');
        $filename = $path . $class . '.php';

        $helper = $this->getHelper('question');
        $question = new Question("Add action to StepObject class (ENTER to exit): ");

        $stepObject = new StepObjectGenerator($config, ucfirst($suite) . '\\' . $step);

        if (!$input->getOption('silent')) {
            do {
                $question = new Question('Add action to StepObject class (ENTER to exit): ', null);
                $action = $dialog->ask($input, $output, $question);
                if ($action) {
                    $stepObject->createAction($action);
                }
            } while ($action);
        }

        $res = $this->createFile($filename, $stepObject->produce());

        if (!$res) {
            $output->writeln("<error>StepObject {$filename} already exists</error>");
            return 1;
        }
        $output->writeln("<info>StepObject was created in {$filename}</info>");
        return 0;
    }
}
