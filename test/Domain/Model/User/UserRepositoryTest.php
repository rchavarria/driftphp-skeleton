<?php

namespace Test\Domain\Model\User;

use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use function Clue\React\Block\await;

abstract class UserRepositoryTest extends TestCase {

  /** @var LoopInterface */
  private $loop;

  public function setUp() {
    $this->loop = Factory::create();
  }

  abstract protected function createEmptyRepository(LoopInterface $loop): UserRepository;

  public function testUserNotFound() {
    $repository = $this->createEmptyRepository($this->loop);
    $promise = $repository->find('1234');

    $this->expectException(UserNotFoundException::class);
    await($promise, $this->loop);
  }
  
  public function testUserExists() {
    $user = new User('1234', 'Percebes');
    $repository = $this->createEmptyRepository($this->loop);
    $promise = $repository->save($user)
      ->then(function () use ($repository) {
        return $repository->find('1234');
      });

    /** @var User $user */
    $user = await($promise, $this->loop);
    $this->assertEquals('1234', $user->getUid());
  }

  public function testUserTwice() {
    $repository = $this->createEmptyRepository($this->loop);

    $user1 = new User('1234', 'Percebes');
    await($repository->save($user1), $this->loop);

    $user2 = new User('1234', 'Engonga');
    await($repository->save($user2), $this->loop);

    $user = await($repository->find('1234'), $this->loop);
    $this->assertNotEquals($user, $user1);
    $this->assertEquals($user, $user2);
  }

  public function testFindRightUser() {
    $repository = $this->createEmptyRepository($this->loop);

    $user = new User('1234', 'Percebes');
    await($repository->save($user), $this->loop);

    $this->expectException(UserNotFoundException::class);
    await($repository->find('4321'), $this->loop);
  }

}
