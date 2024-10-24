#!/bin/bash
docker build --tag=web-pop_restaurant . && \
docker run -p 1337:80 --rm --name=web-pop_restaurant -it web-pop_restaurant