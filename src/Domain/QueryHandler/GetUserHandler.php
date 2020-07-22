<?php

namespace App\Domain\QueryHandler;

use Domain\Query\GetUser;

class GetUserHandler {

  public function handle(GetUser $getUser) {
    // tiene que devolver un array
    return [
        'uid' => $getUser->getUid()
    ];
  }
}
