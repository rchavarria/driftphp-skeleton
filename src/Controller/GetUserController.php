<?php

/*
 * This file is part of the DriftPHP package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Transformer\UserTransformer;
use Domain\Model\User\User;
use Domain\Model\User\UserNotFoundException;
use Domain\Query\GetUser;
use Drift\CommandBus\Bus\QueryBus;
use Drift\CommandBus\Exception\InvalidCommandException;
use React\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetUserController {

  /** @var QueryBus */
  private $bus;

  public function __construct(QueryBus $bus) {
    $this->bus = $bus;
  }

  /**
   * @param string $uid
   *
   * @return PromiseInterface<Response>
   *
   * @throws InvalidCommandException
   */
  public function __invoke(string $uid) {
    return $this->bus
      ->ask(new GetUser($uid))
      ->then(function (User $user) {
        return new JsonResponse(UserTransformer::toArray($user));
      })
      ->otherwise(function (UserNotFoundException $exception) {
        return new Response('Not found', 404);
      });
  }
}
