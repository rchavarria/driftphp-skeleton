<?php

namespace Domain\EventSubscriber;

use Drift\Websocket\Connection\Connection;
use Drift\Websocket\Event\WebsocketConnectionOpened;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastNewConnections implements EventSubscriberInterface {

  public final function broadcastNewConnection(WebsocketConnectionOpened $event): void {
    $hash = Connection::getConnectionHash($event->getNewConnection());

    echo '* broadcast new connection: ', $hash, PHP_EOL;

    $event
      ->getConnections()
      ->broadcast(json_encode([
        'type' => 'new-connection',
        'connection' => $hash
      ]));
  }

  public static function getSubscribedEvents() {
    return [
      WebsocketConnectionOpened::class => [
        [ 'broadcastNewConnection', 0 ]
      ]
    ];
  }

}
