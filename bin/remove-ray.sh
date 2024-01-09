#!/bin/bash

# Check if the argument is provided
if [ -z "$1" ]; then
    echo "Usage: $0 <target>"
    exit 1
fi

# Execute the Rector command with the provided target
vendor/bin/rector process "$1" --config vendor/spatie/ray/remove-ray-rector.php
