# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework, with full [HTTP/2](https://symfony.com/doc/current/weblink.html), HTTP/3 and HTTPS support.

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. API Documentation: https://localhost/api/doc
6. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features
- Get a list of all the unicorns at the farm
- Create a post in my name
- Fix a typo I made in my post
- See all posts that were made
- Delete a post I made
- Purchase a unicorn, which should:
  - Send an email containing all posts that were made of my newly bought unicorn
  - Delete all posts linked to my unicorn

## API endpoints:
GET
/api/posts

POST
/api/posts

PUT
/api/posts/{id}

DELETE
/api/posts/{id}

PATCH
/api/posts/{id}

GET
/api/unicorns

POST
/api/unicorns

PUT
/api/unicorns/{id}

DELETE
/api/unicorns/{id}

PATCH
/api/unicorns/{id}

POST
/api/users/{id}/unicorns/{unicorn_id}/purchase