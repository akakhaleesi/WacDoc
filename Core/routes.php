<?php

namespace Core;

$routes = [
	'/',
	'/app',
	'/app/index',
	'/app/login',
	'/app/register',
	'/app/logout',
	'/app/delete',
	'/app/parameters',
	'/doc',
	'/doc/index',
	'/doc/create',
	'/doc/save',
	'/doc/modify',
	'/doc/upload',
	'/doc/rename',
	'/doc/delete',
	'/doc/download'
	];

foreach ($routes as $route) {
		$args = explode('/', $route);
		$controller = ($args[1] == null) ? 'app' : $args[1];
		$action = (!isset($args[2])) ? 'index' : $args[2];
		Router::connect($route, ['controller' => $controller, 'action' => $action]);
}
