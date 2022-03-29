<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput ;


class TestCase extends PHPUnitTestCase
{
    
    public function dumpTable($t) {
        $output = new ConsoleOutput();

        $f = reset($t);
        $table = new Table($output);
        $table->setHeaders(array_keys($f));
        foreach ( $t as $tt ) {
            foreach ( $tt as $ttt => $v ) { 
                if ( $v === null ) {
                    $tt[$ttt] = 'NULL';
                }
                if ( $v === FALSE ) {
                    $tt[$ttt] = 'FALSE';
                }
                if ( $v === TRUE ) {
                    $tt[$ttt] = 'TRUE';
                }
                if ( $v === "" ) {
                    $tt[$ttt] = '""';
                }
            }
            $table->addRow($tt);
        }
        
        $table->render();
    }    
    public function dumpTableFlip($t) {
        $table = [];
        foreach ( $t as $row ) {
            $table[] = $row->getMapAsArray();
        }
        $this->dumpTable($table);
    }
    public function dumpMapping($t) {
        $output = new ConsoleOutput();
        $output->writeLn("<info>Properties for entity: </info>".$t['entity']->getName() );
        $this->dumpTableFlip($t['properties']);
        
        $output->writeLn("<info>Relations for entity: </info>".$t['entity']->getName() );
        $this->dumpTableFlip($t['relations']);
    }



}