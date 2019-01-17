<?php

namespace src\controller;

class AppController extends \Core\Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index(){
    if(isset($_SESSION['user_id'])){
      $this->render('index');
    }
    else {
      $this->render('login');
    }
  }

  public function register(){
    if(!isset($_SESSION['user_id'])){
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
    else {
      $this->render('index');
    }
  }

  public function login(){
    if(!isset($_SESSION['user_id'])){
      if(isset($_POST['login']) && isset($_POST['password'])
      && !empty($_POST['login']) && !empty($_POST['password'])){
        $errors = [];
        $posts = ['login' => $_POST['login'], 'password' => $_POST['password']];
        unset($_POST);

        $password = hash('sha512', $posts['password']);
        $checkData = $this->db->prepare("SELECT id FROM users WHERE login = :login AND password = :password");
        $checkData->bindParam(':login', $posts['login']);
        $checkData->bindParam(':password', $password);
        $checkData->execute();
        if($datas = $checkData->fetch()){
          session_destroy();
          session_start();
          $_SESSION['user_id'] = $datas['id'];
          $this->render('index');
        }
        else {
          $this->render('login', ['errors' => 'connection']);
        }
      }
      else {
        $this->render('login');
      }
    }
    else {
      $this->render('index');
    }
  }

  public function logout(){
    session_destroy();
    $this->render('login');
  }
}

?>
