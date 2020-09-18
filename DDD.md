# Domain Driven Design Architecture

All the services in folder `src/server/bounded-contexts` follow the Hexagonal Architecture with Domain Driven Design principles.

In DDD, a domain is a problem, and a bounded context is the solution for that problem. In other words, a bounded context is a bunch of classes and files that implement the use cases of the domain.

The key is to have loosely coupled bounded contexts so that if we change one context, the change will be contained in that context.

What we try to avoid is context leak, which happens when the language and the classes of one bounded context "leak" into another.
For example, if bounded context X uses an entity from bounded context Y, that's a context leak because the entity should be accessible only by its own bounded context.

Hexagonal Architecture helps us implement DDD through dividing the bounded context into three layers:
* Application: this is the only layer visible to the "outside world". Controllers, commands, and other bounded contexts can read and use the application layer of a bounded context.
* Application: this is the only layer visible to the "outside world". Controllers, commands, and other bounded contexts can read and use the application layer of a bounded context.
* Domain: this is the core of our bounded context. All business logic, validation, and complexity reside here. This layer is off limit outside the bounded context.
* Infrastructure: when the bounded context needs to interact with anything outside its boundary, it uses the infrastructure layer to do so. Examples: reading from a file or a database, calling a third party micro-service, calling another bounded context, etc

You will find the following concepts in this architecture:

| Concept      | Layer         | Meaning       |
| ------------ | ------------- | ------------- |
| binder | N/A | To bind to the IoC container, so that each bounded context adds its own classes to the IoC container. Example: `locations/LocationsDomainBinder.ts` |
| input | application | Encapsulate all the parameters the application service needs in one object (it's usually an interface). Example: `locations/application/input/ListCountriesInput.ts` |
| service | application | This is the handler that takes care of one use case, it uses transformers to convert all domain or infrastructure concerns to application objects and DTOs. Example: `locations/application/services/ListCountriesService.ts` |
| transformer | application | It transforms the domain and infrastructure concerns to application concerns and DTOs so that we wouldn't have context leak. You can have multiple transformers to return data in different formats (all transformers implement an interface), and the caller (i.e. the controller) chooses the transformer it needs. |
| DTO | application | Data Transfer Objects: They're dummy objects (usually interfaces) to shield the domain and infrastructure objects from leaking to other contexts. Transformers usually spit out DTOs. |
| entities | domain | The bread and butter of the bounded context, it includes business rules and validation. It should not have any dependencies to other layers in the same bounded context or other bounded contexts. |
| gateway | infrastructure | Gateways are used by the bounded context to call other micro-services (or possibly other bounded contexts too). |
| models | infrastructure | The output of the other micro-service or bounded context. Usually it's an interface. Usually we have an anti corruption layer (described below) to transfer those models to domain entities. |
| ACL | infrastructure | Anti Corruption Layer: It's just like the application transformer, except that it transforms from the third party service to our bounded context. It can spit out a domain entity. It's very important specially when dealing with legacy systems that are a big ball of mud. |

There are other concepts in DDD that are not currently implemented, but may someday be:

| Concept      | Layer         | Meaning       |
| ------------ | ------------- | ------------- |
| value objects | domain | A value object is an immutable object that does not have an identity and is always valid. Generally speaking, favor using value objects instead of entities when possible. Example: money amount. |
| aggregates | domain | An aggregate is a cluster of domain objects (entities and value objects) that can be treated as a single unit, usually it has invariants rules. |
| service | domain | When you need to interact with multiple entities, and that logic shouldn't belong to one entity, a domain service can be used. |
| repositories | infrastructure | A repository is used to persist and read entities and aggregates. Each aggregate should have its own repository. |
| event | domain | Something happened that domain experts (and the current bounded context) care about. |
| exceptions | ALL LAYERS | Each layer should have its own exceptions (errors). The application layer catches all exceptions of the domain and infrastructure layers and throws its own exception (to shield domain and infrastructure exceptions from the "outside world"). |

For more information about those concepts, check: https://domainlanguage.com/wp-content/uploads/2016/05/DDD_Reference_2015-03.pdf

### Mocking Gateways
Although this has nothing to do with DDD or Hexagonal Architecture, we'll mention it here since it is used in some bounded contexts.

You can easily mock the response of a third party micro-service by writing your own JSON file of what the response should look like. For example, check the gateway: `shipping/infrastructure/gateways/ShippingGateway.ts`.

To allow a gateway to mock the data you want, follow those instructions:

1. Make sure your gateway extends `bounded-contexts/Gateway.ts`
2. Add the following properties to your gateway:
    ```
    protected mock: boolean = true; // this should move to an environment variable
    protected mockFolder: string = 'shipping/infrastructure/mocks';
    ```
   where `mockFolder` is the folder to store the mocked files. When `mock` is false, a real request will be made to the micro-service. When it's true, we'll read the response from the mocked file.
3. Add the decorator `@GatewayDecorators.MOCKED_FILE()` before the method you want to mock.
4. For that method, create a JSON file in the folder you specified in `mockFolder`, and name the file with the same name as the method.
5. The JSON file will have a structure similar to the following:
```
{
    "status": 200,
    "headers": [],
    "data": [
      The output you want to mock
    ]
}
```

### Dummy Example
To set it in action, a dummy controller has been created `DDDCountriesController` with a dummy route: `http://localhost:8080/en-us/ddd-countries-list`.

Once the CountryService is removed and replaced with the new bounded context, the `CountriesController` will be replaced by `DDDCountriesController`.
