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
    if(isset($_SESSION['user_id'])){
      $this->render('index');
    }
    else {
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
          mkdir($_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$posts['login']."/", 0777);
          $this->render('login', ['success' => true]);
        }
      }
      else {
        $this->render('register');
      }
    }
  }

  public function login(){
    if(isset($_SESSION['user_id'])){
      $this->render('index');
    }
    else {
      if(isset($_POST['login']) && isset($_POST['password'])
      && !empty($_POST['login']) && !empty($_POST['password'])){
        $errors = [];
        $posts = ['login' => $_POST['login'], 'password' => $_POST['password']];
        unset($_POST);

        $password = hash('sha512', $posts['password']);
        $checkData = $this->db->prepare("SELECT id, login FROM users WHERE login = :login AND password = :password");
        $checkData->bindParam(':login', $posts['login']);
        $checkData->bindParam(':password', $password);
        $checkData->execute();
        if($datas = $checkData->fetch()){
          session_destroy();
          session_start();
          $_SESSION['user_id'] = $datas['id'];
          $_SESSION['user_name'] = $datas['login'];
          $this->render('index');
        }
        else {
          $this->render('login', ['errors' => ['connection']]);
        }
      }
      else {
        $this->render('login');
      }
    }
  }

  public function logout(){
    if(!isset($_SESSION['user_id'])){
      $this->render('login');
    }
    else {
      session_destroy();
      $this->render('login');
    }
  }

  public function delete(){
    if(!isset($_SESSION['user_id'])){
      $this->render('login');
    }
    else {
      if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
        $posts = ['user_id' => $_POST['user_id'], 'user_name' => $_SESSION['user_name']];
        unset($_POST);
        $delete = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $delete->bindParam(':id', $posts['user_id']);
        $delete->execute();
        $this->rmdir($_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$posts['user_name']."/");
        session_destroy();
        $this->render('login', ['errors' => ['deleted']]);
      }
      else {
        $this->render('index');
      }
    }
  }

  public function parameters(){
    if(isset($_SESSION['user_id'])){
      $this->render('parameters');
    }
    else {
      $this->render('login');
    }
  }

  public function rmdir($dirname) {
    if(is_dir($dirname)) $dir_handle = opendir($dirname);
    if(!$dir_handle) return false;
    while($file = readdir($dir_handle)) {
      if($file != "." && $file != "..") {
        if(!is_dir($dirname."/".$file)) unlink($dirname."/".$file);
        else delete_directory($dirname.'/'.$file);
      }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
  }
}

?>
