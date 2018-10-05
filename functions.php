<?php

function checkTitle($title)
{
	return preg_match("/^[0-9a-zA-Z-]+$/i", $title);
}