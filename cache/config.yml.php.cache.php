<?php
$time = 1223493558;
$data = array(
"localhost" => array(
	"enabled" => 1,
	"encoding" => 'utf-8',
	"locale" => 'Cs',
	"name" => 'Výletník admin',
	"description" => '',
	"keywords" => '',
	"style" => 'default',
	"upload_dir" => 'upload',
	"db" => array(
		"connector" => 'mysql',
		"user" => 'norsphpc_norsphp',
		"password" => '',
		"host" => 'localhost',
		"database" => 'norsphpc_norsphp',
		"table_prefix" => 'denik_',
	),
	"timezone" => 'Europe/Prague',
	"debug" => array(
		"enabled" => 1,
		"error_reporting" => E_ALL,
		"display_errors" => 1,
		"time_management" => 1,
		"sql_queries" => 0,
		"included_files" => 0,
		"die_on_error" => 1,
	),
	"log" => array(
		"enabled" => 1,
		"file" => 'vyletnik.log',
	),
	"cookie" => array(
		"expiration" => 3600*24*30,
	),
	"routes" => array(
		"default" => array(
			"format" => ':module/:event',
			"defaults" => array(
				"module" => 'administration',
				"event" => '__default',
			),
		),
	),
	"administration" => array(
		"items_per_page" => 30,
		"content" => array(
			"default_subevent" => 'towns',
		),
	),
),
);
?>