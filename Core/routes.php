<?php

namespace Core;

$routes = [
	'/app/index',
	'/app/login',
	'/app/register'
	];

foreach ($routes as $route) {
		$args = explode('/', $route);
		Router::connect($route, ['controller' => $args[1], 'action' => $args[2]]);
}
