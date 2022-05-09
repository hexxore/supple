<?php
declare(strict_types=1);

require_once("../../../vendor/autoload.php");
use \Roave\BetterReflection\Reflection\ReflectionFunction;
use \Roave\BetterReflection\Reflection\ReflectionClass;
use \Roave\BetterReflection\Reflection\ReflectionMethod;

use PhpParser\NodeDumper;


class PqlExpressionException extends \Exception{}

class Util {
    // dont use this in the real world!
    public static function escape($value) {
        $return = '';
        for($i = 0; $i < strlen($value); ++$i) {
            $char = $value[$i];
            $ord = ord($char);
            if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
                $return .= $char;
            else
                $return .= '\\x' . dechex($ord);
        }
        return $return;
    }
    public static function pascalCase($input) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
    public static function wrapSqlName($input) {
        return '`'.self::escape($input).'`';
    }
}
class WhereExpression{
    private Closure $lambda;

    private ReflectionFunction $refLambda;
    private ReflectionClass $refModel;
    public function __construct(Closure $lambda, ReflectionClass $model) {
        $this->lambda = $lambda;
        $this->refLambda = ReflectionFunction::createFromClosure($lambda);
        $this->refModel = $model;
//        new \ReflectionFunction($lambda);
    }
    public function toSql(): string {

        $context = [];
        $modelname = $this->refModel->getName();

        $modelSqlName = "{$modelname::getSqlName()}";
        foreach ( $this->refLambda->getParameters() as $p ) { 
            //echo '$'.$p->getName().PHP_EOL;
            $modelVarName = '$'.$p->getName();

            foreach ( $this->refModel->getProperties() as $p ) {
                $propVarName = "{$modelVarName}->{$p->getName()}";
                $propSqlName = "$modelSqlName.{$modelname::getSqlName($p->getName())}";

                $context[$propVarName] =  $propSqlName;
            }   

            
            $context[$modelVarName] =  $modelSqlName;
        }
        if ( count($this->refLambda->getBodyAst()) > 1 ){
            throw new PqlExpressionException("not a valid expression on line ".$this->refLambda->getLine());
        }
        
        $expr = $this->refLambda->getBodyCode();
        //echo "Before parsing:\n".($expr)."\n\n";
        
        foreach ( $context as $var => $sql ) {
            $expr = str_replace($var, $sql, $expr);
        }  
        
        
        $bindings = (new \ReflectionFunction($this->lambda))->getClosureUsedVariables();
        foreach ( $bindings as $k => $v ) {
            // escape vars! 
            if ( is_int($v ) ) {
                 $expr = str_replace('$'.$k, (string)$v, $expr);
            } else {
                $expr = str_replace('$'.$k, "'$v'", $expr);
            }
        }


           
        return $expr;
    }
    
}

class Pql {
    private $query;
    private $refModel;
    private $where;

    public function from(string|ReflectionClass $target) {
        if ( $target instanceof ReflectionClass ) {
            $this->refModel = $target;
            return $this;
        }
        $this->refModel = ReflectionClass::createFromName($target);
        return $this;
    }
    public function where(Closure $lambda) {
        $reflection = new WhereExpression($lambda, $this->refModel);
        $this->where = $reflection->toSql();
        // do something with lambda.
        return $this;
    }
    public function toSql() {
        return "SELECT * FROM {$this->refModel->getName()} WHERE \n\n{$this->where}\n";
    }
    
}
class Collection {
    // some kind of array thing 
}
class UserCollection extends Collection {
    static $type = User::class;
}
interface Entity {

}
class  Model implements Entity {
    public static function getSqlName($property = null ): string {
        return Util::wrapSqlName(self::getNormalizedName($property));
    }
    public static function getNormalizedName($property = null): string {
        if ( $property == null ) {
            return Util::pascalCase(get_called_class());
        } else {
            return Util::pascalCase($property);
        }
    }
}
class User extends Model {
    public $id;
    public $name;
    public $lastname;
    public $age;
}
class DbContext {
    public function __get( String $ent ) {
        $ref = ReflectionClass::createFromName($ent);
        if ( !$ref->implementsInterface(Entity::class) ) {
            throw new \RuntimeException("Cannot instantiate a Pql on a non Entity object");
        }

        return (new Pql())->from($ref);
    }
}

$dbContextInstance = new DbContext(/* db meuk hier*/);


$minage = 69420;
$name = 'somename';

echo $dbContextInstance->User->where(fn($uu)  =>
    ( $uu->name == $name || $uu->lastname == $name ) 
    &&
    ( $uu->age >= $minage && $uu->age <= $minage )
)->toSql();

 




/* 

$pql->from(User::class)
    
    ->where( fn($u) => 
        $u->age >=  $minage
        &&
        $u->age <=  $minage
    )
    ->where( fn($u) => $u->active == true )
    ->run();



    # test*/ 