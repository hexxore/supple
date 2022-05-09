<?php namespace Hexxore\Omni\Commands;

use Hexxore\Omni\Command;
use Hexxore\Omni\Modules;

class Init extends Command {
    
    protected static $defaultName = 'init';
    
    protected function configure() {
        $this ->setDescription('Initiate a new omni project.');
    }

    protected function fire()
    {
        $init = getcwd().'/omni.json';
        $found = false;

        try {
            $v = $this->project->get();
            $found = $this->project->getAbsolutePath();
        } catch ( \RuntimeException $e ){
            // return
        }

        if ( $found ) {
            if ( $found == $init ) {
                $this->io->error($found." already exists...");
                return;
            }
            $this->io->warning(["conflicts between", "existing: ".$found, "proposed: ".$init]);
            
            if ( !$this->io->confirm("No modifications will be made to the existing files\nDo you still want to create $init", false)  ) {
                $this->io->error("Aborting initiation because of confliction");
                return 1;
            };

        }

        // initiate the json file

        $this->io->success("initiating $init");
        touch($init);
        $this->project->setDirectory(getcwd());
        $this->project->get(); // invokes init.
        $this->project->set('version', '1');
        $this->project->save();
        
        //return $this->shell(['docker', 'compose', '-f', 'docker-compose.yml', 'build'] );
        // compose -f docker-compose.yaml build');
    }
}