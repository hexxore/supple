<?php namespace Hexxore\Omni;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command extends BaseCommand
{
    protected $input;
    protected $output;
    protected $project;
    protected $resources;
    
    public function __construct(Project $project, Resources $resources) {
        $this->project = $project;
        $this->resources = $resources;
        parent::__construct();
    }
    // construct stuff here
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($this->input, $this->output);
        // execute some events here!
        return (int) $this->fire();
    }

  

    protected function info($string)
    {
        $this->output->writeln("<info>{$string}</info>");
    }

    protected function error($string)
    {
        $this->output->writeln("<error>{$string}</error>");
    }

    protected function comment($string)
    {
        $this->output->writeln("<comment>{$string}</comment>");
    }
    
    protected function shell($command)
    {
        // $command = explode(" ", $command);
        // replace parameter templates parameters.
        $process = new Process($command);
        $process->start();

        $process->wait(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->error($buffer);
            } else {
                $this->info($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            return 1;
        }
        return 0;
    }
    public function table($headers, $rows , $style = 'compact') {
        $table = new Table($this->output);
        if ( $headers ) {
            $table->setHeaders($headers);
        }
        if ( $rows ) {
            $table->setRows($rows);
        }

        $table->setStyle($style);
        $table->render();
    }
    abstract protected function fire();
}