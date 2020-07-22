<?php

namespace Domain\Model\User;

final class User {

  /** @var string */
  private $uid;

  public function __construct(string $uid) {
    $this->uid = $uid;
  }

  public function getUid(): string {
    return $this->uid;
  }

}
