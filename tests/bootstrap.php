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

$capsule->schema()->create('posts', function($table)
{
    $table->increments('id');
    $table->integer('user_id');
    $table->string('title');
    $table->text('body');
});

// Users
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

// Posts
$capsule->table('posts')->insert([
    'title' => 'This is post one',
    'body' => 'Bacon ipsum dolor sit amet sausage ham hock tenderloin, filet mignon t-bone kielbasa chicken frankfurter leberkas sirloin.',
    'user_id' => 1,
]);

$capsule->table('posts')->insert([
    'title' => 'This is post two',
    'body' => 'Ribeye meatloaf chuck tri-tip brisket shankle pork cow tail sirloin. Bresaola shank flank brisket tri-tip chuck pork loin turkey ribeye swine porchetta.',
    'user_id' => 2,
]);

$capsule->table('posts')->insert([
    'title' => 'This is post three',
    'body' => 'Ribeye t-bone sausage corned beef, ground round capicola ham. Bacon fatback cow frankfurter venison spare ribs. T-bone andouille hamburger pork belly porchetta kevin.',
    'user_id' => 2,
]);

$app = $capsule->getContainer();
$sp = new \EntityMapper\EntityMapperServiceProvider($app);
$sp->register();

Repository::setConnectionResolver( $capsule->getDatabaseManager() );