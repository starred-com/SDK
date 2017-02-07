#!/bin/bash

# Prerequisites:
# - docker installed

# run all unit tests
docker run -v $(cd .. && pwd):/app --rm phpunit/phpunit Test/Unit

# run system test, THIS SENDS REAL INVITATIONS
#docker run -p 443:443 -v $(cd .. && pwd):/app --rm phpunit/phpunit Test/System
