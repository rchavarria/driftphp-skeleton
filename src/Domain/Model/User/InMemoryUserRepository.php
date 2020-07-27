<?php

namespace Domain\Model\User;

use React\Promise\PromiseInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

class InMemoryUserRepository implements UserRepository {

  private $users = [];

  function save(User $user): PromiseInterface {
    $this->users[$user->getUid()] = $user;
    return resolve();
  }

  function find(string $uid): PromiseInterface {
    if (!array_key_exists($uid, $this->users)) {
      return reject(new UserNotFoundException('User not found'));
    }

    return resolve($this->users[$uid]);
  }

  function delete(string $uid): PromiseInterface {
    // TODO: Implement delete() method.
  }
}
