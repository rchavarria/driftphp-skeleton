<?php

namespace App\Controller;

use Domain\Command\DeleteUser;
use Drift\CommandBus\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserController {

  /** @var CommandBus */
  private $bus;

  public function __construct(CommandBus $bus) {
    $this->bus = $bus;
  }

  public function __invoke(string $uid) {
    return $this
      ->bus
      ->execute(new DeleteUser($uid))
      ->then(function () {
        return new Response('', 204);
      });
  }

}
