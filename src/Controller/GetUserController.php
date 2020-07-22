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

use Drift\CommandBus\Bus\QueryBus;
use React\Promise\FulfilledPromise;
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
    return new FulfilledPromise(
      new JsonResponse([
        'message' => "Me has pasado el uid '$uid'",
      ], 200)
    );
  }
}
