<?php

namespace Domain\Model\User;

use React\Promise\PromiseInterface;

interface UserRepository {

  /**
   * @param User $user
   *
   * @return PromiseInterface
   */
  function save(User $user): PromiseInterface;

  /**
   * @param string $uid
   *
   * @return PromiseInterface<User>
   *
   * @throws UserNotFoundException
   */
  function find(string $uid): PromiseInterface;

  /**
   * @param string $uid
   *
   * @return PromiseInterface
   *
   * @throws UserNotFoundException
   */
  function delete(string $uid): PromiseInterface;

}
