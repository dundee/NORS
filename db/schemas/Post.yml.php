#<?php die(0); ?>
table: post
fields:
	-id_post: int
	+name: string
	-id_user: table
	id_cathegory: table
	text: html
	date: datetime
	active: bool
	karma: double
	evaluated: int
	-seen: int
	photo: file
ids: id_post
indexes:
	active_date: active, date
	cathegory: id_cathegory
