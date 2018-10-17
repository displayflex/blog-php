<?php

namespace controllers;

use core\DBConnector;
use models\PostsModel;
use models\Auth;
use core\Core;

class PostController extends BaseController
{
	protected $PostsModel;

	public function __construct()
	{
		parent::__construct();
		$this->PostsModel = new PostsModel(DBConnector::getConnect());
	}

	public function indexAction()
	{
		$isAuth = Auth::check();
		
		$posts = $this->PostsModel->getAll();
		
		if ($isAuth) {
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

	public function postAction($id = null)
	{
		if ($id === null) {
			header("Location: " . ROOT);
			exit();
		}

		$post = $this->PostsModel->getOne($id);

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
		$isAuth = Auth::check();

		if (!$isAuth) {
			header("Location: " . ROOT);
			exit();
		}
		
		if (count($_POST) > 0) {
			$title = trim(htmlspecialchars($_POST['title']));
			$content = trim(htmlspecialchars($_POST['content']));
			
			if ($title == '' || $content == '') {
				$msg = 'Заполните все поля.';
			} else {
				$post = $this->PostsModel->addOne($title, $content);
		
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

	public function editAction($id = null)
	{
		$isAuth = Auth::check();

		if (!$isAuth) {
			header("Location: " . ROOT);
			exit();
		}
		
		$err404 = false;
		$msg = '';
		
		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$post = $this->PostsModel->getOne($id);
	
			if (!$post) {
				$err404 = true;
			} else {
				$title = $post['title'];
				$content = $post['content'];
			}
			
			if (count($_POST) > 0) {
				$title = trim(htmlspecialchars($_POST['title']));
				$content = trim(htmlspecialchars($_POST['content']));
		
				if ($title == '' || $content == '') {
					$msg = 'Заполните все поля';
				} else {
					$this->PostsModel->updateOne($id, $title, $content);
		
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

	public function deleteAction($id = null)
	{
		$isAuth = Auth::check();

		if (!$isAuth) {
			header("Location: " . ROOT);
			exit();
		}
		
		$err404 = false;
		
		if ($id === null || $id == '' || !Core::checkId($id)) {
			$err404 = true;
		}
		
		if ($err404) {
			$this->err404Action();
		} else {
			$post = $this->PostsModel->deleteOne($id);
		
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