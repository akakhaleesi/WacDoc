<?php

namespace src\controller;

class DocController extends \Core\Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index(){
    if(isset($_SESSION['user_id'])){
      $files = [];
      $searchFiles = $this->db->prepare("SELECT id,name FROM users_docs WHERE user_id = :user_id");
      $searchFiles->bindParam(':user_id', $_SESSION['user_id']);
      $searchFiles->execute();
      while($datas = $searchFiles->fetch()){
        array_push($files, ['id' => $datas['id'], 'name' => $datas['name']]);
      }
      $this->render('index', ['files' => $files]);
    }
  }

  public function create(){
    if(isset($_SESSION['user_id'])){
      $this->render('create');
    }
  }

  public function save(){
    if(isset($_SESSION['user_id'])){
      if(isset($_POST['doc_content']) && !empty($_POST['doc_content'])){
        $posts = ['user_id' => $_SESSION['user_id'], 'user_name' => $_SESSION['user_name'], 'doc_name' => $_POST['doc_name'], 'doc_content' => $_POST['doc_content']];
        unset($_POST);
        if(empty($posts['doc_name'])){
          $this->render('create', ['doc_content' => $posts['doc_content'], 'errors' => ['name']]);
        }
        else {
          $this->saveFile($posts['user_id'], $posts['user_name'], $posts['doc_name'], $posts['doc_content']);
          $this->render('create', ['doc_name' => $posts['doc_name'], 'doc_content' => $posts['doc_content'], 'errors' => ['success']]);
        }
      }
      else {
        $this->render('create', ['errors' => ['empty']]);
      }
    }
  }

  public function modify(){
    if(isset($_SESSION['user_id'])){
      if(!isset($_POST['doc_id'])){
        $this->render('index');
      }
      else {
        if(isset($_POST['type']) && $_POST['type'] == 'save_modif'){
          if(isset($_POST['doc_content']) && !empty($_POST['doc_content'])){
            $posts = ['user_id' => $_SESSION['user_id'], 'user_name' => $_SESSION['user_name'], 'doc_name' => $_POST['doc_name'], 'doc_content' => $_POST['doc_content']];
            unset($_POST);
            if(empty($posts['doc_name'])){
              $this->render('modify', ['doc_content' => $posts['doc_content'], 'errors' => ['name']]);
            }
            else {
              $updateFile = $this->db->prepare("UPDATE users_docs SET content = :content");
              $updateFile->bindParam(':content', $posts['doc_content']);
              $updateFile->execute();
              $this->render('modify', ['doc_name' => $posts['doc_name'], 'doc_content' => $posts['doc_content'], 'errors' => ['success']]);
            }
          }
          else {
            $this->render('create', ['errors' => ['empty']]);
          }
        }
        else {
          $posts = ['doc_id' => $_POST['doc_id']];
          unset($_POST);

          $searchFile = $this->db->prepare("SELECT id,name,datas FROM users_docs WHERE id = :id");
          $searchFile->bindParam(':id', $posts['doc_id']);
          $searchFile->execute();
          // NEXT TIME MODIFY FILE /!\ 
          if($datas = $searchFile->fetch()){
            $this->render('modify', ['doc_id' => $datas['id'], 'doc_name' => $datas['name'], 'doc_content' => $datas['datas']]);
          }
          else {
            $this->render('index');
          }
        }
      }
    }
  }

  public function saveFile($user_id,$user_name,$doc_name,$doc_content){
    $checkUser = $this->db->prepare("SELECT id FROM users WHERE id = :id");
    $checkUser->bindParam(':id', $user_id);
    $checkUser->execute();
    if($datas = $checkUser->fetch()){
      $doc_name = $this->checkName($doc_name, $user_id);

      $saveFile = $this->db->prepare("INSERT INTO users_docs (user_id, name, datas) VALUES (:user_id, :name, :datas)");
      $saveFile->bindParam(':user_id', $user_id);
      $saveFile->bindParam(':name', $doc_name);
      $saveFile->bindParam(':datas', $doc_content);
      $saveFile->execute();

      $file = fopen($_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$user_name."/".$doc_name, "w");
      fwrite($file, $doc_content);
      fclose($file);
    }
    return;
  }

  public function checkName($doc_name, $user_id, $i=0){
    $doc_name_search = ($i == 0) ? $doc_name.'.mywac' : $doc_name.'.mywac('.$i.')';
    $checkName = $this->db->prepare("SELECT id FROM users_docs WHERE name = :name AND user_id = :user_id");
    $checkName->bindParam(':name', $doc_name_search);
    $checkName->bindParam(':user_id', $user_id);
    $checkName->execute();
    if($datas = $checkName->fetch()){
      $i++;
      return $this->checkName($doc_name, $user_id, $i);
    }
    else {
      $doc_name = ($i == 0) ? $doc_name.'.mywac' : $doc_name.'.mywac('.$i.')';
      return $doc_name;
    }
  }
}

?>
