#<?php die(0); ?>
enabled: 1
encoding: utf-8
locale: Cs
name: Nors
description:
keywords:
style: default
upload_dir: upload
timezone: Europe/Prague
db:
	connector: mysql
	user:
	password:
	host: localhost
	database: nors
	table_prefix: nors4_
	version: 4.3.7
debug:
	enabled: 0
	error_reporting: 6141
	display_errors: 1
	time_management: 1
	sql_queries: 0
	included_files: 0
	die_on_error: 1
log:
	enabled: 1
	file: nors.log
cookie:
	expiration: 2592000
routes:
	home:
		format:
		defaults:
			controller: post
			action: __default
			p: 0
	category:
		format: category/@category
		defaults:
			controller: category
			action: __default
			p: 0
	post:
		format: post/@post
		defaults:
			controller: post
			action: __default
	page:
		format: page/@page
		defaults:
			controller: page
			action: __default
	default:
		format: @controller/@action
		defaults:
			controller: post
			action: __default
front_end:
	perex_length: 100
	posts_per_page: 5
administration:
	items_per_page: 10
	content:
		default_event: post
	users:
		default_event: user
	settings:
		default_event: basic
