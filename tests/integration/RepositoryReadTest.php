<?php

use EntityMapper\Repository;

class RepositoryReadTest extends TestCase {

    public function testCanLoadCustomRepository()
    {
        $repo = Repository::getRepository('\User');

        $this->assertInstanceof('\UserRepository', $repo);
    }

    public function testCanLoadDefaultRepository()
    {
        $repo = Repository::getRepository( new noRepositoryDefinedStub );

        $this->assertInstanceof('\EntityMapper\Repository', $repo);
    }

    public function testRepositoryFindsOne()
    {
        $repo = Repository::getRepository('\User');
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
        $repo = Repository::getRepository('\User');
        $repo->findOrFail(999);
    }

    public function testRepositoryGetsAll()
    {
        $repo = Repository::getRepository('\User');
        $allUsers = $repo->all();

        $this->assertTrue( count($allUsers) > 0 );
        $this->assertInstanceOf( '\User', $allUsers->first() );
        $this->assertInstanceof( '\Email', $allUsers->first()->getEmail() );
        $this->assertInstanceOf( '\Votes', $allUsers->first()->getVotes() );
        $this->assertTrue( is_string($allUsers->first()->getName()) );
    }

    public function testRepostoryGetsEntityOnCustomSqlCall()
    {
        $repo = Repository::getRepository('\User');
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