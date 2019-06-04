# Symfony 4 rest starter
Simple project to start developping rest api with symfony 4

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



