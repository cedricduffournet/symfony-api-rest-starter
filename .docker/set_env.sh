#!/bin/bash
sed -i "s/APP_ENV=.*/APP_ENV=$1/" .env
