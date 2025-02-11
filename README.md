# NYPL ReCAP Hold Request Service

[![Build Status](https://travis-ci.org/NYPL/recap-hold-request-service.svg?branch=master)](https://travis-ci.org/NYPL/recap-hold-request-service)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/recap-hold-request-service/badge.svg?branch=master)](https://coveralls.io/github/NYPL/recap-hold-request-service?branch=master)

This package is intended to be used as a Lambda-based Hold Request Service using the [NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) (using the [Composer](https://getcomposer.org/) autoloader).

## Service Responsibilities

## Requirements

* Docker >= v27

## Installation

1. Clone the repo.
2. Copy the `config/local.env.dist` file to `config/local.env`.
3. Replace values in `config/local.env` with appropriate local, development configuration values.
   * Add values for AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY using keys generated for your IAM user under the 
nypl-digital-dev AWS account.

## Configuration

Common configuration variables are defined in `config/global.env`. Environment-specific configuration is maintained in 
`./config/[environment].env`. Secret values for these environments are stored encrypted using AWS KMS encryption.

## Deployment

Github Actions is enabled for pushes to `origin/development`, `origin/qa`, and `origin/master` (production). The GHA script is in .github/workflows/deploy.yml

### For New Deployments: Grant Permission to API Gateway

When deploying to an environment for the first time (e.g. new QA deployment), you'll need to manually grant the API Gateway permission to execute the newly deployed lambda. To determine the command to run:

1. Log into relevant AWS Console (i.e. nypl-sandbox for development deploy, nypl-digital-dev for QA/Production)
1. Browse to API Gateway > Platform > Resources
1. Browse to `/api/v0.1/recap/hold-requests` POST > Integration Request
1. Click pencil icon ("Edit") to right of "Lambda Function: RecapHoldRequestService-${stageVariables.environment}"
1. Without changing anything, click checkmark icon ("Update")
1. A modal will display titled "Add Permission to Lambda Function and provide a template like the following:

```
aws lambda add-permission \
  --function-name "arn:aws:lambda:us-east-1:946183545209:function:RecapHoldRequestServiceV2-${stageVariables.environment}:current" \
  --source-arn "arn:aws:execute-api:us-east-1:946183545209:ggmsmw0dql/*/POST/api/v0.1/recap/hold-requests" \
  --principal apigateway.amazonaws.com \
  --statement-id 969a61fd-1ae9-47f3-b149-481d5011eefb \
  --action lambda:InvokeFunction  
```

Modify that by replacing "${stageVariables.environment}" with the relevant environment name (e.g. qa). Also add `--region us-east-1` and relevant `--profile`. For example, authorizing the QA deployment looks like this:

```
aws lambda add-permission \
  --function-name "arn:aws:lambda:us-east-1:946183545209:function:RecapHoldRequestServiceV2-qa:current" \
  --source-arn "arn:aws:execute-api:us-east-1:946183545209:ggmsmw0dql/*/POST/api/v0.1/recap/hold-requests" \
  --principal apigateway.amazonaws.com \
  --statement-id 969a61fd-1ae9-47f3-b149-481d5011eefb \
  --action lambda:InvokeFunction \
  --region us-east-1 \
  --profile nypl-digital-dev
```

Run the resulting command in a shell.

## Usage

### Run as a Web Server

We use Docker Compose to provide a local development environment. See docker-compose.yml. The base image is the Bref 
PHP 8.3 FPM Docker Image, which provides a PHP runtime for Lambda. To start the PHP development server, run:

~~~~
docker compose up --build
~~~~

You can then make requests to the Lambda at localhost:8000, (e.g. `http://localhost:8000/api/v0.1/recap/hold-requests`).

The docker-compose.yml file also defines a container for a local Postgres database. The first time it is built, it 
will import the schema at `samples/recap-hold-requests_schema.sql`. The local.env.dist file has the required DB 
variables defined for use with this local db.

### Event Documentation

For more information on the different scenarios that involve RecapHoldRequestService, see:

* [Diagram of NYPL Request architecture](https://docs.google.com/presentation/d/1Tmb53yOUett1TLclwkUWa-14EOG9dujAyMdLzXOdOVc/edit#slide=id.g330b256cdf_0_0)
 * [Detailed description of hold request scenarios with reference to above diagram](https://docs.google.com/document/d/1AMqdUlKn5gV6o98JXfD2SjbIUZm04aGKXtupnmvJUN8/edit#heading=h.br4pvk4ymn9s)

 Also useful:

 * [Flow diagram documenting how item & EDD manifest across NYPL & HTC systems](https://docs.google.com/presentation/d/1G9wCyRswefgu4IvN6pn8ntuSVxJ6eEwYDzsdexTfHS8/edit#slide=id.g2a59ba2c93_0_439)
 * [HTC API wiki](https://htcrecap.atlassian.net/wiki/spaces/RTG/pages/25438542/Request+Item)


### Swagger Documentation

Swagger documentation exists at `/docs/recap-hold-requests`. This endpoint is used by platformdocs.nypl.org to get
schemas and data samples to present as documentation for developers and to help with API testings. Metadata for swagger
is defined in docblocks throughout the codebase, wherever you see the @OA tag.

Note: This codebase was upgraded to use Swagger 3.x and may produce errors on platformdocs until it is also upgraded.

## Git Workflow & Deployment

### Git Workflow

We follow a [feature-branch](https://www.atlassian.com/git/tutorials/comparing-workflows/feature-branch-workflow)
workflow. Our branches, ordered from least-stable to most stable are:

| branch                                                                                                                                                                                | tier        | AWS account      |
|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|:------------|:-----------------|
| `qa` [![Build Status](https://github.com/NYPL/recap-hold-request-service/actions/workflows/deploy.yml/badge.svg?branch=qa)](https://github.com/NYPL-discovery/itemservice/actions)         | qa          | nypl-digital-dev |
| `master` [![Build Status](https://github.com/NYPL-discovery/itemservice/actions/workflows/deploy.yml/badge.svg?branch=master)](https://github.com/NYPL/recap-hold-request-service/actions) | production  | nypl-digital-dev |

Cut feature branches off of, and file PRs into `development`. Merge `development` => `qa` & `qa` => `master`.

### Deployment

*@todo - Add production branch deployment to deploy.yml.*

The application is hosted on AWS Lambda. Upon pushing a change to the `qa` or `production` branches, the Github Actions
script `.github/workflows/deploy.yml` will be run on Github. This script will run Unit tests and PHP Code Sniffer, build
the Docker container as defined by `Dockerfile`, push the image to ECR, and finally, update the Lambda function to pull
in the new image.
