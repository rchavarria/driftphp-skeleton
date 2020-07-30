<?php

namespace Domain\Model\User;

use React\Promise\PromiseInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

class InMemoryUserRepository implements UserRepository {

  private $users = [];

  /**
   * @param User[] $users Tiene que ser un array asociativo donde las claves sean
   * los uids
   */
  public function loadFromArray(array $users): void {
    $this->users = $users;
  }

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
    unset($this->users[$uid]);
    return resolve();
  }
}
