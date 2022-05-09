<?php
namespace Hexxore\Matter\Drivers\PDO;

use Closure;
use PDO;
use Hexxore\Matter\Drivers\DriverInterface;
use Hexxore\Matter\Drivers\PDO\PdoCompiler;
use Hexxore\Matter\Query\qNode;
use Hexxore\Matter\Query\qNodeInterface;
use Hexxore\Matter\QueryInterface;
use Hexxore\Matter\ResultInterface;
use Hexxore\Matter\Query\WhereCondition;

class Driver implements DriverInterface {
    
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function connect() : bool {
        // pdo handles it ?.
        return true;
    }
    public function disconnect() : bool {
        // pdo handles it ?.
        return true;
    }
    public function commit(Closure $procedure): ResultInterface {
        // wrap it.
        $this->pdo->beginTransaction();
        $result = $procedure($this);
        $this->pdo->commit();
        return $result;
    }


    // Acutal DATA api
    // SEARCH
    public function search(WhereCondition $where) {
        $where = $this->compile($where);
    }

    // compiles into PdoDriver format ( a.k.a. ANSI sql ).
    public function compile(qNode $node) {
        $compiler = new PdoCompiler($node);
        return $compiler->compile(); 
    }
    public function group() {

    }

    // pdo specific functions
    public function getPdo(): PDO {
        return $this->pdo;
    }

}