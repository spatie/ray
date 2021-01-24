---
title: Docker
weight: 1
---

When developing using Docker, the Ray host should point to the internal IP of your Docker host by using 'host.docker.internal' in [the config file](/docs/ray/v1/configuration/general).

```php
// save this in a file called "ray.php"
<?php
return [
    /*
     *  The host used to communicate with the Ray app.
     */
    'host' => 'host.docker.internal',

    /*
     *  The port number used to communicate with the Ray app. 
     */
    'port' => 23517,
    
    /*
     *  Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development server.
     */
    'remote_path' => null,
    
    /*
     *  Absolute base path for your sites or projects on your local computer where your IDE or code editor is running on. 
     */
    'local_path' => null,
];
```

To make sure that Ray uses the correct file path for creating the links, you will also need to setup the `remote_path` and `local_path` variables. `remote_path` is the absolute path of your project in the Docker container. `local_path` is the absolute path of your project on the local file system.

**Example:**
In your `docker-compose.yml` you mount the volume as follows:
```
volumes:
  - .:/var/www
```
Then `remote_path` should be `/var/www` and `local_path` should be the absolute path to the directory where your `docker-compose.yml` is located (you can find this by running `pwd` inside that directoy if you are on Linux).

On Linux, you will also need to add an 'extra_hosts' parameter to your container definitions to expose 'host.docker.internal'. Please make sure you are using Docker `20.03` or higher.
```
#docker-compose.yml

services:
  site:
    image: nginx:stable-alpine
    container_name: nginx
    ports:
      - "80:80"
    depends_on:
      - php
      - db
    networks:
      - packt-api
    extra_hosts: # <-- this is required
      - "host.docker.internal:host-gateway" # <-- this is required
```
