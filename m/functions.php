<?php

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/db.php';
include_once __DIR__ . '/posts.php';
include_once __DIR__ . '/authorization.php';

function checkTitle($title)
{
	return preg_match(ALLOWED_IN_TITLE, $title);
}

function checkController($controller)
{
	return preg_match(ALLOWED_IN_CONTROLLER, $controller);
}

function checkId($id)
{
	return preg_match(ALLOWED_IN_ID, $id);
}

function template($filename, $vars = [])
{
	extract($vars);
	ob_start();
	include "v/$filename.php";

	return ob_get_clean();
}
