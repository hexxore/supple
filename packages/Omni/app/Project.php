<?php
/* -----------------------
|
|   Simple Project wrapper based on the current working directory or above working directory with omni.json
|
*/
namespace Hexxore\Omni;
use Adbar\Dot;
class Project extends Dot {
    
    private $filename;
    private $directory;
    private $start;
    private $init = false;
    
    public function __construct($start = false, $filename = 'omni.json')  {
        $this->filename = $filename;
        $this->start = $start;
    }
    public function get($key = null, $default = null) {
        $this->init();
        return parent::get($key, $default);
    }
    public function set($key, $val = null) { 
        $this->init();

        echo "setting $key to $val\n";
        
        return parent::set($key, $val);
    }
    private function init($start = false) {
        if ( $this->init ) {
            return;
        }
        echo "init...\n";
        
        
        if ( !$start ) {
            $start = $this->start;
        }
        if ( !$start ) {
            $start = getcwd();
        }
        $file = $this->resolve($start);
        
        if ( !$this->filename ) {
            throw new \RuntimeException("Couldn't determine project filename");
        }
        if ( !$this->directory ) {
            throw new \RuntimeException("Couldn't determine project directory");
        }
        $this->init = true;
        $this->load();
    }
    // check for omni.json file in working directory
    private function resolve($current): self {
        if ( $this->filename && $this->directory ) {
            return $this; // asume its resolved.
        }
        $file = "$current/{$this->filename}";

        if ( file_exists($file) ) {
            $this->directory = $current;
            return $this;
        }

        // check parent directory if there is one.
        if ( $current == '/') {
            throw new \RuntimeException("{$this->filename} project file could not be resolved. was it initiated?");
        }
        return $this->resolve(dirname($current)); 
    }


    public function getAbsolutePath() : string {

        return $this->directory.'/'.$this->filename;
    }
    public function setDirectory($directory): void {
        if ( !is_dir($directory ) ){ 
            throw new \RuntimeException("Invalid directory for ".get_called_class());
        }
        $this->directory = $directory;
    }
    // we asume json as default fileformat.
    private function load(): self {
        $file = $this->getAbsolutePath();
        if ( is_file($file ) ) {
            $this->items = json_decode(file_get_contents($file), true);
        }
        return $this;
    }


    public function save() {
        
        $content = $this->items;
        $json_string = json_encode($content, JSON_PRETTY_PRINT);
        echo $this->getAbsolutePath()."\n\n";
        echo $json_string;

        if ( file_put_contents($this->getAbsolutePath(), $json_string)){
            return true;
        }
        return false;
    }
}