<?php

namespace Domain\Event;

use Domain\Model\User\User;

class UserWasCreated {

  /** @var User */
  private $user;

  public function __construct(User $user) {
    $this->user = $user;
  }

  public function getUser(): User {
    return $this->user;
  }

}
