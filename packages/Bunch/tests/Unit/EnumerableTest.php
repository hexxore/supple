<?php
declare(strict_types=1);

namespace Hexxore\Bunch\Tests\Unit;
 
use \Hexxore\Bunch\Tests\Resources\StockPrice;
use \Hexxore\Bunch\Tests\Resources\StockPriceCollection;
use \Hexxore\Bunch\Tests\Resources\Human;
use \Hexxore\Bunch\Tests\Resources\People;
use \Hexxore\Bunch\Tests\Resources\Gender;
use \Hexxore\Bunch\Tests\Resources\City;
use ReflectionFunction;

final class CollectionTest extends TestCase
{
    private $stock;
    private $first, $second, $third;

    public function setUp() : void {
        $this->first = new StockPrice(1,9,2022,1);
        $this->second = new StockPrice(2,8,2022,2);
        $this->third = new StockPrice(3,7,2022,3);

        $this->stock = new StockPriceCollection (
            $this->first ,
            $this->second ,
            $this->third
        );

        $this->people = new People(
            new Human("Patrick", Gender::Male, City::Ede),
            new Human("Chantal", Gender::Female, City::Ede),
            new Human("Bas", Gender::Male, City::Raalte),
            new Human("Lisa", Gender::Female, City::Raalte),
        );

    }
    public function testCount() { 
        $this->assertEquals( $this->stock->count() , 3 );
    }
    public function testAutoTyped() {
        $this->assertEquals( $this->stock->getType() ,StockPrice::class );
    }
    public function testFirst() {
        $first  = $this->stock->first();
        $this->assertEquals($this->first, $first);
    }
    public function testLast() {
        $last = $this->stock->last();
        $this->assertEquals($this->third, $last);
    }
    public function testSlice() {
        $slice = $this->stock->slice(1,1)->first();
        $this->assertEquals($this->second, $slice);
    }
    public function testReverse() {
        $last = $this->stock->reverse()->last();
        $this->assertEquals($this->first, $last);
    }
    public function testMergeArray() {
        $fourth = new StockPrice(4,6,2022,4);
        $merged = $this->stock->merge( [$fourth] );

        $this->assertEquals($this->stock->first(), $merged->first());
        $this->assertEquals($this->stock->last(), $this->third);
        $this->assertEquals($merged->last(), $fourth);

        $this->assertEquals(4, $merged->count());
        $this->assertEquals(3, $this->stock->count());
    }
    public function testIterator() {
        
        $high = '';
        $low = '';
        foreach ($this->stock as $stockprice ) {
            $high .= (string)$stockprice->high;
            $low .= (string)$stockprice->low;
        }
        $this->assertEquals("987", $high);
        $this->assertEquals("123", $low);
    }
    
    public function testAggregate() {
        $sum = $this->stock->aggregate(fn($sum, $stock) => $sum + $stock->low);
        $expected = 1+2+3 ;
        $this->assertEquals($sum, $expected );
    }
    public function testSelect() {
        $mapped = $this->stock->select( fn($s) => ($s->high *  $s->low) );
        
        $this->assertEquals(9, $mapped->first() );
        $this->assertEquals(16, $mapped->slice(1,1)->first());
        $this->assertEquals(21, $mapped->last());
    }
    public function testWhere() {
        $males = $this->people->where( fn( $h ) => $h->gender == Gender::Male );
        $females = $this->people->where( fn( $h ) => $h->gender == Gender::Female );

        $this->assertEquals(2, $males->count());
        $this->assertEquals('Patrick', $males->first()->name);
        $this->assertEquals(2, $females->count());
        $this->assertEquals('Chantal', $females->first()->name);


        $male_raalte = $males->where( fn( $h ) => $h->city == City::Raalte )->first();
        $female_raalte = $females->where( fn( $h ) => $h->city == City::Raalte )->first();

        $this->assertEquals('Bas', $male_raalte->name);
        $this->assertEquals('Lisa', $female_raalte->name);
    }

    public function testGroupBy() {
        $expected = [
            'male' => ['Patrick', 'Bas'],
            'female' => ['Chantal','Lisa']
        ];

        $result = [];

        $bygender = $this->people->groupBy( fn ( $h ) => $h->gender->value );

        
        foreach ( $bygender as $gender => $group ) {
            foreach ( $group as $human ) {
                $result[$gender][] = $human->name;
            }
        }
        $this->assertEquals($expected, $result);
    }
}

/* 
Some mockup idea for SqlExpressionCaching ( lambda -> sourcecode -> sql -> php factory for rapid compilation )
class SqlExpresion___FILE___LINE {
    const string $hash = __HASH__;
    private function generateHash(\ReflectionFunction $func) {
        
    }
    public function getFactory(... $params) {
        return function( $context ) {
            return [ 
                sql => ' where a.name => ?a.name ',
                bind => [ '?a.name' => $context['a']['name']
            ]
        }
        // throw new cacheInvalidException
    }
    public function isValid(ReflectionFunction $func) {
        if ( $this->generateHash($fileOnDisk)
    }
}*/ 