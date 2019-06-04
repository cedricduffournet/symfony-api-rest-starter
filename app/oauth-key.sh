#!/bin/bash
OAUTH=$(bin/console custom:oauth-server:create-client --grant-type="refresh_token" --grant-type="password")
CLIENT_ID=$(echo $OAUTH | cut -d ' ' -f1 | cut -d '=' -f2)
SECRET_ID=$(echo $OAUTH | cut -d ' ' -f2 | cut -d '=' -f2)
printf "AUTH_CLIENT_ID=%s\nAUTH_CLIENT_SECRET=%s" $CLIENT_ID $SECRET_ID > .env.local
