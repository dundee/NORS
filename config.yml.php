#<?php die(0); ?>
localhost:
	enabled : 1
	encoding: 'utf-8'
	locale  : 'Cs'
	name: 'Výletník admin'
	description: ''
	keywords: ''
	style: 'default'
	upload_dir: 'upload'
	db:
		connector    : 'mysql'
		user         : 'norsphpc_norsphp'
		password     : ''
		host         : 'localhost'
		database     : 'norsphpc_norsphp'
		table_prefix : 'denik_'
	timezone: 'Europe/Prague'
	debug:
		enabled: 1
		error_reporting: E_ALL # & ~E_NOTICE
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
			default_subevent: 'towns'
