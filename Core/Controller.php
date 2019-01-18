<?php

	namespace Core;

	class Controller {

		private $_render = null;
		public $db;

		public function __construct() {
			try {
			    $this->db = new \PDO('mysql:dbname=WacDoc;host=127.0.0.1', 'root', '');
			} catch (PDOException $e) {
			    echo 'Connexion échouée : ' . $e->getMessage();
			}

			$secure = new \Core\Request;
			$secure->cleanAll();
		}

		protected function render($view, $scope = []) {

			extract($scope);
			$controller = (isset($controller) && !empty($controller)) ? $controller : get_class($this);
			$f = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'views', str_replace('Controller', '',basename(str_replace('\\', '/', $controller))), $view]) . '.php';

			if(file_exists($f)) {
				ob_start();
				include($f);
				$view = ob_get_clean();
				ob_start();
				include(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'views', 'index']) . '.php');
				$this->_render = ob_get_clean();
				echo $this->_render;
			}
		}
	}
