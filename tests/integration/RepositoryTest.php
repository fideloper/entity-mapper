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

    public function testRepositoryFindsOne()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');
        $user = $repo->find(1);

        $this->assertEquals( 1, $user->id() );
        $this->assertInstanceOf( '\User', $user );
        $this->assertInstanceOf( '\Email', $user->getEmail() );
        $this->assertInstanceOf( '\Votes', $user->getVotes() );
        $this->assertTrue( is_string($user->getName()) );
    }

    /**
     * @expectedException \EntityMapper\EntityNotFoundException
     */
    public function testRepositoryFailsIfNotFound()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');
        $repo->findOrFail(999);
    }

    public function testRepositoryGetsAll()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');
        $allUsers = $repo->all();

        $this->assertTrue( count($allUsers) > 0 );
        $this->assertInstanceOf( '\User', $allUsers->first() );
        $this->assertInstanceof( '\Email', $allUsers->first()->getEmail() );
        $this->assertInstanceOf( '\Votes', $allUsers->first()->getVotes() );
        $this->assertTrue( is_string($allUsers->first()->getName()) );
    }

    public function testRepostoryGetsEntityOnCustomSqlCall()
    {
        $repo = \EntityMapper\Repository::getRepository('\User');
        $users = $repo->where('votes', '>', 0)->get();

        $this->assertTrue( count($users) > 0 );
        $this->assertInstanceOf( '\User', $users->first() );
        $this->assertInstanceof( '\Email', $users->first()->getEmail() );
        $this->assertInstanceOf( '\Votes', $users->first()->getVotes() );
        $this->assertTrue( is_string($users->first()->getName()) );
    }
}

/**
 * Class noRepositoryDefinedStub
 * @table example
 */
class noRepositoryDefinedStub {}