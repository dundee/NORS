#<?php die(0); ?>
table: user
fields:
	-id_user: int
	+name: string
	+id_group: table
	password: password
	fullname: string
	email: string
	active: bool
ids: id_user
