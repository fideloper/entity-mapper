<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class TestCase extends PHPUnit_Framework_TestCase {

    protected $capsule;

    public function setUp()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'sqlite' => array(
                'driver'   => 'sqlite',
                'database' => __DIR__.'/testing.sqlite',
                'prefix'   => '',
            ),
        ]);
    }
}