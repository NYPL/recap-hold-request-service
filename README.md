# NYPL ReCAP Hold Request Service

[![Build Status](https://travis-ci.org/NYPL/recap-hold-request-service.svg?branch=master)](https://travis-ci.org/NYPL/recap-hold-request-service)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/recap-hold-request-service/badge.svg?branch=master)](https://coveralls.io/github/NYPL/recap-hold-request-service?branch=master)

This package is intended to be used as a Lambda-based Hold Request Service using the [NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) (using the [Composer](https://getcomposer.org/) autoloader).

## Service Responsibilities

## Requirements

* Node.js >=6.0
* PHP >=7.0 
  * [pdo_pdgsql](http://php.net/manual/en/ref.pdo-pgsql.php)

Homebrew is highly recommended for PHP:
  * `brew install php71`
  * `brew install php71-pdo-pgsql`
  

## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.
   * If you have not already installed `node-lambda` as a global package, run `npm install -g node-lambda`.
3. Setup [configuration](#configuration) files.
   * Copy the `.env.sample` file to `.env`.
   * Copy `config/var_qa.env.sample` to `config/var_qa.env` and `config/var_production.env.sample` to `config/var_production.env`.

## Configuration

Various files are used to configure and deploy the Lambda.

### .env

`.env` is used *locally* for two purposes:

1. By `node-lambda` for deploying to and configuring Lambda in *all* environments. 
   * You should use this file to configure the common settings for the Lambda 
   (e.g. timeout, role, etc.) and include AWS credentials to deploy the Lambda. 
2. To set local environment variables so the Lambda can be run and tested in a local environment.
   These parameters are ultimately set by the [var environment files](#var_environment) when the Lambda is deployed.

### package.json

Configures `npm run` deployment commands for each environment and sets the proper AWS Lambda VPC and
security group.
 
~~~~
"scripts": {
  "deploy-qa": "node-lambda deploy -e qa -f config/deploy_qa.env -S config/event_sources_qa.json -b subnet-f4fe56af -g sg-1d544067",
  "deploy-production": "node-lambda deploy -e production -f config/deploy_production.env -S config/event_sources_production.json -b subnet-f4fe56af -g sg-1d544067"
},
~~~~

### var_app

Configures environment variables common to *all* environments.

### var_*environment*

Configures environment variables specific to each environment.

### event_sources_*environment*

Configures Lambda event sources (triggers) specific to each environment.

## Usage

### Process a Lambda Event

To use `node-lambda` to process the sample API Gateway event in `event.json`, run:

~~~~
node-lambda run
~~~~

### Run as a Web Server

To use the PHP internal web server, run:

~~~~
php -S localhost:8888 -t . index.php
~~~~

You can then make a request to the Lambda: `http://localhost:8888/api/v0.1/bibs`.

### Swagger Documentation Generator

Create a Swagger route to generate Swagger specification documentation:

~~~~
$service->get("/swagger", SwaggerGenerator::class);
~~~~
