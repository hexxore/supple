<?php
namespace Hexxore\Matter\Query;
interface qNodeInterface {
    public function accept(qNodeVisitorInterface $visitor);
}