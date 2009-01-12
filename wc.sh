#!/bin/bash
echo 'content-type: text/plain'
echo ''
find ./ \
        -not -name "jquery.*" \
        -not -name "test.*" \
        -regextype posix-egrep \
        -type f \
        -regex ".*(\.(php|html?|css|js))$" \
        -or -name ".htaccess" \
        | xargs wc
