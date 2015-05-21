## Infusionsoft OAuth Miniframework Example

This application outlines how to integrate the Infusionsoft API with an off the shelf PHP framework. It demonstrates how to handle the OAuth flow with multiple routes, as well as how to renew a token that we've already stored on a user record in a database. From a fundamental perspective, this example strives to accomplish two things: 
	- an introduction to using and understanding OAuth with the Infusionsoft PHP SDK
	- demonstrate how to decouple authorization and data requests

This example is built in PHP on top of the Laravel Lumen framework. Why do an example like this inside a framework? Many production implementations require integration with an existing code base, and this demonstration illustrates how you can best utilize the features of the PHP SDK to your advantage. The Lumen framework itself is extremely light, fast, and has a number of features built in that make our examples cleaner and easier to understand, including built in composer support and autoloading.

## Getting Started

You can get started with this sample application by following just a few steps:

1. Create a new file named `.env` and duplicate all of the keys in the included `.env.example` file. Then, set all the proper values for `APP_KEY`, all keys prefixed with `DB_` and all keys prefixed with `INFUSIONSOFT_`
2. Note that we've done some setup required by the Lumen framework, all of which happens in `/app/bootstrap/app.php`

## Lumen PHP Framework

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Lumen Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

### License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
