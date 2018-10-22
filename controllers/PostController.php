<?php

namespace controllers;

use core\DBConnector;
use models\PostsModel;
use core\DBDriver;
use models\Auth;
use core\Core;

class PostController extends BaseController
{
	const MSG_EMPTY_FIELDS = 'Заполните все поля!';

	public function indexAction()
	{
		$postsModel = new PostsModel(new DBDriver(DBConnector::getConnect()));
		$posts = $postsModel->getAll();
		
		if (Auth::check()) {
			$template = 'v_index_admin';
		} else {
			$template = 'v_index';
		}
		
		$this->title .= INDEX_SUBTITLE;
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
			$this->redirect(ROOT);
		}

		$postsModel = new PostsModel(new DBDriver(DBConnector::getConnect()));
		$post = $postsModel->getOne($id);

		if (!$post) {
			$this->err404Action();
		} else {
			$this->title .= POST_SUBTITLE;
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
			$this->redirect(ROOT);
		}
		
		if ($this->request->isPOST()) {
			$title = trim(htmlspecialchars($this->request->getPOST('title')));
			$content = trim(htmlspecialchars($this->request->getPOST('content')));
			
			if ($title == '' || $content == '') {
				$msg = self::MSG_EMPTY_FIELDS;
			} else {
				$postsModel = new PostsModel(new DBDriver(DBConnector::getConnect()));
				$id = $postsModel->addOne([
					'title' => $title,
					'content' => $content
				]);
		
				$this->redirect(sprintf('%spost/%s', ROOT, $id));
			}
		} else {
			$title = '';
			$content = '';
			$msg = '';
		}

		$this->title .= POST_ADD_SUBTITLE;
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
			$this->redirect(ROOT);
		}
		
		$err404 = false;
		$msg = '';
		$id = $this->request->getGET('id');
		
		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$postsModel = new PostsModel(new DBDriver(DBConnector::getConnect()));
			$post = $postsModel->getOne($id);
	
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
					$msg = self::MSG_EMPTY_FIELDS;
				} else {
					$postsModel->updateOne([
						'title' => $title,
						'content' => $content
					], [
						'id' => $id
					]);
		
					$this->redirect(ROOT);
				}
			}
		}
		
		if ($err404) {
			$this->err404Action();
		} else {
			$this->title .= POST_EDIT_SUBTITLE;
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
			$this->redirect(ROOT);
		}
		
		$err404 = false;
		$id = $this->request->getGET('id');
		
		if ($id === null || $id == '' || !Core::checkId($id)) {
			$err404 = true;
		}
		
		if ($err404) {
			$this->err404Action();
		} else {
			$postsModel = new PostsModel(new DBDriver(DBConnector::getConnect()));
			$post = $postsModel->deleteOne([
				'id' => $id
			]);
		
			$this->redirect(ROOT);
		}
	}

	public function err404Action()
	{
		header("HTTP/1.0 404 Not Found");
		$this->title .= ERR404_SUBTITLE;
		$this->content = $this->build('v_404');
	}
}