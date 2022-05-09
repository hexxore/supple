<?php namespace Hexxore\Omni\Commands;

use Hexxore\Omni\Command;
use Hexxore\Omni\Config;
use Hexxore\Omni\Project;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Set extends Command {
    protected static $defaultName = 'set';
    
    protected function configure() {
        $this
            ->setDescription('Build your omni docker setup.')
            ->addArgument('Variable', InputArgument::REQUIRED, "Variable to set" )
            ->addArgument('Value', InputArgument::REQUIRED, "Value to set" );

    }

    protected function fire() {
        $var = $this->input->getArgument('Variable');
        $val = $this->input->getArgument('Value');

        // if prefixed with global or private, use the global or private $target


        if ( $var == "" || $val == "" ){
            throw new \RuntimeException("Variable cannot be empty");
        }
        if ( $val == "NULL" || $val == "NIL" ) {
            // unset it.
            $val = NULL;
        }
        
        $this->info("Setting \"$var\" to \"$val\"");
        $this->project->set($var, $val);
        $this->project->save();
    }
}