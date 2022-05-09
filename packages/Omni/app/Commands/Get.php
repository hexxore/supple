<?php namespace Hexxore\Omni\Commands;

use Hexxore\Omni\Command;
use Hexxore\Omni\Config;
use Hexxore\Omni\Project;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Get extends Command {
    protected static $defaultName = 'get';
    
    protected function configure() {
        $this
            ->setDescription('Build your omni docker setup.')
            ->addArgument('Variable', InputArgument::OPTIONAL, "Variable to set",null );

    }

    protected function fire() {
        $var = $this->input->getArgument('Variable');

        $val = $this->project->get($var);

        // single line dump
        if ( !is_array($val) && !is_object($val) ){
            $this->output->writeln($var . " = ". var_export($val, true));
            return;
        }
        
        // multiline dump
        $dot = new \Adbar\Dot($val);
        $items = $dot->flatten();
        array_walk($items, function(&$v, $k) { $v = [$k, ':' , $v]; });

        $this->table(null, $items );
    }
}