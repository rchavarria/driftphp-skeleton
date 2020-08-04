# Problemas al conectar con el servidor de Websockets

## Proceso seguido

Parto de un proyecto con query bus, command bus y event bus configurados. También
el exchange de AMQP está configurado y funcionando.

1. Añado dependencia en `composer.json`, seguido de `composer update`

```
"require": {
//...
    "drift/websocket-bundle": "*"
},
```

2. Activo el bundle en `bundles.php`

```
return [
//...
  Drift\Websocket\WebsocketBundle::class => [ 'all' => true ]
];
```

3. Configuro las conexiones de Websockets en `services.yml`

```
websocket:
    routes:
        events:
            path: /events
```

4. Creo una clase que se suscribe a recibir eventos de nuevas conexiones,
`src/Domain/EventSubscriber/BroadcastNewConnections.php`

5. Arranco el server, conectándose a la ruta `events` de los websockets, y
escuchando el exchange `my_events` de AMQP

```
bin/console websocket:run \
    0.0.0.0:9009 \
    --no-debug \
    --env=prod \
    --route=events \
    --exchange=my_events
```

Pero al intentar conectar al servidor desde un navegador (o incluso desde
CLI con `curl`), siempre tengo el error:

```
WebSocket connection to 'ws://localhost:9009/events' failed: Error during
WebSocket handshake: Unexpected response code: 404
```

Parece que la petición llega al servidor, y alguna respuesta obtengo. Pero
parece ser que no encuentra la ruta.
