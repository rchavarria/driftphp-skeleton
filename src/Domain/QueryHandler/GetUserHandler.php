<?php

namespace Domain\QueryHandler;

use Domain\Query\GetUser;

class GetUserHandler {

  public function handle(GetUser $getUser): array {
    // mock user
    return [
      'uid' => $getUser->getUid()
    ];
  }
}
