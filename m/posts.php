<?php

function selectAllPosts()
{
	$sql = sprintf("SELECT * FROM %s ORDER BY `date` DESC", DB_TABLE);
	$query = dbQuery($sql);

	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function selectOnePost($id)
{
	$sql = sprintf("SELECT * FROM %s WHERE `id`=:id", DB_TABLE);
	$query = dbQuery($sql, [
		'id' => $id
	]);

	return $query->fetch(PDO::FETCH_ASSOC);
}

function addPost($title, $content)
{
	$sql = sprintf("INSERT INTO %s(`title`, `content`) VALUES (:t, :c)", DB_TABLE);
	$query = dbQuery($sql, [
		't' => $title,
		'c' => $content
	]);

	$db = dbConnect();

	return $db->lastInsertId();
}

function updatePost($id, $title, $content)
{
	$sql = sprintf("UPDATE %s SET `title`=:t,`content`=:c WHERE `id`=:id", DB_TABLE);
	$query = dbQuery($sql, [
		't' => $title,
		'c' => $content,
		'id' => $id
	]);

	$db = dbConnect();

	return $db->lastInsertId();
}

function deletePost($id)
{
	$sql = sprintf("DELETE FROM %s WHERE `id`=:id", DB_TABLE);
	$query = dbQuery($sql, [
		'id' => $id
	]);

	return true;
}