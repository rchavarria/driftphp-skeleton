<?php

namespace Domain\CommandHandler;

use Domain\Command\PutUser;

class PutUserHandler {

  public function handle(PutUser $putUser) {
    return [
      'uid' => $putUser->getUid()
    ];
  }

}
