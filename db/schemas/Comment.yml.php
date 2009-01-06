#<?php die(0); ?>
table: comment
fields:
	-id_comment: int
	+id_post: table
	+user: string
	email: string
	www: string
	ip: string
	text: html
	date: datetime
	karma: double
ids: id_comment
