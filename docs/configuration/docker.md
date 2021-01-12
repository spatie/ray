---
title: Docker
weight: 6
---

When developing using Docker, the Ray host should point to the internal IP of your Docker host by using 'host.docker.internal'.

```php
// save this in a file called "ray.php"

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


On Linux, you will need to add an 'extra_hosts' parameter to your container definitions to expose 'host.docker.internal'.
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
