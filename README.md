[![Latest Stable Version](https://poser.pugx.org/feikwok/laravel-redis-ui/v/stable)](https://packagist.org/packages/feikwok/laravel-redis-ui)
[![Total Downloads](https://poser.pugx.org/feikwok/laravel-redis-ui/downloads)](https://packagist.org/packages/feikwok/laravel-redis-ui)
[![License](https://poser.pugx.org/feikwok/laravel-redis-ui/license)](https://packagist.org/packages/feikwok/laravel-redis-ui)

# laravel-redis-ui
Redis UI for Laravel

When we are dealing with redis database, we often need to install a different application for a user interface to view or maybe manipulate the data, say for testing purposes.

This package will provide an interface for user to management the redis database with their Laravel application. The features included in this package are:

- Select a database connection on your Redis server
- Search/Filter by:
  - Key
  - Content/ Value
- Reset filter
- Pagination on results
- Create a new record (key + content)
- Edit an existing record (content only)
- Delete an existing record

# Requirement
- PHP 7.0+
- Redis Server, which configured in you laravel application
- Laravel 5.3+
- Vuejs 2.0+
- Vuex 2.o+

# Installation

**First we need to install Vuex**

Vuejs CDN has been included to the default template. But Vuex are not. So you might want to install Vuex first on your machine.

```
npm install vuex --save
```

Or otherwise, you can modify the default template to include the CDN for Vuex.

```
<script src="/path/to/vuex.js"></script>
or
<script src="/path/to/vuex.js@2.0.0"></script>
```

**Add and install the package with the follow command to your laravel application**
```
composer require feikwok/laravel-redis-ui
```
**Update the config/app.php file to include the following line in 'providers' section**
```
Feikwok\RedisUI\LaravelRedisUIServiceProvider::class,
```
**At last make sure you publish the resources to your public folder**
```
php artisan vendor:publish
```
Thats should be all. Happy coding!

# On Going Developments
- ~~Organise the vuejs structure with Vuex and components, instead of put everything in one page~~
- ~~Adding number of results/ offset display per-page options to page~~
- Research a way to properly display JSON data
- ~~Auto load the available redis databases/ connections from Laravel config.~~
- ~~Refactoring the boots and register in Package Service Provider~~

# Sandbox/ Demo
TBA
