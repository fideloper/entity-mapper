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

// Not actually required, despite
// what the docs say
$capsule->setAsGlobal();

$capsule->schema()->create('users', function($table)
{
    $table->increments('id');
    $table->string('table');
    $table->string('email');
    $table->integer('votes');
});

