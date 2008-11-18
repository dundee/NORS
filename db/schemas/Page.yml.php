#<?php die(0); ?>
table: page
fields:
	-id_page: int
	-id_language: int
	+name: string
	link: url
	position: int
	text: html
	active: bool
ids: id_page
