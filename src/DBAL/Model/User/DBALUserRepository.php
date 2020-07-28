<?php

namespace Infrastructure\DBAL\Model\User;

use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use Drift\DBAL\Connection;
use Drift\DBAL\Result;
use React\Promise\PromiseInterface;

class DBALUserRepository implements UserRepository {

  /** @var Connection */
  private $connection;

  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  function save(User $user): PromiseInterface {
    //
    // aquí estamos trasformando un usuario en un array, como los transformers del contorller
    //
    $ids = [
      'uid' => $user->getUid()
    ];
    $columns = [
      'name' => $user->getName()
    ];

    return $this
      ->connection
      ->upsert('users', $ids, $columns);
  }

  function find(string $uid): PromiseInterface {
    return $this
      ->connection
      ->findOneBy('users', [ 'uid' => $uid ])
      ->then(function ($userAsArray) {
        return new User(
          $userAsArray['uid'],
          $userAsArray['name']
        );
      })
      ->otherwise(function () use ($uid) {
        throw new UserNotFoundException("User with id [$uid] not found");
      });

//    $queryBuilder = $this
//      ->connection
//      ->createQueryBuilder();
//
//    $queryBuilder->select('*')
//      ->from('users', 'u')
//      ->where('u.uid = :uid')
//      ->setParameter('uid', $uid)
//      ->setMaxResults(1);
//
//    return $this
//      ->connection
//      ->query($queryBuilder)
//      ->then(function (Result $result) use ($uid) {
//        $userAsArray = $result->fetchFirstRow();
//        if (is_null($userAsArray)) {
//          throw new UserNotFoundException("User with id [$uid] not found");
//        }
//
//        return new User(
//          $userAsArray['uid'],
//          $userAsArray['name']
//        );
//      });
  }

  function delete(string $uid): PromiseInterface {
    return $this
      ->connection
      ->delete('users', [ 'uid' => $uid ])
      ->then(function (Result $result) use ($uid) {
        // what to do if no user was found
        if ($result->fetchCount() === 0) {
          throw new UserNotFoundException("User with id [$uid] not found");
        }

        return true;
      });
  }
}
