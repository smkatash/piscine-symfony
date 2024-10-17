#!/bin/sh

args=0
for arg in "$@"; do
    args=$((args + 1))
done

bitly="https://bit.ly/"

if [ "$args" -ne 1 ]; then
    echo "Error: invalid amount of arguments."
else
    if echo "$1" | grep -q "$bitly"; then
        url=$(curl -Ls -o /dev/null -w '%{url_effective}' "$1")
        if [ $? -ne 0 ]; then
            echo "Error: failed to fetch the URL."
        fi
        if [ -n "$url" ]; then
            main_domain=$(echo "$url" | cut -d'/' -f1,2,3)
            if [ -n "$main_domain" ]; then
                echo "$main_domain"
            else
                echo "Error: invalid link."
            fi
        else
            echo "Error: invalid link."
        fi
    else
        echo "Error: invalid '$bitly' link."
    fi
fi
