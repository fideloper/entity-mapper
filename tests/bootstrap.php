<?php

require_once('vendor/autoload.php');
require_once('tests/TestCase.php');
require_once('stubs/Email.php');
require_once('stubs/Votes.php');
require_once('stubs/User.php');
require_once('stubs/UserRepository.php');

use EntityMapper\Repository;
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
    $table->string('username');
    $table->string('email');
    $table->integer('votes');
});

$capsule->table('users')->insert([
    'username' => 'Chris',
    'email' => 'chris@example.com',
    'votes' => 20,
]);

$capsule->table('users')->insert([
    'username' => 'Bob',
    'email' => 'bob@example.com',
    'votes' => 10,
]);

$capsule->table('users')->insert([
    'username' => 'Dan',
    'email' => 'dan@example.com',
    'votes' => 0,
]);

$app = $capsule->getContainer();
$sp = new \EntityMapper\EntityMapperServiceProvider($app);
$sp->register();

Repository::setConnectionResolver( $capsule->getDatabaseManager() );