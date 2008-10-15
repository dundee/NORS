<?php
$time = 1224096900;
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
		"user" => 'root',
		"password" => '1234',
		"host" => 'localhost',
		"database" => 'nors',
		"table_prefix" => 'nors4_',
	),
	"timezone" => 'Europe/Prague',
	"debug" => array(
		"enabled" => 1,
		"error_reporting" => E_ALL,
		"display_errors" => 1,
		"time_management" => 1,
		"sql_queries" => 0,
		"included_files" => 1,
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