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
* First, setup the containers on your machine following the instructions above.
* Then read the swagger file to know what APIs to use. You may need Postman for some POST requests.
* Read the md file of the section you want to learn about and follow with the code.

## Workshops
This repo includes a workshop for several points:
* [Single Responsibility Principle](docs/workshops/single-responsibility.md)
* [Dependency Inversion Principle](docs/workshops/dependency-inversion.md)
* [Domain Driven Design](docs/workshops/domain-driven-design.md)

## References
[Robert C Martin - aka Uncle Bob: The Single Responsibility Principle](https://www.youtube.com/watch?v=Gt0M_OHKhQE)
(you may want to increase the video speed to 1.25)

[SOLID Principles made easy](https://hackernoon.com/solid-principles-made-easy-67b1246bcdf)
