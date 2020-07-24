<?php

namespace Domain\Command;

class DeleteUser {

  /**
   * @var string
   */
  private $uid;

  public function __construct(string $uid) {
    $this->uid = $uid;
  }

  /**
   * @return string
   */
  public function getUid(): string {
    return $this->uid;
  }

}
