#!/bin/sh

rm ../cache/*

for name in `ls test.*.php`; do
	php $name > ./output/$name.html 2>/dev/null
done

diff -Naur ref output
