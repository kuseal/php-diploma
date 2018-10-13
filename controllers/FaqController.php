<?php

  namespace controllers;

  use lib\Controller;
  use lib\Vld;
  use models\ThemesModel;
  use models\QuestionsModel;

  class FaqController extends Controller {

    public function __construct() {
      parent::__construct('frontend');
    }

    public function index() {
      $data['info'] = $data['error'] = '';

      if (!empty($_POST)) {
        if (Vld::createQuestion()) {
          if (QuestionsModel::addQuetion(Vld::createQuestion()['name'], Vld::createQuestion()['email'], Vld::createQuestion()['theme'], Vld::createQuestion()['message'])) {
            header('Location: faq/success');
          } else {
            $data['error'] = 'Запрос не отправлен. Поробуйте еще.';
          }
        } else {
          $data['error'] = 'Некорректно заполнена форма.';
        }
      }

      $data['title'] = 'FAQ';
      $data['themes'] = ThemesModel::frontendThemes();
      $data['all_themes'] = ThemesModel::getAllThemes();
      $data['question'] = QuestionsModel::viewVisibleQuestions();

      $this->views->render('/frontend_page', $data);
    }

    public function success() {
      $data['title'] = 'Ваш вопрос';
      $this->views->render('/success_page', $data);

    }

  }
  