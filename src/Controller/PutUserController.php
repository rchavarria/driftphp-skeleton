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

use Domain\Command\PutUser;
use Drift\CommandBus\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;

class PutUserController {

  /** @var CommandBus */
  private $bus;

  public function __construct(CommandBus $bus) {
    $this->bus = $bus;
  }

  public function __invoke(string $uid) {
    return $this->bus
      ->execute(new PutUser($uid))
      ->then(function ($foo) {
        return new JsonResponse('Creado');
      });
  }
}
