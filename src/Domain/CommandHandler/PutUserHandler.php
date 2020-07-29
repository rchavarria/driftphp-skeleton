<?php

namespace Domain\CommandHandler;

use Domain\Command\PutUser;
use Domain\Event\UserWasCreated;
use Domain\Model\User\UserRepository;
use Drift\EventBus\Bus\EventBus;
use React\Promise\PromiseInterface;

class PutUserHandler {

  /** @var UserRepository */
  private $userRepository;
  /** @var EventBus */
  private $eventBus;

  public function __construct(UserRepository $userRepository, EventBus $eventBus) {
    $this->userRepository = $userRepository;
    $this->eventBus = $eventBus;
  }

  /**
   * @param PutUser $putUser
   *
   * @return PromiseInterface
   */
  public function handle(PutUser $putUser): PromiseInterface {
    return $this
      ->userRepository
      ->save($putUser->getUser())
      ->then(function () use ($putUser) {
        return $this
          ->eventBus
          ->dispatch(new UserWasCreated($putUser->getUser()));
      });
  }

}
