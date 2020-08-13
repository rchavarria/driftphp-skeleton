# Problemas conectando al servidor de Websockets

Durante el curso, durante la realización del ejercicio final del curso, e incluso
ejecutando la demo oficial de DriftPHP, soy incapaz de conectarme al servidor
de Websockets.

Continuamente, tengo un error HTTP 404 al intentar conectar. Por lo que la
petición llega al servidor, pero éste la rechaza, como que no encuentra la
ruta.

## Contexto

Estoy desarrollando sobre Windows.

El código fuente está en Windows.

Para ejecutarlo, levanto contenedores Docker, a través de Docker Desktop, mapeando
el directorio del código fuente. Por lo que el código fuente lo pilla de
Windows.

## Causa

Investigando en el código fuente del `drift/websocket-bundle`, el camino me
ha llevado por:

- `\Drift\Websocket\Connection\WebsocketServer::createServer`, donde crea una
instancia de una aplicación de Ratchet (la librería basada en ReactPHP para
crear un servidor de Websockets)
- `\Ratchet\App::__construct`, donde crea el servidor de Websockets, a base
de ReactPHP servers (IoServer, HttpServer,...). A este servidor se le pasa
una instancia de `UrlMatcher`
- `\Symfony\Component\Routing\Matcher\UrlMatcher::matchCollection` es llamado
en algún momento cuando un cliente intenta conectarse. Busca la ruta de la
petición en la lista de rutas que tiene. En un punto, compara el host donde 
está escuchando el servidor, con la dirección de la petición. En mi caso,
compara `0.0.0.0` con `localhost`, y como no cuadra, dice que no encuentra la
ruta. Ahí tienes el error 404.

El server lo ejecuto con el comando (pasado al contenedor Docker):

```
php bin/console websocket:run 0.0.0.0:9009 --route=events --exchange=my_events
```

Mientras que la dirección que uso para conectarme al server es:

```
ws://localhost:9009/events
```

`ws://0.0.0.0:9009/events` no funciona, Windows (o Docker, quien sea), no
dirige la petición al servidor.

## Solución

En lugar de arrancar el servidor en `0.0.0.0`, lo arranco en `localhost`. En
teoría es lo mismo, pero al haber contenedores y Windows de por medio, en la
práctica no es para nada lo mismo.

## Detalles del código

```
en \Drift\Websocket\Connection\WebsocketServer::createServer
        $server = new App($httpHost, $port, $address, $this->loop);


en \Ratchet\App::__construct
        $this->_server = new IoServer(new HttpServer(new Router(new UrlMatcher($this->routes, new RequestContext))), $socket, $loop);


vendor/symfony/routing/Matcher/UrlMatcher.php:173
\Symfony\Component\Routing\Matcher\UrlMatcher::matchCollection
```
