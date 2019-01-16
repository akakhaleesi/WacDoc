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
    if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['password2'])
    && !empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['password2'])){

      $errors = [];
      $posts = ['login' => $_POST['login'], 'password' => $_POST['password'], 'password2' => $_POST['password2']];
      unset($_POST);

      foreach($posts as $data => $val){
        if($data !== 'password' && $data !== 'password2'){
          $checkValidity = $this->db->prepare("SELECT id FROM users WHERE ".$data." = :".$data);
          $checkValidity->bindParam(':'.$data, $val);
          $checkValidity->execute();
          if($datas = $checkValidity->fetch()){
            array_push($errors, $data);
          }
        }
      }
      if($posts['password'] !== $posts['password2']){
        array_push($errors, 'password');
      }
      if(count($errors) > 0){
        $this->render('register', ['errors' => $errors]);
      }
      else {
        $password = hash('sha512', $posts['password']);
        $register = $this->db->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
        $register->bindParam(':login', $posts['login']);
        $register->bindParam(':password', $password);
        $register->execute();
        $this->render('login', ['success' => true]);
      }
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
