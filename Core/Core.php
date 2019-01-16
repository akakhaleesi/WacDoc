<?php

	namespace Core;

	class Core {

		public function run() {
			include 'routes.php';

			$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
      $url = '/'.substr($_SERVER['REQUEST_URI'], strlen($basepath));
			$loadurl = new \Core\Router;

			if($route = $loadurl->get($url)) {
				$controller = '\src\controller\\' . ucfirst($route['controller']) . 'Controller';
				$action = $route['action'];

				$load = new $controller;
				$load->$action();

			} else {
				include '404.php';
			}
		}
	}
