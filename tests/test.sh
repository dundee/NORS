#!/bin/sh

rm -f ../cache/* 2>errors

for name in `ls test.*.php`; do
	php $name > ./output/$name.html 2>>errors
done

diff -Naur ref output
