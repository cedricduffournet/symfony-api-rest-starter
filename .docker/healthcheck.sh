#!/bin/bash
while [ "$(docker inspect -f '{{.State.Health.Status}}' symfony-oauth_db)" != "healthy" ];
do
    sleep 1
done