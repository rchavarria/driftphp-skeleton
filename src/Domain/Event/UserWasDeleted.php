<?php

namespace Domain\Event;

class UserWasDeleted {

  /** @var  */
  private $uid;

  public function __construct(string $uid) {
    $this->uid = $uid;
  }

}
