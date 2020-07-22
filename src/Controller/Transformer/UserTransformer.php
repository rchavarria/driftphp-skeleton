<?php

namespace App\Controller\Transformer;

use Domain\Model\User\User;

class UserTransformer {

  public static function fromArray(array $userAsArray): User {
    return new User($userAsArray['uid']);
  }

  public static function toArray(User $user): array {
    return [
      'uid' => $user->getUid()
    ];
  }

}
