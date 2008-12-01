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
		sql_queries: 
		included_files: 
		die_on_error: 1
	log: 
		enabled: 1
		file: nors.log
	cookie: 
		expiration: 2592000
	routes: 
		default: 
			format: :module/:event
			defaults: 
				module: administration
				event: __default
	administration: 
		items_per_page: 5
		content: 
			default_subevent: post
		users: 
			default_subevent: users
		settings: 
			default_subevent: basic
