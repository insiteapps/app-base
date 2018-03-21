#!/usr/bin/env bash

git add --all
git commit -am "batch code update - app-base"
git pull
git push
echo All done...
