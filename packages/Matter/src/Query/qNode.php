<?php
namespace Hexxore\Matter\Query;
class qNode implements qNodeInterface {
    public function accept(qNodeVisitorInterface $visitor) {
        $actor = get_called_class();
        $actor = explode("\\", $actor);
        $actor = array_pop($actor);
        $actor = 'visit'.ucfirst($actor);
        return $visitor->$actor($this);
    }
}