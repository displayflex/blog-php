<?php

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/db.php';
include_once __DIR__ . '/posts.php';
include_once __DIR__ . '/authorization.php';

function checkTitle($title)
{
	return preg_match(ALLOWED_IN_TITLE, $title);
}

function checkId($id)
{
	return preg_match(ALLOWED_IN_ID, $id);
}

// function showError($msg = null)
// {
// 	static $error = [];
	
// 	if ($msg != null) {
// 		$error[] = $msg;
// 	}

// 	return $error;
// }



