<?php

	namespace Core;

	class Request {

		public function cleanAll() {
			if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
				$_POST = $this->sanitize($_POST);
			}
			elseif($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
				$_GET = $this->sanitize($_GET);
			}
		}
		public function cleanInput($input) {

			$search = array(
		    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		  	);

		    $replace = preg_replace($search, '', $input);
		    $trim = trim($replace);
		    $stripslashes = stripslashes($trim);
		    $output = addslashes($stripslashes);

		    return $output;
		}
		public function sanitize($input) {

		    if(is_array($input)) {
		        foreach($input as $var=>$val) {
		            $output[$var] = $this->sanitize($val);
		        }
		    }
		    else {
		        $output = $this->cleanInput($input);
		    }
		    return $output;
		}
	}
