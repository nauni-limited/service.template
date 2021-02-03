#!/bin/bash

result=$(vendor/bin/doctrine-migrations diff --allow-empty-diff)

if [[ $result == "No changes detected in your mapping information." ]]; then
    echo $result
    exit 0
else
    echo "You need to run doctrine-migrations diff and review/commit the resulting migration"
    exit 1
fi
