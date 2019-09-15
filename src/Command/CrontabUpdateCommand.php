<?php

namespace BenTools\CrontabBundle\Command;

use BenTools\CrontabBundle\CrontabGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class CrontabUpdateCommand extends ContainerAwareCommand
{
    /**
     * @var CrontabGenerator
     */
    private $crontabGenerator;

    /**
     * CrontabReplaceCommand constructor.
     * @param CrontabGenerator $crontabGenerator
     */
    public function __construct(CrontabGenerator $crontabGenerator)
    {
        $this->crontabGenerator = $crontabGenerator;
        parent::__construct('crontab:update');
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Prevents the command to really update current crontab.');
        $this->addOption('output-file', null, InputOption::VALUE_OPTIONAL, 'Send crontab content to this file');
        $this->addOption('dump', null, InputOption::VALUE_NONE, 'Show generated crontab');
    }


    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $distFile = $this->getContainer()->getParameter('bentools_crontab.dist_file');

        if (null === $distFile) {
            throw new \RuntimeException('Crontab prototype file "dist_file" is not configured.');
        }

        if (!is_readable($distFile)) {
            throw new \RuntimeException(sprintf('%s is not readable', $distFile));
        }

        $commandsToAdd = file($distFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $replacedCommandsToAdd = $this->crontabGenerator->replaceWithContainerParameters($commandsToAdd, $this->getContainer());

        if (true === $input->getOption('dump')) {
            $output->writeln('<info>Generated crontab:</info>');
            $output->writeln(implode(PHP_EOL, $replacedCommandsToAdd));
        }

        $outputFile = null !== $input->getOption('output-file') ? $input->getOption('output-file') : $this->crontabGenerator->createTemporaryFile();

        if (true !== $input->getOption('dry-run')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Update crontab on system? (Y/n): ', true);

            if (!$helper->ask($input, $output, $question)) {
                $output->writeln('<info>Crontab was not updated.</info>');
                return;
            }

            $question = new ConfirmationQuestion('Replace crontab (y/N): ', false);
            $replace = $helper->ask($input, $output, $question);

            $output->writeln('Applying new crontab... ');

            if (true === $replace) {
                $crontabList = implode(PHP_EOL, $replacedCommandsToAdd);
            } else {
                $process = new Process('crontab -l');
                $process->run();
                $crontabList = $process->getOutput();
                $explodedContabList = array_map('trim', explode(PHP_EOL, $crontabList));
                $nothingToUpdate = true;

                foreach ($replacedCommandsToAdd as $command) {
                    $command = trim($command);
                    $notExists = !in_array($command, $explodedContabList);
                    $nothingToUpdate = $nothingToUpdate && !$notExists;

                    if ($notExists) {
                        $crontabList .= PHP_EOL.$command;
                    } else {
                        $output->writeln(sprintf('<info>Skipping command "%s" as it already exists</info>', $command));
                    }
                }

                if ($nothingToUpdate) {
                    $output->writeln('<info>Nothing to update</info>');
                    return;
                }
            }

            $output->write(sprintf('Writing crontab... ', $outputFile));
            $this->crontabGenerator->write($crontabList, $outputFile);
            $process = new Process(sprintf('crontab %s', $outputFile));
            $process->run();
            $output->writeln('<info>Success!</info>');
        }
    }
}
