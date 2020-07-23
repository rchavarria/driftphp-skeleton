<?php

namespace Domain\QueryHandler;

use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use Domain\Query\GetUser;
use React\Promise\PromiseInterface;

class GetUserHandler {

  /** @var UserRepository */
  private $userRepository;

  public function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  /**
   * @param GetUser $getUser
   *
   * @return PromiseInterface<User>
   *
   * @throws UserNotFoundException
   */
  public function handle(GetUser $getUser): PromiseInterface {
    return $this
      ->userRepository
      ->find($getUser->getUid());
  }
}
