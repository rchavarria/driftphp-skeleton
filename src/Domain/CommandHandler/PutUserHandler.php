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
    $user = $putUser->getUser();

    // simular que se crea el usuario
    $uid = $user->getUid();
    $name = $user->getName();
    echo 'User with uid [', $uid, '] and name [', $name, '] created', PHP_EOL;

    // al menos, devolver una promesa vacía
    return resolve();
  }

}
