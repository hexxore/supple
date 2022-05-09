#!/usr/bin/php
<?php



# used for converting a sqlite3 database to a mongodb setup.

require_once(__DIR__."/../vendor/autoload.php");


use Hexxore\Matter\Matter;

// load dot env file
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

function env($var) {
    if ( getenv($var) ){
        return getenv($var);
    }
    if ( isset($_ENV[$var]) ) {
        return $_ENV[$var];
    }
}

$matter = new Matter();

$sqlite = $matter->connect([
    'driver' => 'SQLite',
    'database' => __DIR__.'/../tests/Resources/chinook.sqlite'
]);

$client = new MongoDB\Client(
    //'mongodb+srv://'.getenv('MONGO_USERNAME').':'.getenv('MONGO_PASSWORD').'@'.getenv('MONGO_SERVER').'/test?retryWrites=true&w=majority'
    'mongodb://'.env('DB_USERNAME').':'.env('DB_PASSWORD').'@mongodb'
);

$db = $client->test;

/* 
$mongodb = $matter->connect([
    'driver' => 'MongoDB',
    'host' => __DIR__.'/../../chinook.db'
]);
*/ 

// how do we do this as an abstraction to mongodb?
//$sqlite = $sqlite->getPdo();

//$sqlite->query();