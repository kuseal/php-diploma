<?php


  namespace controllers;


  use lib\Controller;
  use lib\View;
  use models\AdminModel;

  class LoginController extends Controller {

    public function __construct() {
      parent::__construct('/backend');
    }

    public function index() {
      if (isset($_SESSION['role'])) {
        header('Location: /admin');
      }
      if (!empty($_POST)) {
        $login = htmlspecialchars($_POST['login']);
        if (AdminModel::checkLogin($login)) {
          $pass = htmlspecialchars($_POST['pass']);
          if ($data['admin'] = AdminModel::checkLoginPass($login, $pass)) {
            if (!isset($_SESSION)) {
              session_start();
            }
            $_SESSION['role'] = $data['admin']['role_id'];
            $_SESSION['loginAdmin'] = $data['admin']['login'];
            header('Location: /admin');
          } else {
            $data['error'] = 'Неверный логин или пароль';
          }
        } else {
          $data['error'] = 'Неверный логин или пароль';
        }
      }

      $data['title'] = 'Вход';

      $this->views->render('/login/login_page', $data);
    }


    public function logout(){
      session_destroy();
      header('Location: /login');
    }
  }
  