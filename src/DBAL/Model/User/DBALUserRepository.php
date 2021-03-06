<?php

namespace Infrastructure\DBAL\Model\User;

use Domain\Model\User\PersistentUserRepository;
use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Drift\DBAL\Connection;
use Drift\DBAL\Result;
use React\Promise\PromiseInterface;

class DBALUserRepository implements PersistentUserRepository {

  /** @var Connection */
  private $connection;

  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  function save(User $user): PromiseInterface {
    //
    // aquí estamos trasformando un usuario en un array, como los transformers del controller
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
    $queryBuilder = $this
      ->connection
      ->createQueryBuilder();

    $queryBuilder->select('*')
      ->from('users', 'u')
      ->where('u.uid = ?')
      ->setParameters([ $uid ])
      ->setMaxResults(1);

    return $this
      ->connection
      ->query($queryBuilder)
      ->then(function (Result $result) use ($uid) {
        $userAsArray = $result->fetchFirstRow();
        if (is_null($userAsArray)) {
          throw new UserNotFoundException("User with id [$uid] not found");
        }

        return new User(
          $userAsArray['uid'],
          $userAsArray['name']
        );
      });
  }

  function delete(string $uid): PromiseInterface {
    return $this
      ->connection
      ->delete('users', [ 'uid' => $uid ])
      ->then(function (Result $result) use ($uid) {
        // what to do if no user was found
        if ($result->getAffectedRows() !== 1) {
          // TODO there is no test for this exception
          throw new UserNotFoundException("User with id [$uid] not found");
        }

        return true;
      });
  }

  function findAll(): PromiseInterface {
    $queryBuilder = $this
      ->connection
      ->createQueryBuilder()
      ->select('*')
      ->from('users', 'u');

    return $this
      ->connection
      ->query($queryBuilder)
      ->then(function (Result $result) {
        $usersAsArray = $result->fetchAllRows();

        $users = [];
        foreach ($usersAsArray as $userAsArray) {
          $uid = $userAsArray['uid'];
          $name = $userAsArray['name'];
          $users[$uid] = new User($uid, $name);
        }

        return $users;
      });
  }
}
