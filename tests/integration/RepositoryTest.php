<?php

use EntityMapper\Repository;

class RepositoryTest extends TestCase {

    protected $app;

    public function setUp()
    {
        global $capsule;

        $this->app = $capsule->getContainer();
        $sp = new \EntityMapper\EntityMapperServiceProvider($this->app);
        $sp->register();

        Repository::setConnectionResolver( $capsule->getDatabaseManager() );
    }

    public function testCanLoadCustomRepository()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');

        $this->assertInstanceof('\UserRepository', $repo);
    }

    public function testCanLoadDefaultRepository()
    {
        $repo = \EntityMapper\Repository::getRepository( new noRepositoryDefinedStub );

        $this->assertInstanceof('\EntityMapper\Repository', $repo);
    }

    public function testTesting()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');
        var_dump($repo->find(1));

        $this->assertTrue(true);
    }
}

/**
 * Class noRepositoryDefinedStub
 * @table example
 */
class noRepositoryDefinedStub {}