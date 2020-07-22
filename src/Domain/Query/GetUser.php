<?php

namespace Domain\Query;

class GetUser {

  /** @var string */
  private $uid;

  /**
   * GetUser constructor.
   * @param string $uid
   */
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
