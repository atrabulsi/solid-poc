# Single Responsibility Principle

The single responsibility principle states that each class must have one, and only one, reason to change.
You can see the code of the single responsibility principle here:
```
Controller: SingleResponsibilityController
Bounded Context: App\Core\SingleResponsibility
```
To illustrate it in this workshop, you'll find three routes:
* `/solid/sr-no-cache/{id}`
  * This route returns a hard-coded wave with ID 1 (any other ID will return 404)
  * It uses a repository to simulate as if it's returning the wave with the ID 1
  * The repository sleeps for 1 second to simulate latency in connecting to a database
  * There is no caching
* `/solid/sr-with-cache-in-service/{id}`
  * This route performs the same task, but it uses Redis to cache the wave with ID 1
  * If the requested ID already exists in the cache, return it, if not, request it from the repository, cache it, and return the wave
  * The caching is done in the service `App\Core\SingleResponsibility\Services\WaveServiceWithCaching`
* `/solid/sr-with-cacheable-repo/{id}`
  * It performs the work as the previous route, but it does so through a repository and a cacheable repository
  * We could have added the caching to the database repository, but in this case the repo would be doing two things: retrieving from the database, and caching in Redis at the same time
  * By having a cacheable repository and a regular repository, each class has its own responsibility, and we achieved the single responsibility principle
