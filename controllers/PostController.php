<?php

namespace controllers;

use core\DBConnector;
use models\PostsModel;
use models\Auth;
use core\Core;

class PostController extends BaseController
{
	public function indexAction()
	{
		$PostsModel = new PostsModel(DBConnector::getConnect());
		$posts = $PostsModel->getAll();
		
		if (Auth::check()) {
			$template = 'v_index_admin';
		} else {
			$template = 'v_index';
		}
		
		$this->title .= ' | Главная';
		$this->content = $this->build(
			$template,
			[
				'posts' => $posts
			]
		);
	}

	public function postAction()
	{
		$id = $this->request->getGET('id');

		if ($id === null) {
			header("Location: " . ROOT);
			exit();
		}

		$PostsModel = new PostsModel(DBConnector::getConnect());
		$post = $PostsModel->getOne($id);

		if (!$post) {
			$this->err404Action();
		} else {
			$this->title .= ' | Просмотр статьи';
			$this->content = $this->build(
				'v_post',
				[
					'post' => $post
				]
			);
		}
	}

	public function addAction()
	{
		if (!Auth::check()) {
			header("Location: " . ROOT);
			exit();
		}
		
		if ($this->request->isPOST()) {
			$title = trim(htmlspecialchars($this->request->getPOST('title')));
			$content = trim(htmlspecialchars($this->request->getPOST('content')));
			
			if ($title == '' || $content == '') {
				$msg = 'Заполните все поля.';
			} else {
				$PostsModel = new PostsModel(DBConnector::getConnect());
				$post = $PostsModel->addOne($title, $content);
		
				header("Location: " . ROOT);
				exit();
			}
		} else {
			$title = '';
			$content = '';
			$msg = '';
		}

		$this->title .= ' | Добавление статьи';
		$this->content = $this->build(
			'v_add',
			[
				'title' => $title,
				'content' => $content,
				'msg' => $msg
			]
		);
	}

	public function editAction()
	{
		if (!Auth::check()) {
			header("Location: " . ROOT);
			exit();
		}
		
		$err404 = false;
		$msg = '';
		$id = $this->request->getGET('id');
		
		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$PostsModel = new PostsModel(DBConnector::getConnect());
			$post = $PostsModel->getOne($id);
	
			if (!$post) {
				$err404 = true;
			} else {
				$title = $post['title'];
				$content = $post['content'];
			}
			
			if (count($_POST) > 0) {
				$title = trim(htmlspecialchars($this->request->getPOST('title')));
				$content = trim(htmlspecialchars($this->request->getPOST('content')));
		
				if ($title == '' || $content == '') {
					$msg = 'Заполните все поля';
				} else {
					$PostsModel->updateOne($id, $title, $content);
		
					header("Location: " . ROOT);
					exit();
				}
			}
		}
		
		if ($err404) {
			$this->err404Action();
		} else {
			$this->title .= ' | Редактирование статьи';
			$this->content = $this->build(
				'v_edit',
				[
					'title' => $title,
					'content' => $content,
					'msg' => $msg
				]
			);
		}
	}

	public function deleteAction()
	{
		if (!Auth::check()) {
			header("Location: " . ROOT);
			exit();
		}
		
		$err404 = false;
		$id = $this->request->getGET('id');
		
		if ($id === null || $id == '' || !Core::checkId($id)) {
			$err404 = true;
		}
		
		if ($err404) {
			$this->err404Action();
		} else {
			$PostsModel = new PostsModel(DBConnector::getConnect());
			$post = $PostsModel->deleteOne($id);
		
			header("Location: " . ROOT);
			exit();
		}
	}

	public function err404Action()
	{
		header("HTTP/1.0 404 Not Found");
		$this->title .= ' | 404';
		$this->content = $this->build('v_404');
	}
}