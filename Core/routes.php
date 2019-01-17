<?php

namespace Core;

$routes = [
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
	'/doc/save'
	];

foreach ($routes as $route) {
		$args = explode('/', $route);
		$action = (!isset($args[2])) ? 'index' : $args[2];
		Router::connect($route, ['controller' => $args[1], 'action' => $action]);
}
