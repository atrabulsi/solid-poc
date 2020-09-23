# Domain Driven Design

Domain Driven Design (DDD) is a concept used to produce good quality software and involve the product owners with the technical side and vice versa (involve the developers with the business side).

Domain Driven Design was introduced by Eric Evans in his book [Domain-Driven Design: Tackling Complexity in the Heart of Software Hardcover](https://www.amazon.ca/Domain-Driven-Design-Tackling-Complexity-Software/dp/0321125215)
which goes back to 2003, but people started adopting DDD especially with the move to microservice architecture.

DDD has 3 pillars:
* Strategic Design
* Collaborative Modelling
* Tactical Design 

The first two pillars are more a culture and process than technical details. This workshop is only about the last part, tactical design.

DDD was implemented in this workshop with the Hexagonal Architecture, which makes it easier to build the application and separate the layers.

Before we start, we have to define two main concepts in DDD:
* **A domain** is the business problem we're trying to solve. For example, the ability to apply discounts to products (the discount tool)
* **A bounded context** is the solution to that problem. It includes the classes, files, and databases that implement the use cases of the domain

## Architecture of the service
With hexagonal architecture, we want to divide our service into three layers:
1. Application layer: this is the only layer visible to the "outside world". Controllers, console commands, and other bounded contexts can read and interact with the application layer of a bounded context.
2. Domain layer: this is the core of our bounded context. All business logic, validation, and complexity reside here. This layer is off-limits outside the bounded context.
3. Infrastructure layer: when the bounded context needs to interact with anything outside its boundary, it uses the infrastructure layer to do so. Examples: reading from a file or a database, calling a third party micro-service, calling another bounded context, etc.

## Workshop
[The swagger file can be found here](http://localhost:8083/docs/swagger)

We have two routes: one to create a wave (POST) and one to retrieve a wave (GET). The waves are all stored in Redis for 1 hour.
You will see 4 main layers in this workshop: controllers, application, domain, and infrastructure.

### 1. Controllers
We'll start with the controller: Controllers in DDD ARE NOT part of the bounded context. They are called "adapters" in Hexagonal architecture.
The responsibilities of the controller are:
1. Instantiate the command/query and pass it to the handler so that the handlers are agnostic of how they're called (HTTP request, a cron job, pub/sub, etc) (note: your bounded context should be as framework-agnostic as possible).
2. Do some **very** simple validation. For example, controllers should validate that we're receiving the startDate of the wave (if it's a mandatory field), and it is indeed of type DateTime. However, they SHOULD NOT validate that the start date must be before the end date. This is a job for the domain layer. Basically controllers validate that the swagger contract is respected!
3. Get the response from the handler and format it in a way the client understands.
4. Return the right response code to the client.

That's it! Anything else should not be part of the controller. So basically it's only about input/output according to the contract (swagger file).
The same rule applies to any other adapter, such as a console command (that runes through a cron job for example), or a listener to an event using pub/sub, etc.

### 2. Application
The application layer includes:
* Handlers
* Commands and Queries
* Transformer
* DTO
* Application exceptions

Handlers in DDD are the orchestrators for the use case. We can call them "Application Services", but in DDD we use the "Command/Command Handler" pattern,
where a Command or a Query is basically a special DTO (Data Transfer Object) that includes the parameters the Handler needs to do its job.
If the use case is to retrieve data, the handler would expect a Query to be passed, and if the use case is to write date (or do something) then the handler would expect a Command, but basically Commands and Queries are the same concept.
To read more about the "Command/Command Handler" pattern, you can read: [Implement the microservice application layer using the Web API](https://docs.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/microservice-application-layer-implementation-web-api)

The main two responsibilities of a handler are:
1. Orchestrate the use case: handlers receive a command or query, then they can instantiate a domain object, and they can call the infrastructure layer (to retrieve or persist an object), and finally they return a DTO to the caller (i.e. the controller).
2. Encapsulate the Domain and Infrastructure layers: Handlers must ensure we don't expose any domain or infrastructure concerns to the outside world.
    * They're the **gatekeeper of the bounded context**.
    * They must transform the domain object (through a Transformer) into a dummy DTO before returning it.
    * They must catch all Exceptions thrown by the domain and infrastructure layers, and throw application layer exceptions, if needed.

What handlers **MUST NOT DO** is any business logic or even validation. This is part of the domain layer.

### 3. Domain
The domain layer includes the business logic of our bounded context. It must not depend on any dependency outside the domain layer. For example, if a domain service needs a repository, we simply inject an interface (that is defined and owned by the domain layer)
and using the Dependency Inversion Principle, our domain service will get a concrete class, that implements the interface.

The domain layer includes:
* Entities
* Value objects
* Aggregates
* Domain services
* Domain events
* Domain exceptions
* Factories

In this workshop, we'll only talk about entities, value objects, and domain services.

An entity is an object that has an ID and a life cycle (you create a wave, update it, and then delete it). Two entities are equal if they have the same ID.
Entities (and value objects) are the bread and butter of your domain layer. They include business logic, they include validation, and ideally they should be framework agnostic and should not be able to persist themselves (we should stay away from Active Record ORM).

A good example of an entity would be the wave object.

Value objects on the other hand have the following 4 characteristics:
* They are immutable. Once you create a value object, you cannot change it. They don't have a lifecycle.
* They don't have an ID. Unlike entities, value objects are identified by their attributes. Two value objects are equal if all of their attributes are equal.
* They are always valid. You must not be able to instantiate an invalid Value Object, as an exception is thrown in the constructor if the parameters are invalid (e.g. trying to instantiate an email address object with an invalid email address).
* They include business logic. The bare minimum is validation.

A good example of a value object would be the discount object in a wave, an email address, or a money amount.

Lastly, domain services are used when you need to interact with multiple entities/aggregates/value objects.
The logic to orchestrate the interaction between those objects does not belong to one single entity (because of the Single Responsibility Principle), and we probably don't want to include it in the application handler.
Interacting with multiple domain objects in the application layer is a code smell, because that means the Handler knows about business logic.
So the answer is to use a domain service, that is completely stateless, and it can orchestrate the interaction between different objects.

### Infrastructure


## Concepts we see in DDD

### Application layer
| Concept | Meaning |
| ---- | ---- | 
| Command | A command encapsulates the arguments that a handler needs. Commands are instantiated by controllers and they’re passed to the handler to do its job. |
| Query | A query is similar to a command, only that it’s used for reading instead of writing (or doing). |
| Handler | A handler is the orchestrator of the bounded context. It receives a command/query and it processes it. Handlers are called by the controller, and they return DTO (explained below).|
| Transformer | A transformer transforms domain objects (e.g. entities) to a DTO. They’re instantiated by the controller and injected in the handler, and they return a specific DTO that the controller understands.|
| DTO | DTO = Data Transfer Objects. They’re dummy objects that don’t include any domain logic. They only include attributes with getters. A controller will get a DTO after it executes a handler. For example, the DTO will include wave ID, wave title, start date, and end date. It doesn't include any logic. The only thing it does is provide getters to retrieve those attributes.|
| Exception | The handler will throw two types of exceptions: Input Exception and Application Exception. It will do try/catch to catch all exceptions thrown by the infrastructure and domain layers, and then it throws the application exceptions (the idea is to encapsulate the domain exceptions) |

### Domain layer
| Concept | Meaning |
| ---- | ---- | 
| Entity | An entity is an object that has an identity, is potentially mutable (it has a life cycle), it contains some business logic (validation at least), and it can be persisted. An example about an entity would be the wave in the discount tool (although the wave is more like an aggregate)|
| Value Object | A value object is similar to an entity with two main exceptions: it does not have an ID and it is immutable (once created, it cannot be changed). An example about a value object would be the discount associated with a style in the discount tool.|
| Aggregate | An aggregate is an entity that binds together with other entities and value objects. For example, a wave is an aggregate that includes the wave meta data and the style discounts. An aggregate has one and only one repository to persist it.|
| Domain Service | A domain service is used when dealing with multiple entities/value objects that aren’t coupled together. We may not have a domain service at phase 1 of the discount tool.|
| Domain Event | A domain event is something that happened in the past that is of interest to the domain. Domain events are local in the bounded context. They’re not sent to other bounded contexts or other services. To do that, we use an integration event.|
| Exception | The domain layer can have multiple exceptions thrown in different edge cases. Domain exceptions must be caught by the application layer and an application exception must be thrown, if need be.|

### Infrastructure layer
| Concept | Meaning |
| ---- | ---- | 
| Repository | A repository is used to persist and read entities and aggregates. This is the only place we’re allowed to read/write from the persistence system (database, redis, ElasticSearch, etc). One repository is built for each aggregate/entity that we want to persist/read. Repositories must implement interfaces that are defined in the domain layer.|
| Gateway | A gateway is similar to a repository, only that it’s used when dealing with other microservices/bounded contexts.|
| Exception | Just like a domain exception, an infrastructure exception is thrown in different edge cases related to infrastructure. E.g. database is down, a third party microservice returns a 500, etc. Just like a domain exception, an infrastructure exception must be caught by the application layer (or domain layer) and an application exception is eventually thrown if need be.|
| ACL | ACL = Anti-corruption-layer. An anti corruption layer is used to encapsulate third party services/bounded contexts. It is used to convert the output of the third party service to a domain object that is local to our bounded context. ACLs are specially important when dealing with a legacy system (“big ball of mud”)|

References:
* A good introduction to DDD: [Domain-driven Design Deconstructed by Andrew Cassell](https://www.youtube.com/watch?v=bgJafJI8mp8)
* [DDD, Part 2: DDD Building Blocks](https://dzone.com/articles/ddd-part-ii-ddd-building-blocks)
* [DDD, Hexagonal, Onion, Clean, CQRS, … How I put it all together](https://herbertograca.com/2017/11/16/explicit-architecture-01-ddd-hexagonal-onion-clean-cqrs-how-i-put-it-all-together/)
* [Implement the microservice application layer using the Web API](https://docs.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/microservice-application-layer-implementation-web-api)
