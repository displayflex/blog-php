<?php

namespace controllers;

use core\Config;
use core\Exceptions\ModelIncorrectDataException;
use core\Exceptions\ErrorNotFoundException;
use forms\PostAdd;
use forms\PostEdit;

class PostController extends BaseController
{
	const MSG_ERROR = 'Ошибка 404!';
	const REVERSE_SORT_BY_DATE_OPTION = 'ORDER BY `date` DESC';

	public function indexAction()
	{
		$postModel = $this->container->fabricate('modelsFactory', 'Post');
		$user = $this->container->getService('user');
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

		$postModel = $this->container->fabricate('modelsFactory', 'Post');
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
		$user = $this->container->getService('user');
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$form = new PostAdd();
		$formBuilder = $this->container->fabricate('formBuilderFactory', $form);

		if ($this->request->isPOST()) {
			$postModel = $this->container->fabricate('modelsFactory', 'Post');

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
		$user = $this->container->getService('user');
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$err404 = false;
		$msg = '';
		$id = $this->request->getGET('id');

		if (!preg_match(Config::ALLOWED_IN_ID, $id) || $id === null || $id == '') {
			$err404 = true;
		} else {
			$postModel = $this->container->fabricate('modelsFactory', 'Post');
			$post = $postModel->getOne($id);

			if (!$post) {
				$err404 = true;
			} else {
				$form = new PostEdit([
					'title' => $post['title'],
					'content' => $post['content']
				]);
				$formBuilder = $this->container->fabricate('formBuilderFactory', $form);
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
		$user = $this->container->getService('user');
		$isAuth = $user->isAuth($this->request);

		if (!$isAuth) {
			$this->redirect(Config::ROOT);
		}

		$id = $this->request->getGET('id');

		if ($id === null || $id == '' || !preg_match(Config::ALLOWED_IN_ID, $id)) {
			throw new ErrorNotFoundException(self::MSG_ERROR, 1);
		} else {
			$postModel = $this->container->fabricate('modelsFactory', 'Post');
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
