<?php

namespace Domain\Model\User;

use Domain\Event\UserWasCreated;
use Domain\Event\UserWasDeleted;
use Drift\HttpKernel\AsyncKernelEvents;
use Drift\HttpKernel\Event\DomainEventEnvelope;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ComposedUserRepository implements UserRepository, EventSubscriberInterface {

  /** @var InMemoryUserRepository */
  private $memory;
  /** @var PersistentUserRepository */
  private $persistent;

  public function __construct(InMemoryUserRepository $memory, PersistentUserRepository $persistent) {
    $this->memory = $memory;
    $this->persistent = $persistent;
  }

  function save(User $user): PromiseInterface {
    return $this->persistent->save($user);
  }

  function find(string $uid): PromiseInterface {
    return $this->memory->find($uid);
  }

  function delete(string $uid): PromiseInterface {
    return $this->persistent->delete($uid);
  }

  public function loadAllUsersToMemory(DomainEventEnvelope $event): PromiseInterface {
    // this way, I can fetch the domain event emitted in the original server
    $myDomainEvent = $event->getDomainEvent();

    return $this
      ->persistent
      ->findAll()
      ->then(function (array $users) {
        $this
          ->memory
          ->loadFromArray($users);
      });
  }

  public static function getSubscribedEvents() {
    return [
      UserWasCreated::class => [
        [ 'loadAllUsersToMemory', 0 ]
      ],
      UserWasDeleted::class => [
        [ 'loadAllUsersToMemory', 0 ]
      ],
      AsyncKernelEvents::PRELOAD => [
        [ 'loadAllUsersToMemory', 0 ]
      ]
    ];
  }
}
