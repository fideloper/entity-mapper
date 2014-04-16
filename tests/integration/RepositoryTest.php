<?php

class RepositoryTest extends TestCase {

    protected $app;

    public function setUp()
    {
        global $capsule;

        $this->app = $capsule->getContainer();
        $sp = new \EntityMapper\EntityMapperServiceProvider($this->app);
        $sp->register();
    }

    public function testCanLoadCustomRepository()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');

        $this->assertInstanceof('\UserRepository', $repo);
    }
} 