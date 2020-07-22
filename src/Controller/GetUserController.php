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

use Domain\Query\GetUser;
use Drift\CommandBus\Bus\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetUserController {

  /** @var QueryBus */
  private $bus;

  public function __construct(QueryBus $bus) {
    $this->bus = $bus;
  }

  public function __invoke(Request $request) {
    $uid = $request->get('uid');

    return $this->bus
      ->ask(new GetUser($uid))
      ->then(function ($user) {
        return new JsonResponse($user, 200);
      });
  }
}
