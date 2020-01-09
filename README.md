![enter image description here](https://travis-ci.org/paooolino/slim4-skeleton-login.svg?branch=master)

# Slim 4 + PHP-DI skeleton application with Login feature

This is my personal version of a skeleton application made by using Slim Php Framework v.4 and PHP-DI as dependency container.
It is the result of a work starded with Slim3 in the past year. The focus is to keep things simple and clear and also be as beginner-friendly as possible. 

## Quick start
1. Install dependencies by running

    composer install

2. Run with docker: 

    docker-compose up -d

3. Point your browser to:

    http://localhost:8080/
    
run tests:

    vendor/bin/phpspec run
    
run phpstan:

     vendor/bin/phpstan analyse app/src --level 5
    

## What this is about

If you are new to Slim Framework, you may ask yourself "Why I need a framework for my web Application"? I'm with you and I like to build tools by myself. Actually I tried to build my own framework and I found out I was just struggling with problems that Slim already addressed and solved in smart ways (I wrote about this topic [here](https://medium.com/@paooolino/why-i-choose-slim-framework-for-my-php-web-development-3b087e6d09fc)). I ended up with a quite good product but Slim was more efficient and tested. So I dived into it and after having used it for a pair of project at work, and a lot of test and study I found the right project skeleton for me.

Slim is completely unopinionated, so you are free to adopt the file structure you like and put things together or divide them based on your needs or feelings. Plus, compared to Slim 3, this new version keeps a lot of things decoupled by the core framework, so you are able to change seamlessly pretty everything: the routing library, the container, or the Psr-7 implementation.

## The container: PHP-DI and new benefits

Before diving into Slim I was completely unaware of what a container was and why I should have used it. Slim 3 relied on the Pimple container, a very simple one. With Slim 4 you have to choose one, so I switched to PHP-DI and learned about a great benefit like the **autowiring** feature. This saved me about writing a lot of repeated code compared to the Slim 3 skeleton I was using for myself before.

Generally speaking, a container is a place where you put things (strings, objects, functions) that you can use later in your controllers. They are called **dependencies**. (The controllers are just the functions that the framework will call in response to a specific route). You just have to pass the items you need to the controllers constructors.

With autowiring I don't need to call the constructor explicitly and passing in the dependencies. The container will do this job automagically by injecting the dependencies by himself, and you have just to tell the constructor about the type of the dependency you want. A definitions file may be useful if a service need some configuration but for regular controllers, models, services, and middlewares I just can skip all the code.

The thing is, that in my code the dependencies needed are in fact just Singleton classes, so I never have more instances of the same type. I am following the principle in which every class is responsible and does just one thing. This high separation of responsibility leads to have a lot of files, but the advantage is very clear: you will have simpler files, and they will be loaded and instantiated only if needed. The disadvantage is that you have to repeat the same structure on each file. The use of copy/paste can help, but I will address this problem later.

## The controllers: not just functions

In this scenario, every controller is a class. I am used to write the code in the magic method __invoke, in which the framework injects the Request and Response objects. That's all you need, and if you need an external service you can just declare it in the constructor.

In this way, each route calls a different class and you can already know where to watch in case you want to debug at a specific web address.

## The models: data providers

I think about a model like a function that provides the data that a controller needs. In my skeleton, the model is a class with a **get**() method to call and obtain the data. The model is responsible about where to find the data (database; external api; in-memory data; filesystem), and send back to the controller.

## Templates: just PHP

This will maybe the next step in my road to enlightenment, but for now I decided to use just plain php views and not a template engine like Twig. I find them simple and powerful, and best suitable for beginners too.

## Services: AppService

Services are the bigger classes you may have in the skeleton. I always start my projects with a "AppService" class that does things strictly related to the current app I am developing. I use it to keep the app state (for example). In the end, the App service is likely injected in almost every controller.

For generic purposes, I usually create an UtilsService class for recurring tasks like formatting values (**formatNumber**() or **formatDate**()), operations on strings (**limitString**()), or HtmlHelper class for returning pieces of HTML code (**getTable**(), **getMenu**(), **getChart**()).

The other service in the skeleton (LoginService) I will describe it below where I talk about the Login Management.

## Middlewares: Init and Auth

My skeleton ships with two middlewares. The "Init" one is called before every route and it essentially makes the router available for both templates and controllers (through the App Service). The main purpose for the router, in this case, is the ability to get the right value for the href attribute and redirects.

In Slim 3 the router was automatically available in the container. In Slim 4 the router is available through the Request object, so I decided to inject it in the App Service through a middleware.

The other middleware, Auth, I will talk about it in the next paragraph.


## Login management

Big sales here, I want to gift you with a simple yet hopefully solid way to manage the login of a user.

The feature is implemented by a LoginService class that holds all the functions used by various components of the framework. Essentially:

1) the LoginPost controller uses the LoginService to write a cookie to keep the user logged.
2) the AuthMiddleware checks if the cookie exists and is valid, otherwise it will redirect to an error page.

The auth cookie may hold informations about the logged user, and it's encrypted using the openssl PHP functions. The encryption is quite secure, otherwise please remember to never include the user password in this cookie. In the skeleton I'm also encrypting the current timestamp in case you want to have a token that is not valid after *n* minutes. To achieve this, you should just change the "is_valid_token" function in the LoginService.

## Tests: PHP-spec

For testing, I found the php-spec library that satisfies me in terms of simplicity and expressivity. I just wanted a way to test if all the pages of my application respond correctly, so I needed a kind of functional tests and a way to simulate the Request object sent by the browser. Plus, php-spec is lighter than PHP-Unit, with which I spent some time in configuration and getting the grasp of what was supposed to be done.

to run the test just launch

    $ vendor/bin/phpspec run

from the root of the skeleton project.


