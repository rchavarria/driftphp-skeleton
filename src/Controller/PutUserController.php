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
use Domain\Command\PutUser;
use Drift\CommandBus\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PutUserController {

  /** @var CommandBus */
  private $bus;

  public function __construct(CommandBus $bus) {
    $this->bus = $bus;
  }

  public function __invoke(Request $request) {
    $body = $request->getContent();
    $userAsArray = json_decode($body, true);
    $userAsArray['uid'] = $request->get('uid');

    $user = UserTransformer::fromArray($userAsArray);
    $command = new PutUser($user);

    return $this->bus
      ->execute($command)
      ->then(function () use ($user) {
        $uid = $user->getUid();
        return new JsonResponse("User with uid [$uid] will be created", 202);
      });
  }
}
