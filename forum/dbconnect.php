<?php
	$DB = new mysqli('localhost', 'root', 'dtb456', 'databaza');
	if (!$DB) {
		die($DB->connect_error);
	}