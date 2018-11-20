<?php

namespace core;

class Config
{
	const ROOT = '/lesson7/';

	const LOG_DIR = './logs';

	/**
	 * true - development mode
	 * false - production mode
	 */
	const ENVIROMENT_DEV = true;

	const ALLOWED_IN_TITLE = "/^[0-9a-z-]+$/i";
	const ALLOWED_IN_CONTROLLER = "/^[0-9a-z_]+$/i";
	const ALLOWED_IN_ID = "/^[0-9]+$/i";

	const HOST_ADDRESS = 'localhost';
	const DB_UESRNAME = 'root';
	const DB_PASSWORD = '';
	const DB_NAME = 'ntschool-php';
	const DB_TABLE = 'news';

	const SITE_TITLE = 'MyBlog';
	const INDEX_SUBTITLE = ' | Главная';
	const POST_SUBTITLE = ' | Просмотр статьи';
	const POST_ADD_SUBTITLE = ' | Добавление статьи';
	const POST_EDIT_SUBTITLE = ' | Редактирование статьи';
	const ERR_SUBTITLE = ' | Error';
	const ERR404_SUBTITLE = ' | 404';
	const LOGIN_SUBTITLE = ' | Войти на сайт';
	const REGISTRATION_SUBTITLE = ' | Регистрация';

	const ADMIN_LOGIN = 'admin'; // TODO: delete this?
	const ADMIN_PASSWORD = 'qwerty'; // TODO: delete this?
	const SALT = 's;fa47wyt';
	const SALT_FORMS = '/#@=@/';
}
