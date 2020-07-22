<?php

namespace Domain\QueryHandler;

use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Domain\Query\GetUser;
use React\Promise\PromiseInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

class GetUserHandler {

  /**
   * @param GetUser $getUser
   *
   * @return PromiseInterface<User>
   *
   * @throws UserNotFoundException
   */
  public function handle(GetUser $getUser): PromiseInterface {
    $uid = $getUser->getUid();

    // lanzar excepción
    if ($uid === '10') {
      return reject(new UserNotFoundException());
    }

    return resolve(new User($uid));
  }
}
