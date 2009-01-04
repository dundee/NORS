#<?php die(0); ?>
table: comment
fields:
	-id_comment: int
	+post: table
	+user: string
	email: string
	www: string
	text: html
	date: datetime
	karma: double
ids: id_comment
