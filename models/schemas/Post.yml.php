#<?php die(0); ?>
table: post
fields:
	-id_post: int
	+name: string
	-id_user: table
	id_category: table
	text: html
	date: datetime
	active: bool
	-karma: double
	-evaluated: int
	-seen: int
	photo: file
ids: id_post
indexes:
	active_date: active, date
	category: id_category
