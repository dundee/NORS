#<?php die(0); ?>
localhost: 
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
		user: root
		password: 1234
		host: localhost
		database: norsphpc_milde
		table_prefix: nors4_
	debug: 
		enabled: 1
		error_reporting: 6143
		display_errors: 1
		time_management: 1
		sql_queries: 0
		included_files: 
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
				module: post
				event: __default
		cathegory: 
			format: cathegory/:cathegory
			defaults: 
				module: cathegory
				event: __default
		post: 
			format: post/:post
			defaults: 
				module: post
				event: __default
		page: 
			format: page/:page
			defaults: 
				module: page
				event: __default
		default: 
			format: :module/:event
			defaults: 
				module: post
				event: __default
	front_end: 
		perex_length: 30
		posts_per_page: 5
	administration: 
		items_per_page: 10
		content: 
			default_subevent: post
		users: 
			default_subevent: user
		settings: 
			default_subevent: basic
nors_cz: 
	enabled: 1
	encoding: utf-8
	locale: Cs
	name: Nors
	description: 
	keywords: 
	style: default
	upload_dir: upload
	db: 
		connector: mysql
		user: root
		password: 1234
		host: localhost
		database: nors
		table_prefix: nors4_
	timezone: Europe/Prague
	debug: 
		enabled: 1
		error_reporting: 6143
		display_errors: 1
		time_management: 1
		sql_queries: 1
		included_files: 
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
				module: post
				event: __default
		cathegory: 
			format: cathegory/:cathegory
			defaults: 
				module: cathegory
				event: __default
		post: 
			format: post/:post
			defaults: 
				module: post
				event: __default
		page: 
			format: page/:page
			defaults: 
				module: page
				event: __default
		default: 
			format: :module/:event
			defaults: 
				module: post
				event: __default
	front_end: 
		perex_length: 30
		posts_per_page: 5
	administration: 
		items_per_page: 10
		content: 
			default_subevent: post
		users: 
			default_subevent: user
		settings: 
			default_subevent: basic
dundee_cz: 
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
		user: root
		password: 1234
		host: localhost
		database: norsphpc_milde
		table_prefix: nors4_
	debug: 
		enabled: 1
		error_reporting: 6143
		display_errors: 1
		time_management: 1
		sql_queries: 0
		included_files: 
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
				module: post
				event: __default
		cathegory: 
			format: cathegory/:cathegory
			defaults: 
				module: cathegory
				event: __default
		post: 
			format: post/:post
			defaults: 
				module: post
				event: __default
		page: 
			format: page/:page
			defaults: 
				module: page
				event: __default
		default: 
			format: :module/:event
			defaults: 
				module: post
				event: __default
	front_end: 
		perex_length: 100
		posts_per_page: 5
	administration: 
		items_per_page: 10
		content: 
			default_subevent: post
		users: 
			default_subevent: user
		settings: 
			default_subevent: basic
