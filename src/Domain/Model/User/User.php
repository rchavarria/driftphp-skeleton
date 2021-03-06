<?php

namespace Domain\Model\User;

final class User {

  /** @var string */
  private $uid;
  /** @var string */
  private $name;

  public function __construct(string $uid, string $name) {
    $this->uid = $uid;
    $this->name = $name;
  }

  public function getUid(): string {
    return $this->uid;
  }

  public function getName(): string {
    return $this->name;
  }

}
