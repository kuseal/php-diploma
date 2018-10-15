<?php
  /**
   * Created by PhpStorm.
   * User: Prikol Studia
   * Date: 14.10.2018
   * Time: 14:40
   */

  namespace lib;


  class Log {

    private $file = LOG;
    private $msg;

    public function __construct() {
      $this->date = date('d-m-Y H:i:s');
      $this->admin = $_SESSION['loginAdmin'];
    }

    public function fileEntry() {

      $handle = fopen($this->file, 'a');
      fwrite($handle, $this->getMsg());
      fclose($handle);
      //      return true;
    }

    public function createTheme($title, $id) {
      $info = "[$this->date] $this->admin добавил тему \"$title\" ($id)\n";
      $this->setMsg($info);
      $this->fileEntry();
    }

    public function deleteTheme($title, $id) {
      $info = "[$this->date] $this->admin удалил тему \"$title\" ($id)\n";
      $this->setMsg($info);
      $this->fileEntry();
    }

    public function Quest($theme, $idTheme, $status, $idQuest) {
      $act = $status == 2 ? 'опубликовал' : 'обновил';
      $info = "[$this->date] $this->admin $act вопрос ($idQuest) из темы \"$theme\" ($idTheme)\n";
      $this->setMsg($info);
      $this->fileEntry();
    }

    public function deleteQuest($theme, $idTheme, $idQuest) {
      $info = "[$this->date] $this->admin удалил вопрос ($idQuest) из темы \"$theme\" ($idTheme)\n";
      $this->setMsg($info);
      $this->fileEntry();
    }

    public function getMsg() {
    return $this->msg;
  }

    public function setMsg($msg) {
      $this->msg = $msg;
    }
  }