<?php

  namespace controllers;

  use lib\Controller;
  use lib\Vld;
  use models\AdminModel;
  use models\QuestionsModel;
  use models\ThemesModel;

  class AdminController extends Controller {

    public function __construct() {
      if (!isset($_SESSION['role'])) {
        header('Location: /login');
      }
      parent::__construct('backend');

    }

    public function index() {
      $data['title'] = 'Главная';
      $data['themes'] = ThemesModel::getAllThemes();
      $data['admins'] = AdminModel::viewAllAdmins();
      $data['questions'] = QuestionsModel::viewAllQuestions();

      $this->views->render('/admin/admin_page', $data);
    }

    public function allAdmins() {
      $data['title'] = 'Администраторы';
      $data['themes'] = ThemesModel::getAllThemes();
      $data['admins'] = AdminModel::viewAllAdmins();
      $data['title'] = 'Администраторы';

      $this->views->render('/admin/all_admin_page', $data);
    }

    public function viewAdmin($id) {
      if (AdminModel::viewAdmin((int)$id)) {
        $data['admin'] = AdminModel::viewAdmin((int)$id)[0];
        $data['title'] = 'Администратор ' . $data['admin']['login'];
        $data['themes'] = ThemesModel::getAllThemes();

        $this->views->render('/admin/view_admin_page', $data);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы</p>');
      }
    }

    public function addAdmin() {
      if (!empty($_POST)) {
        if (!empty($_POST['login']) && !empty($_POST['pass'])) {
          $login = htmlspecialchars($_POST['login']);
          if (!AdminModel::checkLogin($login)) {
            if(Vld::createAdmin()){
              if (AdminModel::createAdmin(Vld::createAdmin()['login'], Vld::createAdmin()['pass'])) {
                header('Location: /admin/all_admins');
              } else {
                $data['error'] = 'Ошибка. Попробуйте еще раз.';
              }
            }else {
              $data['error'] = 'Некорректно заполнена форма';
            }
          } else {
            $data['error'] = 'Логин занят';
          }
        } else {
          $data['error'] = 'Не все поля заполнены';
        }
      }

      $data['title'] = 'Добавить администратора';
      $data['themes'] = ThemesModel::getAllThemes();

      $this->views->render('/admin/create_admin_page', $data);
    }

    // Изменение пароля
    public function updatePass($id) {
      if (!empty($_POST)) {
        if (!empty($_POST['pass']) && !empty($_POST['repeatPass']) && $_POST['pass'] == $_POST['repeatPass']) {
          if(Vld::updatePass()){
            if (AdminModel::updatePassAdmin(Vld::updatePass()['pass'], Vld::updatePass()['id'])) {
              header('Location: /admin/all_admins');
            } else {
              $data['error'] = 'Ошибка. Попробуйте еще раз.';
            }
          } else {
            $data['error'] = 'Некорректно заполнена форма';
          }
        } else {
          $data['error'] = 'Пароли не совпадают.';
        }
      }
      $data['admin'] = AdminModel::viewAdmin((int)$id);
      $data['title'] = 'Изменить пароль ' . $data['admin']['login'];
      $data['themes'] = ThemesModel::getAllThemes();

      $this->views->render('/admin/update_pass_page', $data);
    }

    public function deleteAdmin($id) {
      if ($_SESSION['role'] == 2) {
        AdminModel::deleteAdmin((int)$id);
        header('Location: /admin/all_admins');
      } else {
        header('Refresh: 2; /admin/all_admins');
        die('<h2>Ошибка 403</h2> <p>Нет прав</p>');
      }
    }
  }
  