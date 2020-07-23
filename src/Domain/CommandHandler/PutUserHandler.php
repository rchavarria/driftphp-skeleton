<?php

namespace Domain\CommandHandler;

use Domain\Command\PutUser;
use Domain\Model\User\UserRepository;
use React\Promise\PromiseInterface;

class PutUserHandler {

  /** @var UserRepository */
  private $userRepository;

  public function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  /**
   * @param PutUser $putUser
   *
   * @return PromiseInterface
   */
  public function handle(PutUser $putUser): PromiseInterface {
    return $this
      ->userRepository
      ->save($putUser->getUser());
  }

}
