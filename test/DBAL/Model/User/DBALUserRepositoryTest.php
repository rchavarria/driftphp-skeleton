<?php

namespace Test\Infrastructure\DBAL\Model\User;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Domain\Model\User\UserRepository;
use Drift\DBAL\Connection;
use Drift\DBAL\Credentials;
use Drift\DBAL\Driver\SQLite\SQLiteDriver;
use Infrastructure\DBAL\Model\User\DBALUserRepository;
use React\EventLoop\LoopInterface;
use Test\Domain\Model\User\UserRepositoryTest;

class DBALUserRepositoryTest extends UserRepositoryTest {

  protected function createEmptyRepository(LoopInterface $loop): UserRepository {
    $platform = new SqlitePlatform();
    $driver = new SQLiteDriver($loop);
    $credentials = new Credentials(
      '',
      '',
      'root',
      'root',
      ':memory:'
    );

    $connection = Connection::createConnected($driver, $credentials, $platform);
    $connection->createTable('users', [
      'uid' => 'string',
      'name' => 'string'
    ]);

    return new DBALUserRepository($connection);
  }
}
