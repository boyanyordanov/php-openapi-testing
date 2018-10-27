# OpenAPI Test Tools for PHP

A collection of tools that can help you test your API based on an OpenAPI description. You decide whether to have it run automatically or use the provided assertions to augment your current test suite. 

## Goals
* To be framework agnostic
* Require minimum configuration
* Augment existing testing setup
* Support making real http requests and functional testing tools

## Readme 
This package aims to help you ensure that you API does what it’s says in its OpenAPI file by giving you tools to enhance your existing PHP Unit tests. 

### The basics 

To be able to achieve its goals this package makes a few assumptions:

* Responses are compatible with PSR-7 
* Your OpenAPI definition has OperationId for all operations, at least a success response schema defined, examples for path parameters and request bodies
* For now you use json or a json based content type

A few things that are not possible at the moment:

* Automatically test query string parameters and headers
* Handle authentication (you should disable auth for your test suite)
* Automatically test all possible response variations (e.g. errors or multiple content types)

Future plans: 

* Expand the provided assertions 
* Give options to swap http clients instead of using guzzle 
* Investigate options to integrate with other testing frameworks

### Extracting test cases 

It comes with a Iterator which wraps your OpenAPI definition an can be used as a PHPUnit data provider. 

*Why would you want to do that?*
In this way you get two main benefits:
* You write the test code once
* You get better detailed error messages about which endpoint failed and what exactly happened 

### Testing endpoints 

The package exposes a trait with PHPUnit assertions which you can include in your base API TestCase and use with or without the rest of the library. 
This is helpful  when you already have a test suite that is testing the API and you only want to add verification against the OpenAPI definition.

*Provided assertions*

* `assertValidContract` - wraps everything else and can be used in conjunction with the data provider to pretty much automate testing the whole API. It executes the requests to the API and checks the responses
* `assertJsonMatchesSchema` - given a json string,  *operationId* and status code,  checks whether the json is valid according to the defined schema 
* `assertOperation` - given an *operationId* and a response object, checks whether the response matches the schema defined for the combination of status code and content type.

### Configuration

By default the trait will look for an `apiClient` attribute and create a guzzle based client in the `setUp` hook if it doesn’t find one. If you want provided your own client you can override that. 

The client should implement `get`, `post`, `put`, `patch` and `delete` methods which return PSR-7 compatible response objects. 

This is useful if you want to skip making real http requests and for example use a framework specific testing utility for that. The package ships with a wrapper for Laravel’s testing helpers and in future will provide something similar for Symfony as well. 
