<?php
declare(strict_types=1);

namespace Hexxore\Matter\Tests\Unit;

use Hexxore\Matter\Drivers\MongoDB\Compiler as MongoCompiler;
use Hexxore\Matter\Drivers\PDO\Compiler as PDOCompiler;

use Hexxore\Matter\Query\Equals;
use Hexxore\Matter\Query\GreaterThan;
use Hexxore\Matter\Query\Key;
use Hexxore\Matter\Query\LesserThan;
use Hexxore\Matter\Query\LogicalAnd;
use Hexxore\Matter\Query\LogicalOr;
use Hexxore\Matter\Query\qNode;
use Hexxore\Matter\Query\Value;
use Hexxore\Matter\Query\WhereCondition;

final class MatterQueryTest extends TestCase {

    public function test_build_where_condition() {
       
        $q = new WhereCondition(
            new LogicalAnd(
                // middle age
                new LogicalAnd(
                    new GreaterThan(new Key('age'),new Value(':min_age')),
                    new LesserThan(new Key('age'),new Value(':max_age')),
                ),
                new LogicalOr(
                    new Equals(new Key('vehicleOfChoice'),new Value('motorcycle')),
                    new Equals(new Key('vehicleOfChoice'),new Value('car')),
                )
            )
        );
        $this->assertTrue($q instanceof qNode );
        $this->assertTrue($q instanceof WhereCondition );
        return $q;
    }

    /**
     * @depends test_build_where_condition
     */
    public function test_query_compile_to_pdo($query) {
        $compiler = new PDOCompiler($query);
        $query = $compiler->compile($query);
        $expected = "((age > :min_age AND age < :max_age) AND (vehicle_of_choice = 'motorcycle' OR vehicle_of_choice = 'car'))";
        $this->assertEquals($query, $expected);
        
    }

    /**
     * @depends test_build_where_condition
     */
    public function test_query_compile_to_mongo($query) {
        $compiler = new MongoCompiler($query);
        $query = $compiler->compile($query);
        
        $expected = [
            '$or' => [
                ['vehicleOfChoice' => 'motorcycle'],
                ['vehicleOfChoice' => 'car'],
            ],  
            'age' => [
                '$lt' => ':max_age',
                '$gt' => ':min_age'
            ]
        ];
        
        $this->assertEquals($query, $expected);
        
    }
}
