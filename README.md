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

## How to use this repo
* First, setup the containers on your machine following the instructions above
* Then read the swagger file to know what APIs to use. They're all GET requests, you can simply request them using your browser
* Read the next section which includes the workshops in this repo

## Workshops
This repo includes a workshop for several points:
1. Single Responsibility Principle

   The single responsibility principle states that each class must have one, and only one, reason to change.
   ```
    Controller: SingleResponsibilityController
    Bounded Context: App\Core\SingleResponsibility
   ```
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
   
   The dependency inversion principle states that we should depend on abstractions, not on concretions.
   * High-level modules should not depend on low-level modules. Both should depend on abstractions (e.g. interfaces).
   * Abstractions should not depend on details. Details (concrete implementations) should depend on abstractions.

   A low level module (aka infrastructure class) interacts with the database, reads a file from a disk, stores a value in the cache, etc.
   A high level module includes complex business logic. 
   The high level modules own and define the contract (the interface), and the low level modules implement it.

   In this exercise, we 'll take the previous example and build on top of it to see how we can use the dependency inversion principle.
   You'll find two routes, one controller, and one service.
   
  ```
   Controller: DependencyInversionController
   Bounded Context: App\Core\DependencyInversion
  ```

   You can see we created two interfaces:
   * `WaveRepositoryInterface` This interface includes all functions any Wave Repository must implement
   * `WaveTransformerInterface` This interface includes all functions any Wave Transformer should implement
   
   As you can see in the service `App\Core\DependencyInversion\Services\WaveService` we injected both interfaces instead of injecting concrete classes.
   Then in the service provider `RepositoryProvider` we specify which repository to associate with the interface by default (using the IoC Container of Laravel, which is not a must for dependency inversion, but it makes your like easy).
   
   If you want to use a MySQL repository, a Redis repository, or an ElasticSearch repository, you can change the config file `repository.php`
   
   At the same time, the WaveService accepts an argument that is of type WaveTransformerInterface. We created two transformers, one converts the wave object to an array, and the other one converts it to HTML. We could also create a transformer for XML and other formats if needed.
   
   We have two routes, one of them requests the JSON format, and the other one requests the HTML format.

## References
[Robert C Martin - aka Uncle Bob: The Single Responsibility Principle](https://www.youtube.com/watch?v=Gt0M_OHKhQE)
(you may want to increase the video speed to 1.25)

[SOLID Principles made easy](https://hackernoon.com/solid-principles-made-easy-67b1246bcdf)
