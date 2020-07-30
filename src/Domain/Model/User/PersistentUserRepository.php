<?php

namespace Domain\Model\User;

use React\Promise\PromiseInterface;

interface PersistentUserRepository extends UserRepository {

  function findAll(): PromiseInterface;

}
