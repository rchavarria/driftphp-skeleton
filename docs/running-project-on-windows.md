# Problemas al ejecutar el proyecto en Windows

Tengo problemas al instalar las dependencias en mi entorno.

Uso Windows, con PHP 7.4 (instalado con Wamp), y tengo también la herramienta
`composer` instalada y funcionando.

Al ejecutar `composer install` para el proyecto, ¡boom!

```
drift/server 0.1.13 requires ext-pcntl * -> the requested PHP extension pcntl is missing from your system.
```

Voy a mi `php.ini` para activarla, y no es posible. Al parecer, no es una extensión
fácil de instalar en Windows.

## Solución 1: usar `docker-compose.yml` proporcionado por el proyecto

El proyecto está pensado para utilizar Docker, con lo que trae unos cuantos
archivos para ayudarnos.

El `docker-compose.yml` que trae está genial: parte de una imagen con la versión
de PHP necesario, con `composer`, con todo.

Este fichero crea una imagen, definida en el fichero `Dockerfile`. En esta imagen
se copian los ficheros del proyecto y se instalan las dependencias.

**Problema**: los ficheros del proyecto, las dependencias y todo lo necesario
para desarrollar están en la imagen creada, con lo que no puedo trabajar en mi
local y ver los cambios ejecutándose. En mi local tampoco tengo las dependencias,
con lo que el IDE no me puede ayudar con el autocompletado de las clases de
DriftPHP o de otras librerías.

## Solución 2: usar Docker para instalar dependencias en local

Pero no está todo perdido.

Puedo usar la imagen Docker de donde parte todo, `driftphp/base`, para instalar
las dependencias, pero en mi local.

De esta forma, tendré las librerías accesibles para mi IDE, y podré trabajar
cómodamente con ellas.

```
docker run \
    -v /c/Projects/driftphp-skeleton:/var/www \
    driftphp/base \
    composer update
```

Con ese comando:

- ejecuto la imagen `driftphp/base`, proporcionada por Marc, el creador del 
proyecto
- mapeo el directorio de trabajo de la imagen, `/var/www` al directorio de mi
proyecto, para que lea y escriba en mi sistema de ficheros
- ejecuto el comando `composer update` en el contenedor Docker. Al final, éste
es el comando que quiero ejecutar

Una vez instaladas las dependencias, hay que ejecutar el server de DriftPHP.
En lugar de usar el `docker-compose.yml` del proyecto, tengo que usar la misma
imagen que en el comando anterior, para poder usar los ficheros y las librerías
con las que estoy trabajando en local:

```
docker run \
    -p 8000:8000 \
    -v /c/Projects/driftphp-skeleton:/var/www \
    driftphp/base \
    php vendor/bin/server watch 0.0.0.0:8000
```

- ejecuto la imagen `driftphp/base`
- exporto el puerto `8000`
- mapeo el directorio de trabajo de la imagen a mi directorio local, donde tengo
mi código fuente
- el comando que quiero que ejecute el contendor es `php vendor/bin/server watch 0.0.0.0:8000`,
que lo que hace es ejecutar el server de DriftPHP, esperando cambios en los
ficheros (`watch`) y escuchando conexiones en el puerto `8000`

La salida de ese comando es:

```
>  
>  ReactPHP HTTP Server for DriftPHP
>    by Marc Morera (@mmoreram)
>  
>  Host: 0.0.0.0
>  Port: 8000
>  Environment: prod
>  Debug: disabled
>  Static Folder: /public/
>  Adapter: Drift\Server\Adapter\DriftKernelAdapter
>  Exchanges subscribed: disabled
>  Loaded bootstrap file: /var/www/Drift/config/bootstrap.php
>  

SRV (~ | 5 MB) - EventLoop is running.

[PHP-Watcher] 0.5.1
[PHP-Watcher] watching: /var/www/Drift/, /var/www/src/, /var/www/public/
[PHP-Watcher] starting `php vendor/bin/server run --debug 0.0.0.0:8000 --no-header`
SRV (~ | 8 MB) - Kernel built.
SRV (~ | 8 MB) - Kernel preloaded.
SRV (~ | 8 MB) - Kernel ready to accept requests.
SRV (~ | 8 MB) - EventLoop is running.

```

Y si ejecuto en una consola en local:

```
curl -s localhost:8000
```

Veo:

```
{"message":"DriftPHP is working!"}
```

Que es justo la respuesta generada en `DefaultController`.
