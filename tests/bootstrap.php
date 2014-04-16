<?php

require_once('vendor/autoload.php');
require_once('tests/TestCase.php');
require_once('stubs/Email.php');
require_once('stubs/Votes.php');
require_once('stubs/User.php');
require_once('stubs/UserRepository.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => ':memory:',
    'prefix'   => '',
]);

// Actually required, despite
// what the docs say
$capsule->setAsGlobal();

$app = $capsule->getContainer();
$app['config']['database.fetch'] = PDO::FETCH_CLASS;

$capsule->schema()->create('users', function($table)
{
    $table->increments('id');
    $table->string('name');
    $table->string('email');
    $table->integer('votes');
});

$capsule->table('users')->insert([
    'name' => 'Chris',
    'email' => 'chris@example.com',
    'votes' => 20,
]);

$capsule->table('users')->insert([
    'name' => 'Bob',
    'email' => 'bob@example.com',
    'votes' => 10,
]);

$capsule->table('users')->insert([
    'name' => 'Dan',
    'email' => 'dan@example.com',
    'votes' => 0,
]);