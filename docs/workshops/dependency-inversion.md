# Dependency Inversion Principle
   
The dependency inversion principle states that we should depend on abstractions, not on concretions.
* High-level modules should not depend on low-level modules. Both should depend on abstractions (e.g. interfaces).
* Abstractions should not depend on details. Details (concrete implementations) should depend on abstractions.

A low level module (aka infrastructure class) interacts with the database, reads a file from a disk, stores a value in the cache, etc.
A high level module includes complex business logic. 
The high level modules own and define the contract (the interface), and the low level modules implement it.

In this exercise, we 'll take the example of the single responsibility workshop and build on top of it to see how we can use the dependency inversion principle.
You'll find two routes, one controller, and one service.

```
Controller: DependencyInversionController
Bounded Context: App\Core\DependencyInversion
```

We created two interfaces (or contracts):
* `WaveRepositoryInterface` This interface includes all functions any Wave Repository must implement
* `WaveTransformerInterface` This interface includes all functions any Wave Transformer must implement

As you can see in the service `App\Core\DependencyInversion\Services\WaveService` we injected both interfaces instead of injecting concrete classes. Which is the whole point of dependency inversion.
This way, the high level modules (i.e. the wave service) depends on abstraction (the repository interface, and the transformer interface), instead of depending on concrete classes.

To make things easier, we're using Laravel service provider to bind an interface to a concrete class. This is not a must to implement dependency inversion, but it just makes it easier for us to inject dependencies.
We do so in the service provider `RepositoryProvider` where we specify which repository to associate with the interface by default (using the IoC Container of Laravel).

If you want to use a MySQL repository, a Redis repository, or an ElasticSearch repository, you can change the config file `repository.php`

At the same time, the WaveService accepts an argument that is of type `WaveTransformerInterface`. We created two transformers, one converts the wave object to an array, and the other one converts it to HTML. We could also create a transformer for XML and other formats if needed.

We have two routes, one of them requests the JSON format, and the other one requests the HTML format.
