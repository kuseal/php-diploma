<?php


  namespace controllers;


  use lib\Controller;
  use lib\Vld;
  use models\QuestionsModel;
  use models\ThemesModel;

  class QuestionsController extends Controller {

    public function __construct() {
      if (!isset($_SESSION['role'])) {
        header('Location: /login');
      }
      parent::__construct('/backend');
    }

    public function index() {
      $data['title'] = 'Вопросы';
      $data['themes'] = ThemesModel::getAllThemes();
      $data['qiestions'] = QuestionsModel::viewAllQuestions();

      $this->views->render('/questions/questions_page', $data);
    }

    public function emptyQuestions() {
      $data['title'] = 'Вопросы без ответа';
      $data['themes'] = ThemesModel::getAllThemes();
      $data['qiestions'] = QuestionsModel::viewEmptyQuestions();

      $this->views->render('/questions/empty_questions_page', $data);
    }

    public function update($id) {
      if (QuestionsModel::viewQuestion((int)$id)) {
        if (!empty($_POST)) {
          if (Vld::editQuestion()) {
            if (QuestionsModel::updateQuestion(Vld::editQuestion()['user'], Vld::editQuestion()['email'], Vld::editQuestion()['theme'], Vld::editQuestion()['question'], Vld::editQuestion()['answer'], Vld::editQuestion()['status'], Vld::editQuestion()['id'])) {
              header('Location: /questions/view_question/' . $_POST['id']);
            } else {
              $data['error'] = 'Некорректно заполнена форма.';
            }
          } else {
            $data['error'] = 'Некорректно заполнена форма.';
          }
        }
        $data['title'] = 'Редактировать вопрос';
        $data['themes'] = ThemesModel::getAllThemes();
        $data['question'] = QuestionsModel::viewQuestion($id)[0];
        $data['status'] = QuestionsModel::status();

        $this->views->render('/questions/update_question_page', $data);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы</p>');
      }
    }

    public function viewQuestion($id) {
      if (QuestionsModel::viewQuestion((int)$id)) {
        $data['title'] = 'Вопрос';
        $data['themes'] = ThemesModel::getAllThemes();
        $data['question'] = QuestionsModel::viewQuestion($id)[0];

        $this->views->render('/questions/view_question_page', $data);
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы</p>');
      }
    }

    public function delete($id) {
      if (QuestionsModel::deleteQuestion((int)$id)) {
        header('Location: /questions');
      } else {
        header($_SERVER['SERVER_PROTOCOL'] . 'HTTP/1.0 404 Not found');
        die('<h2>Ошибка 404</h2> <p>Нет страницы</p>');
      }
    }
  }
  