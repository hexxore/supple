
<?php namespace Hexxore\Omni\Modules;

use Hexxore\Omni\Command;
use Hexxore\Omni\Config;
use Hexxore\Omni\Project;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Build extends Command {
    protected static $defaultName = 'build';
    
    protected function configure() {
        $this
            ->setDescription('Build your omni docker setup.')
            ->addArgument('file', InputArgument::OPTIONAL, 
                "Path to docker compose file", 
                'docker-compose.yaml'
            )
            ->addArgument('services', InputArgument::OPTIONAL, "Comma seperated list of services to build", '');
    }

    protected function fire()
    {
        $this->info("firing docker compose -f docker-compose.yml build");
        //return $this->shell(['docker', 'compose', '-f', 'docker-compose.yml', 'build'] );
        // compose -f docker-compose.yaml build');
    }
}