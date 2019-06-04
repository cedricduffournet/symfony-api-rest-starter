# Symfony 4 rest starter
Simple project to start developping rest api with symfony 4

[![SymfonyInsight](https://insight.symfony.com/projects/cbf6d535-ea02-4786-9179-49b254bb1242/mini.svg)](https://insight.symfony.com/projects/cbf6d535-ea02-4786-9179-49b254bb1242)
[![StyleCI](https://github.styleci.io/repos/190141271/shield?branch=master)](https://github.styleci.io/repos/190141271)
[![Build Status](https://travis-ci.com/cedricduffournet/symfony-api-rest-starter.svg?token=JpJyZmdDC55Vj3yZZkTq&branch=master)](https://travis-ci.com/cedricduffournet/symfony-api-rest-starter)

## Installation
1) Clone repository

```bash
$ git clone https://github.com/cedricduffournet/symfony-api-rest-starter.git
```

2) Build dev docker image

```bash
make dev
```

3) Create database

```bash
make database-create
```

4) Load fixtures
```bash
make fixtures
```

5) Create oauth api key

```bash
make oauth2-key
```

## API documentation

Navigate to

```bash
http://127.0.0.1:81
```

use login : `superadmin@dev.com` / pwd : `superadminpwd` to get access token

## Testing (behat)

1) Build test docker image

```bash
make test
```

2) Execute behat scenarios

```bash
make behat
```
