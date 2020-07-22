<?php

namespace Domain\CommandHandler;

use Domain\Command\PutUser;
use Domain\Model\User\User;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class PutUserHandler {

  /**
   * @param PutUser $putUser
   *
   * @return PromiseInterface
   */
  public function handle(PutUser $putUser): PromiseInterface {
    $user = new User(
      $putUser->getUid(),
      $putUser->getName()
    );

    return resolve($user);
  }

}
