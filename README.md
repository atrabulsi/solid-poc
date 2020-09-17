# Workshop Tutorial
This project serves to be a workshop for some principles and best practices.

## Setup
1. Copy the file .env.example to .env
2. Open a terminal and go to the docker folder and type
`docker-compose up -d`
3. Connect to the container by executing `docker exec -it docker_php_1 bash`
4. In the container, run: `composer install`

This will fire up 2 containers, the first one has PHP and nginx combined, and the second one has Redis

By default, the app can be accessed by requesting:
`http://localhost:8083`

To view the swagger file, you can request:
`http://localhost:8083/docs/swagger`

## Workshops
This repo includes a workshop for several points:
1. Single Responsibility Principle

   The single responsibility principle states that each class must have one purpose and one purpose only.
   To illustrate it in this workshop, you'll find three routes:
    * /solid/sr-no-cache/{id}
      * This route will return a hard-coded wave with ID 1 (any other ID will return a 404)
      * It uses a repository to simulate as if it's returning the wave with the ID 1
      * The repository sleeps for 1 second to simulate latency in connecting to a database
      * There is no caching in this route
    * /solid/sr-with-cache-in-service/{id}
      * This route performs the same task, but it uses Redis to cache the wave with ID 1
      * If the requested ID already exists in the cache, return it, if not, request it from the repository, cache it, and return the wave
      * The caching is done in the service `App\Core\SingleResponsibility\Services\WaveServiceWithCaching`
    * /solid/sr-with-cacheable-repo/{id}
      * It performs the same thing the previous route does, but it does so through a repository and a cacheable repository
      * We could have added the caching to the database repository, but in this case the repo would be doing two things: retrieving from the database, and caching in Redis at the same time
      * By having a cacheable repository and a regular repository, each class has its own responsibility, and we achieved the single responsibility principle
    
2. Dependency Inversion Principle
   
   In this exercise, we 'll take the previous example and build on top of it to see how we can use the dependency inversion principle.
   The dependency inversion principle states that we should depend on abstraction, not concrete classes.
   High-level modules should not depend on low-level modules. Both should depend on abstraction, (i.e. interfaces)
