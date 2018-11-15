<?php

namespace controllers;

use core\Config;
use core\DBConnector;
use models\PostModel;
use models\UserModel;
use models\SessionModel;
use core\DBDriver;
use core\Core;
use core\Validator;
use core\User;
use core\Exceptions\ModelIncorrectDataException;
use core\Exceptions\ErrorNotFoundException;
use forms\PostAdd;
use forms\PostEdit;
use core\Forms\FormBuilder;

class PostController extends BaseController
{
	const MSG_ERROR = 'Ошибка 404!';
	const REVERSE_SORT_BY_DATE_OPTION = 'ORDER BY `date` DESC';

	public function indexAction()
	{
		$postModel = new PostModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$userModel = new UserModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$sessionModel = new SessionModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$user = new User($userModel, $sessionModel);
		$posts = $postModel->getAll(self::REVERSE_SORT_BY_DATE_OPTION);
		$isAuth = $user->isAuth($this->request);

		if ($isAuth) {
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
		$this->userMenu = $this->build(
			'v_menu-user',
			[
				'isAuth' => $isAuth
			]
		);
	}

	public function postAction()
	{
		$id = $this->request->getGET('id');

		if ($id === null) {
			$this->redirect(Config::ROOT);
		}

		$postModel = new PostModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$post = $postModel->getOne($id);

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
		$userModel = new UserModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$sessionModel = new SessionModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$user = new User($userModel, $sessionModel);
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$form = new PostAdd();
		$formBuilder = new FormBuilder($form);

		if ($this->request->isPOST()) {
			$postModel = new PostModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);

			try {
				$id = $postModel->addOne($form->handleRequest($this->request), true);
				$this->redirect(sprintf('%spost/%s', Config::ROOT, $id));
			} catch (ModelIncorrectDataException $e) {
				$msg = $this->getErrorsAsString($e->getErrors());
				// $form->addErrors($e->getErrors());
			}
		} else {
			$msg = '';
		}

		$this->title .= Config::POST_ADD_SUBTITLE;
		$this->content = $this->build(
			'v_add',
			[
				'msg' => $msg,
				'formBuilder' => $formBuilder
			]
		);
		$this->userMenu = $this->build(
			'v_menu-user',
			[
				'isAuth' => $isAuth
			]
		);
	}

	public function editAction()
	{
		$userModel = new UserModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$sessionModel = new SessionModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$user = new User($userModel, $sessionModel);
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$err404 = false;
		$msg = '';
		$id = $this->request->getGET('id');

		if (!Core::checkId($id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$postModel = new PostModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$post = $postModel->getOne($id);

			if (!$post) {
				$err404 = true;
			} else {
				$form = new PostEdit([
					'title' => $post['title'],
					'content' => $post['content']
				]);
				$formBuilder = new FormBuilder($form);
			}

			if (count($_POST) > 0 && !$err404) {
				$title = trim(htmlspecialchars($this->request->getPOST('title')));
				$content = trim(htmlspecialchars($this->request->getPOST('content')));

				try {
					$postModel->updateOne(
						$form->handleRequest($this->request),
						'id=:id',
						[
							'id' => $id
						],
						true
					);
					$this->redirect(Config::ROOT);
				} catch (ModelIncorrectDataException $e) {
					$msg = $this->getErrorsAsString($e->getErrors());
					// $form->addErrors($e->getErrors());
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
					'formBuilder' => $formBuilder,
					'msg' => $msg
				]
			);
			$this->userMenu = $this->build(
				'v_menu-user',
				[
					'isAuth' => $isAuth
				]
			);
		}
	}

	public function deleteAction()
	{
		$userModel = new UserModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$sessionModel = new SessionModel(
			new DBDriver(DBConnector::getConnect()),
			new Validator()
		);
		$user = new User($userModel, $sessionModel);
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$id = $this->request->getGET('id');

		if ($id === null || $id == '' || !Core::checkId($id)) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 1);
		} else {
			$postModel = new PostModel(
				new DBDriver(DBConnector::getConnect()),
				new Validator()
			);
			$post = $postModel->deleteOne(
				'id=:id',
				[
					'id' => $id
				]
			);

			$this->redirect(Config::ROOT);
		}
	}
}
