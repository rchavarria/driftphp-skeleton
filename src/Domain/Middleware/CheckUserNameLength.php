<?php

namespace Domain\Middleware;

use Domain\Command\PutUser;
use Domain\Model\User\NameTooShortException;
use Drift\CommandBus\Middleware\DiscriminableMiddleware;
use React\Promise\PromiseInterface;
use function React\Promise\reject;

class CheckUserNameLength implements DiscriminableMiddleware {

  public function execute(PutUser $command, callable $next): PromiseInterface {
    $name = $command->getUser()->getName();
    if (strlen($name) < 5) {
      return reject(new NameTooShortException());
    }

    return $next($command);
  }

  public function onlyHandle(): array {
    return [
      PutUser::class
    ];
  }
}
