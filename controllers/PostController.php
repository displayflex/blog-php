<?php

namespace controllers;

use core\Config;
use core\DBConnector;
use models\PostsModel;
use core\DBDriver;
use models\Auth;
use core\Core;
use core\Validator;
use core\Exceptions\ModelIncorrectDataException;
use core\Exceptions\ErrorNotFoundException;

class PostController extends BaseController
{
	const MSG_ERROR = 'Ошибка 404!';

	public function indexAction()
	{
		$postsModel = new PostsModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$posts = $postsModel->getAll();

		if (Auth::check()) {
			$template = 'v_index_admin';
		} else {
			$template = 'v_index';
		}

		$this->title .= Config::INDEX_SUBTITLE;
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
			$this->redirect(Config::ROOT);
		}

		$postsModel = new PostsModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$post = $postsModel->getOne($id);

		if (!$post) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 1);
		} else {
			$this->title .= Config::POST_SUBTITLE;
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
			$this->redirect(Config::ROOT);
		}

		if ($this->request->isPOST()) {
			$title = trim(htmlspecialchars($this->request->getPOST('title')));
			$content = trim(htmlspecialchars($this->request->getPOST('content')));

			try {
				$postsModel = new PostsModel(
					new DBDriver(DBConnector::getConnect()),
					new Validator()
				);
				$id = $postsModel->addOne([
					'title' => $title,
					'content' => $content
				]);

				$this->redirect(sprintf('%spost/%s', Config::ROOT, $id));
			} catch (ModelIncorrectDataException $e) {
				$errors = array_reduce($e->getErrors(), 'array_merge', array());
				$msg = implode('<br>', $errors);
			}

		} else {
			$title = '';
			$content = '';
			$msg = '';
		}

		$this->title .= Config::POST_ADD_SUBTITLE;
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
			$this->redirect(Config::ROOT);
		}

		$err404 = false;
		$msg = '';
		$id = $this->request->getGET('id');

		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$postsModel = new PostsModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
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

				try {
					$postsModel->updateOne(
						[
							'title' => $title,
							'content' => $content
						],
						'id=:id',
						[
							'id' => $id
						]
					);

					$this->redirect(Config::ROOT);
				} catch (ModelIncorrectDataException $e) {
					$errors = array_reduce($e->getErrors(), 'array_merge', array());
					$msg = implode('<br>', $errors);
				}
			}
		}

		if ($err404) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 1);
		} else {
			$this->title .= Config::POST_EDIT_SUBTITLE;
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
			$this->redirect(Config::ROOT);
		}

		$id = $this->request->getGET('id');

		if ($id === null || $id == '' || !Core::checkId($id)) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 1);
		} else {
			$postsModel = new PostsModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$post = $postsModel->deleteOne(
				'id=:id',
				[
					'id' => $id
				]
			);

			$this->redirect(Config::ROOT);
		}
	}
}
