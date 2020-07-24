<?php

namespace Domain\CommandHandler;

use Domain\Command\DeleteUser;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use React\Promise\PromiseInterface;

class DeleteUserHandler {

  /**
   * @var UserRepository
   */
  private $repository;

  public function __construct(UserRepository $repository) {
    $this->repository = $repository;
  }

  /**
   * @param DeleteUser $deleteUser
   *
   * @return PromiseInterface
   *
   * @throws UserNotFoundException
   */
  public function handle(DeleteUser $deleteUser): PromiseInterface {
    return $this
      ->repository
      ->delete($deleteUser->getUid());
  }
}
