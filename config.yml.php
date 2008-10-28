#<?php die(0); ?>
localhost:
	enabled : 1
	encoding: 'utf-8'
	locale  : 'Cs'
	name: 'Blog'
	description: ''
	keywords: ''
	style: 'default'
	upload_dir: 'upload'
	db:
		connector    : 'mysql'
		user         : 'root'
		password     : '1234'
		host         : 'localhost'
		database     : 'nors'
		table_prefix : 'nors4_'
	timezone: 'Europe/Prague'
	debug:
		enabled: 1
		error_reporting: E_ALL & ~E_NOTICE
		display_errors: 1
		time_management: 1
		sql_queries: 0
		included_files: 0
		die_on_error: 1
	log:
		enabled: 1
		file   : 'vyletnik.log'
	cookie:
		expiration: 3600*24*30 #one month
	routes:
		default:
			format: ':module/:event'
			defaults:
				module: 'administration'
				event: '__default'
	administration:
		items_per_page: 30
		content:
			default_subevent: 'post'
		settings:
			default_subevent: 'basic'
