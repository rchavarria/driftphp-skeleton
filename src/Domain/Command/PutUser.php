<?php

namespace Domain\Command;

use Domain\Model\User\User;

class PutUser {

  /** @var User */
  private $user;

  public function __construct(User $user) {
    $this->user = $user;
  }

  public function getUser(): User {
    return $this->user;
  }

}
