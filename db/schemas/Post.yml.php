#<?php die(0); ?>
table: post
fields:
	-id_post: int
	+name: string
	url: url
	cathegory: table
	text: html
	date: datetime
	active: bool
	karma: double
	-seen: int
	photo: file
ids: id_post