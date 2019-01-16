<?php

namespace src\controller;

class AppController extends \Core\Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index(){
    $data['title'] = 'index';
    $this->render('index', $data);
  }

  public function register(){
    if(isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])){
      var_dump($_POST);
    }
    else {
      $this->render('register');
    }
  }

  public function login(){
    if(isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])){
      var_dump($_POST);
    }
    else {
      $this->render('login');
    }
  }
}

?>
