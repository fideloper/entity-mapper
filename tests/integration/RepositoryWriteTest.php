<?php

use EntityMapper\Repository;

class RepositoryWriteTest extends TestCase {

    public function testCanSaveNewUser()
    {
        $user = new User('Chris', new Email('chris@example.com'), 10);

        $repo = Repository::getRepository('\User');

        $userId = $repo->save($user);

        $user2 = $repo->find($userId);

        $this->assertTrue( is_numeric($userId) );
        $this->assertTrue( is_numeric($user->id()) );

        $this->assertEquals( $userId, $user2->id() );
        $this->assertEquals( $user->getName(), $user2->getName() );
        $this->assertEquals( $user->getEmail(), $user2->getEmail() );
        $this->assertEquals( $user->getVotes(), $user2->getVotes() );
    }

    public function testCanUpdateUser()
    {
        $repo = Repository::getRepository('\User');

        $user = $repo->find(1);

        $newEmail = $this->randomString('5').'@'.$this->randomString('8').'.com';
        $newVotes = mt_rand(0, 100);
        $user->setEmail( new Email($newEmail) );
        $user->setVotes( $newVotes );

        $repo->save($user);

        $updatedUser = $repo->find(1);

        $this->assertEquals( $user->id(), $updatedUser->id() );
        $this->assertEquals( $user->getName(), $updatedUser->getName() );
        $this->assertEquals( $newEmail, $updatedUser->getEmail() );
        $this->assertEquals( $user->getEmail(), $updatedUser->getEmail() );
        $this->assertEquals( (string)$newVotes, $updatedUser->getVotes() );
        $this->assertEquals( $user->getVotes(), $updatedUser->getVotes() );
    }

}