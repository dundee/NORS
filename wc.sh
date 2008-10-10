#!/bin/bash

find ./ \
        -not -name "jquery.markitup.js" \
        -not -name "jquery.thickbox.js" \
        -not -name "jquery.jtageditor.js" \
        -not -name "jquery.facebox.js" \
        -not -name "jquery.blockUI.js" \
        -not -name "jquery.form.js" \
        -not -name "jquery.js" \
        -not -name "jquery-1.2.2.js" \
        -not -name "jquery-1.3.1.js" \
        -regextype posix-egrep \
        -type f \
        -regex ".*(\.(php|html?|css|js))$" \
        -or -name ".htaccess" \
        | xargs wc
