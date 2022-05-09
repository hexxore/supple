<?php
namespace Hexxore\Omni;

class Resources {
    private $paths = [];
    private $root;
    public function __construct($root = __DIR__.'/../resources') {
        $this->root = realpath($root);
    }
    public function addPath($path) {
        $this->paths[] = $path;
    }
    public function listDirectories($path): array {
        // list for each path and combine.
        // favor user defined over omni defined.
        
        $list = $this->listDirectoriesForFolder($this->root.'/'.$path);
      
        return $list;
    }
    public function listDirectoriesForFolder($path): array {
        $result = array_diff(scandir($path), array('..', '.'));
        $result = array_filter($result, function($file) use ($path) {
            return is_dir($path . '/' . $file);
        });
        return array_values($result);
    }
}