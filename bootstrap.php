<?php

require_once __DIR__ . '/vendor/autoload.php';

use ftwr\blogphp\core\Container;
use ftwr\blogphp\Application;
use ftwr\blogphp\boxes\DBDriverBox;
use ftwr\blogphp\boxes\ModelsFactory;
use ftwr\blogphp\boxes\UserBox;
use ftwr\blogphp\boxes\FormBuilderFactory;

session_start();
error_reporting(E_ALL);

$container = new Container();
$app = new Application($container);

$container->register(new DBDriverBox());
$container->register(new ModelsFactory());
$container->register(new UserBox());
$container->register(new FormBuilderFactory());

return $app;
