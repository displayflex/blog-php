<?php

namespace controllers;

use core\DB;
use models\PostsModel;
use models\Auth;
use core\Core;

class PostController extends BaseController
{
	public function indexAction()
	{
		$isAuth = Auth::check();
		
		$PostsModel = new PostsModel(DB::connect());
		$posts = $PostsModel->getAll();
		
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

		$PostsModel = new PostsModel(DB::connect());
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
				$PostsModel = new PostsModel(DB::connect());
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

	public function editAction($id = null)
	{
		$isAuth = Auth::check();

		if (!$isAuth) {
			header("Location: " . ROOT);
			exit();
		}
		
		$err404 = false;
		
		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$PostsModel = new PostsModel(DB::connect());
			$post = $PostsModel->getOne($id);
	
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
			$PostsModel = new PostsModel(DB::connect());
			$post = $PostsModel->deleteOne($id);
		
			header("Location: " . ROOT);
			exit();
		}
	}

	public function err404Action()
	{
		$this->title .= ' | 404';
		$this->content = $this->build('v_404');
	}
}