# NYPL ReCAP Hold Request Service

[![Build Status](https://travis-ci.org/NYPL/recap-hold-request-service.svg?branch=master)](https://travis-ci.org/NYPL/recap-hold-request-service)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/recap-hold-request-service/badge.svg?branch=master)](https://coveralls.io/github/NYPL/recap-hold-request-service?branch=master)

This package is intended to be used as a Lambda-based Hold Request Service using the [NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) (using the [Composer](https://getcomposer.org/) autoloader).

## Service Responsibilities

## Requirements

* Node.js >=6.0
* PHP >=7.1

Homebrew is highly recommended for PHP:

```
brew install php@7.1
```

### Troublshooting PHP

If `php -v` reports a version < 7.1 (e.g. 5.x), you may need to correct your path:

```
echo 'export PATH="/usr/local/opt/php@7.1/sbin:$PATH"' >> ~/.profile
echo 'export PATH="/usr/local/opt/php@7.1/bin:$PATH"' >> ~/.profile
source ~/.profile
```

If `php -v` complains about not finding pdo, you may need to manually remove a deprecated pdo conf, like this one:

`rm /usr/local/etc/php/7.1/conf.d/ext-pdo_pgsql.ini`

## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.
   * If you have not already installed `node-lambda` as a global package, run `npm install -g node-lambda`.

## Configuration

Common configuration is maintained in `./.env`. Deployment-specific configuration is maintained in `./config/var_[environment].env`. Event sources (this app has none) are configured in `./config/event_sources_[environment].json`.

## Deployment

Travis CD is enabled for pushes to `origin/development`, `origin/qa`, and `origin/master` (production).

If you need to manually deploy local code, you can use:

```
npm run deploy-[environment]
```

### For New Deployments: Grant Permission to API Gateway

When deploying to an environment for the first time (e.g. new QA deployment), you'll need to manually grant the API Gateway permission to execute the newly deployed lambda. To determine the command to run:

1. Log into relevant AWS Console (i.e. nypl-sandbox for development deploy, nypl-digital-dev for QA/Production)
1. Browse to API Gateway > Platform > Resources
1. Browse to `/api/v0.1/recap/hold-requests` POST > Integration Request
1. Click pencil icon ("Edit") to right of "Lambda Function: RecapHoldRequestService-${stageVariables.environment}"
1. Without changing anything, click checkmark icon ("Update")
1. A modal will display titled "Add Permission to Lambda Function and provide a template like the following:

```
aws lambda add-permission   --function-name "arn:aws:lambda:us-east-1:946183545209:function:RecapHoldRequestService-${stageVariables.environment}"   --source-arn "arn:aws:execute-api:us-east-1:946183545209:ggmsmw0dql/*/POST/api/v0.1/recap/hold-requests"   --principal apigateway.amazonaws.com   --statement-id 969a61fd-1ae9-47f3-b149-481d5011eefb   --action lambda:InvokeFunction
```

Modify that by replacing "${stageVariables.environment}" with the relevant environment name (e.g. qa). Also add `--region us-east-1` and relevant `--profile`. For example, authorizing the QA deployment looks like this:

```
aws lambda add-permission   --function-name "arn:aws:lambda:us-east-1:946183545209:function:RecapHoldRequestService-qa"   --source-arn "arn:aws:execute-api:us-east-1:946183545209:ggmsmw0dql/*/POST/api/v0.1/recap/hold-requests"   --principal apigateway.amazonaws.com   --statement-id 969a61fd-1ae9-47f3-b149-481d5011eefb   --action lambda:InvokeFunction --profile nypl-digital-dev --region us-east-1
```

Run the resulting command in a shell.

## Usage

### Process a Lambda Event

A sample `event.json` can be used to test the lambda. (To modify the sample post, edit `./sample-post.json` and run `node scripts/update-event-json`.)

To use `node-lambda` to process the sample API Gateway event in `event.json`, run:

~~~~
node-lambda run
~~~~

### Run as a Web Server

To use the PHP internal web server, run:

~~~~
php -S localhost:8888 -t . index.php
~~~~

You can then make a request to the Lambda: `http://localhost:8888/api/v0.1/recap/hold-requests`.

For running locally, you will have to create a database that links to the local server. Use the schema in samples/recap-hold-requests_schema.sql to execute database dump. Replace the [username] placeholder in the file with your user name. After that, set the right configurations of DB_CONNECT_STRING, DB_PASSWORD, DB_USERNAME in config/var_app.

### Event Documentation

For more information on the different scenarios that involve RecapHoldRequestService, see:

* [Diagram of NYPL Request architecture](https://docs.google.com/presentation/d/1Tmb53yOUett1TLclwkUWa-14EOG9dujAyMdLzXOdOVc/edit#slide=id.g330b256cdf_0_0)
 * [Detailed description of hold request scenarios with reference to above diagram](https://docs.google.com/document/d/1AMqdUlKn5gV6o98JXfD2SjbIUZm04aGKXtupnmvJUN8/edit#heading=h.br4pvk4ymn9s)

 Also useful:

 * [Flow diagram documenting how item & EDD manifest across NYPL & HTC systems](https://docs.google.com/presentation/d/1G9wCyRswefgu4IvN6pn8ntuSVxJ6eEwYDzsdexTfHS8/edit#slide=id.g2a59ba2c93_0_439)
 * [HTC API wiki](https://htcrecap.atlassian.net/wiki/spaces/RTG/pages/25438542/Request+Item)

### Swagger Documentation Generator

Create a Swagger route to generate Swagger specification documentation:

~~~~
$service->get("/swagger", SwaggerGenerator::class);
~~~~
