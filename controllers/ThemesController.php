<?php

  namespace controllers;

  use lib\Controller;
  use lib\Vld;
  use models\ThemesModel;
  use models\QuestionsModel;

  class ThemesController extends Controller {

    public function __construct() {
      if (!isset($_SESSION['role'])) {
        header('Location: /login');
      }
      parent::__construct('backend');
    }

    public function index() {
      $data['title'] = 'Темы';
      $data['themes'] = ThemesModel::getAllThemes();

      $this->views->render('/themes/themes_page', $data);
    }

    public function theme($id) {
      if (ThemesModel::getTheme((int)$id)) {
        $data['themes'] = ThemesModel::getAllThemes();
        $data['theme'] = ThemesModel::getTheme($id);
        $data['questions'] = QuestionsModel::questionsInTheme($id);
        $data['title'] = 'Тема ' . $data['theme']['title'];

        $this->views->render('/themes/theme_page', $data);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы</p>');
      }
    }

    // CREATE THEME
    public function create() {
      if (!empty($_POST)) {
        if (Vld::createTheme()) {
          if ($id = ThemesModel::createTheme(Vld::createTheme()['title'])) {
            $this->log->createTheme(Vld::createTheme()['title'], $id);
            header("Location: /themes");
          } else {
            $data['error'] = 'Тема не создана.';
          }
        } else {
          $data['error'] = 'Некорректно заполнена форма.';
        }
      }
      $data['themes'] = ThemesModel::getAllThemes();
      $data['title'] = 'Создать тему ';

      $this->views->render('/themes/create_theme', $data);
    }


    // DELETE THEME
    public function delete($id) {
      $title = ThemesModel::getTheme($id)['title'];
      if (QuestionsModel::questionsInTheme((int)$id)) {
        if (ThemesModel::deleteThemeAndQuestions((int)$id)) {
          $this->log->deleteTheme($title, $id);
          header('Location: /themes');
        } else {
          header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
          die('<h2>Ошибка 404</h2> <p>Нет страницы 1</p>');
        }
      } elseif (ThemesModel::deleteTheme((int)$id)) {
        $this->log->deleteTheme($title, $id);
        header('Location: /themes');
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы 2</p>');
      }

    }
  }
  