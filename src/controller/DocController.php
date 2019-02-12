<?php

namespace src\controller;

class DocController extends \Core\Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index(){
    if(isset($_SESSION['user_id'])){
      $files = [];
      $searchFiles = $this->db->prepare("SELECT id,name,extension FROM users_docs WHERE user_id = :user_id");
      $searchFiles->bindParam(':user_id', $_SESSION['user_id']);
      $searchFiles->execute();
      while($datas = $searchFiles->fetch()){
        array_push($files, ['id' => $datas['id'], 'name' => $datas['name'], 'extension' => $datas['extension']]);
      }
      $this->render('index', ['files' => $files]);
    }
    else {
      $this->render('login', ['controller' => 'AppController']);
    }
  }

  public function create(){
    if(isset($_SESSION['user_id'])){
      $this->render('create');
    }
    else {
      $this->render('login', ['controller' => 'AppController']);
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
          $extensionFile = '.mywac';
          $this->saveFile($posts['user_id'], $posts['user_name'], $posts['doc_name'], $posts['doc_content'], $extensionFile);
          $this->render('create', ['doc_name' => $posts['doc_name'], 'doc_content' => $posts['doc_content'], 'errors' => ['success']]);
        }
      }
      else {
        $this->render('create', ['errors' => ['empty']]);
      }
    }
    else {
      $this->render('login', ['controller' => 'AppController']);
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
            $posts = ['user_id' => $_SESSION['user_id'], 'user_name' => $_SESSION['user_name'], 'doc_id' => $_POST['doc_id'], 'doc_name' => $_POST['doc_name'], 'doc_content' => $_POST['doc_content']];
            unset($_POST);
            if(empty($posts['doc_name'])){
              $this->render('modify', ['doc_content' => $posts['doc_content'], 'errors' => ['name']]);
            }
            else {
              $file_ext = $this->db->prepare("SELECT extension FROM users_docs WHERE id = :id");
              $file_ext->bindParam(':id', $posts['doc_id']);
              $file_ext->execute();
              if($datas = $file_ext->fetch()){
                $updateFile = $this->db->prepare("UPDATE users_docs SET content = :content WHERE id = :id");
                $updateFile->bindParam(':content', $posts['doc_content']);
                $updateFile->bindParam(':id', $posts['doc_id']);
                $updateFile->execute();

                $filename = $_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$posts['user_name']."/".$posts['doc_name'].$datas['extension'];
                $file = fopen($filename, "w");
                fwrite($file, $posts['doc_content']);
                fclose($file);

                $this->render('modify', ['doc_name' => $posts['doc_name'], 'doc_content' => $posts['doc_content'], 'errors' => ['success']]);
              }
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

          if($datas = $searchFile->fetch()){
            $this->render('modify', ['doc_id' => $datas['id'], 'doc_name' => $datas['name'], 'doc_content' => $datas['datas']]);
          }
          else {
            $this->render('index');
          }
        }
      }
    }
    else {
      $this->render('login', ['controller' => 'AppController']);
    }
  }

  public function upload(){
    if(isset($_SESSION['user_id'])){
      if(isset($_FILES['file']) && !empty($_FILES['file'])){
        $fileContent = file_get_contents($_FILES['file']['tmp_name']);
        $filename = explode('.', $_FILES['file']['name']);
        $filename = $filename[0];
        $fileExtension = explode('/', $_FILES['file']['type']);
        $fileExtension = '.'.$fileExtension[1];

        $this->saveFile($_SESSION['user_id'], $_SESSION['user_name'], $filename, $fileContent, $fileExtension);
        $this->render('index', ['errors' => ['success']]);
      }
      else {
        $this->render('index');
      }
    }
    else {
      $this->render('login', ['controller' => 'AppController']);
    }
  }

  public function rename(){
    if(isset($_SESSION['user_id'])){
      if(isset($_POST['doc_name']) && !empty($_POST['doc_name'])){
        $posts = ['doc_id' => $_POST['doc_id'], 'doc_name' => $_POST['doc_name'], 'doc_ext' => $_POST['doc_ext']];
        unset($_POST);
        $doc_data = $this->checkName($posts['doc_name'], $_SESSION['user_id'], $posts['doc_ext']);

        $file = $this->db->prepare("SELECT name,extension FROM users_docs WHERE id = :id");
        $file->bindParam(':id', $posts['doc_id']);
        $file->execute();

        if($datas = $file->fetch()){
          $filepath = $_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$_SESSION['user_name']."/".$datas['name'].$datas['extension'];
          $newfilepath = $_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$_SESSION['user_name']."/".$doc_data['name'].$doc_data['extension'];
          rename($filepath, $newfilepath);
        }
        $updateName = $this->db->prepare("UPDATE users_docs SET name = :name WHERE id = :id");
        $updateName->bindParam(':name', $posts['doc_name']);
        $updateName->bindParam(':id', $posts['doc_id']);
        $updateName->execute();
        $updateExt = $this->db->prepare("UPDATE users_docs SET extension = :extension WHERE id = :id");
        $updateExt->bindParam(':extension', $posts['doc_ext']);
        $updateExt->bindParam(':id', $posts['doc_id']);
        $updateExt->execute();

        $this->render('index', ['errors' => ['success']]);
      }
      else {
        $this->render('index');
      }
    }
    else {
      $this->render('login', ['controller' => 'appController']);
    }
  }

  public function delete() {
    if(isset($_SESSION['user_id'])){
      if(!isset($_POST['doc_id'])){
        $this->render('index');
      }
      else {
        $posts = ['doc_id' => $_POST['doc_id']];
        unset($_POST);

        $file = $this->db->prepare("SELECT name,extension FROM users_docs WHERE id = :id");
        $file->bindParam(':id', $posts['doc_id']);
        $file->execute();
        if($datas = $file->fetch()){
          $filepath = $_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$_SESSION['user_name']."/".$datas['name'].$datas['extension'];
          if(!is_dir($filepath)) unlink($filepath);

          $delete = $this->db->prepare("DELETE FROM users_docs WHERE id = :id");
          $delete->bindParam(':id', $posts['doc_id']);
          $delete->execute();

          $this->render('index', ['errors' => ['deleted']]);
        }
      }
    }
    else {
      $this->render('login', ['controller' => 'appController']);
    }
  }

  public function download(){
    if(isset($_SESSION['user_id'])){
      if(!isset($_POST['doc_id'])){
        $this->render('index');
      }
      else {
        $posts = ['doc_id' => $_POST['doc_id']];
        unset($_POST);

        $file = $this->db->prepare("SELECT name,extension FROM users_docs WHERE id = :id");
        $file->bindParam(':id', $posts['doc_id']);
        $file->execute();
        if($datas = $file->fetch()){
          $filepath = $_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$_SESSION['user_name']."/".$datas['name'].$datas['extension'];
          if(!is_dir($filepath)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            exit;

            $this->render('index', ['errors' => ['']]);
          }
        }
      }
    }
    else {
      $this->render('login', ['controller' => 'appController']);
    }
  }

  public function saveFile($user_id,$user_name,$doc_name,$doc_content,$doc_ext){
    $checkUser = $this->db->prepare("SELECT id FROM users WHERE id = :id");
    $checkUser->bindParam(':id', $user_id);
    $checkUser->execute();
    if($datas = $checkUser->fetch()){
      $doc_data = $this->checkName($doc_name, $user_id, $doc_ext);

      $saveFile = $this->db->prepare("INSERT INTO users_docs (user_id, name, extension, datas) VALUES (:user_id, :name, :extension, :datas)");
      $saveFile->bindParam(':user_id', $user_id);
      $saveFile->bindParam(':name', $doc_data['name']);
      $saveFile->bindParam(':extension', $doc_data['extension']);
      $saveFile->bindParam(':datas', $doc_content);
      $saveFile->execute();

      $file = fopen($_SERVER['DOCUMENT_ROOT'].BASE_URI."/datas/".$user_name."/".$doc_data['name'].$doc_data['extension'], "w");
      fwrite($file, $doc_content);
      fclose($file);
    }
    return;
  }

  public function checkName($doc_name, $user_id, $doc_ext, $i=0){
    $extension = ($i == 0) ? $doc_ext : $doc_ext.'('.$i.')';
    $checkName = $this->db->prepare("SELECT id FROM users_docs WHERE name = :name AND user_id = :user_id AND extension = :extension");
    $checkName->bindParam(':name', $doc_name);
    $checkName->bindParam(':user_id', $user_id);
    $checkName->bindParam(':extension', $extension);
    $checkName->execute();
    if($datas = $checkName->fetch()){
      $i++;
      return $this->checkName($doc_name, $user_id, $doc_ext, $i);
    }
    else {
      $doc_data = ['name' => $doc_name, 'extension' => $extension];
      return $doc_data;
    }
  }
}

?>
