<?php

use Hexxore\Bunch\Iterators\WhereIterator;
use Hexxore\Matter\Matter;
use Hexxore\Matter\Query\SqlQuery;


/* 

$mongoresult = $sqlite->query(
    // composes a mongo query;
    new MongoQuery('albums',['id'=>['$gt'=>':max_id']],['max_id', 12]);
);
*/ 
// both results should match.

// should we abstract querying dialects? or let the orm do this ?
// if so, the mongo filter is more easy to parse into a mysql query.

// values







// value wrappers for seperation of key/value e.g. user input.




// root of WhereCondition AST


// conditions







// Matter Query Language. should be able to compile this into any language or operation




// Sql uses the following :
    // SELECT_EXPRESSION
    // FROM_EXPRESSION
    // WHERE where_condition ( on select ) // map
    // GROUP_EXPRESSION
    // HAVING where_condition ( on group ) // on the aggregations  ( e.g. where the sum > 100 ).
    // ORDER EXPRESSION
    // LIMIT

var_export($q);
echo PHP_EOL;
echo "\n\n";
echo "SQL:  \n";
echo (new SqlCompiler($q))->compile();

echo "\n\n";
echo "MONGOD:  \n";
var_export( (new MongoCompiler($q) )->compile() );