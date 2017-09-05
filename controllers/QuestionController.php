<?php

class QuestionController extends Controller
{
    protected $question;

    public function __construct()
    {
        parent::__construct();
        $this->question = new QuestionModel();
    }

    public function indexAction()
    {
        $listOfCategories = (new CategoryModel())->listOfCategories();
        $listOfQuestions = (new QuestionModel())->listOfQuestions($_POST['categoryFilter'] ?? null);

        $this->view->generate('index.tmpl', array(
            'rows' => $listOfQuestions,
            'listOfCategories' => $listOfCategories ?? null,
        ));
    }

    //список вопросов
    public function listOfQuestionAction()
    {
        if (isset($_POST['categoryFilter'])) {
            $filter = 'WHERE b.id =' . $_POST['categoryFilter'];
            return $this->question->listOfQuestions ($filter );
        }

        return $this->question->listOfQuestions ();
    }

    //Задать вопрос
    public function askAction()
    {
        $codeForMessage = $this->question->addQuestion($_POST);
		if ($codeForMessage[0] == 0) {
			if ($codeForMessage[1] == 0) {
				$message = 'Все поля обязательны для заполнения.';
			}
		} else if ($codeForMessage[0] == 1) {
			if ($codeForMessage[1] = 0) {
				$message = 'Вы задали вопрос. Модератор опубликует его после проверки.';
			}
		}
        $this->view->generate('ask.tmpl', array(
            // $listOfCategories не определена, тут всегда будет null
			'listOfCategories' => $listOfCategories ?? null,
            'message' => $message ?? null,
        ));
        /* $this->indexAction();*/
    }

    public function dellQuestionAction()
    {
        if (isset ($_GET['questionId'])) {
            $this->question->deleteQuestion($_GET['questionId']);
        }
        header('Location: /user/getUsers');
    }

    public function editQuestionAction()
    {
        $this->question->editQuestion ($_POST);
        $this->cardQuestionAction();
    }

    public function cardQuestionAction()
    {
        //Карточка вопроса
        $category = new CategoryController();
        $listOfCategories = $category->listOfCategoryAction();
        $questionCard = $this->question->questionCard ($_GET['questionId']);
        $this->view->generate('question-edit.tmpl', array(
            'rows' => $questionCard,
            'listOfCategories' => $listOfCategories ?? null,
            'stasuses' => array ('На модерации', 'Опубликован', 'Не опубликован'),
            // всегда будет null
            'message' => $message ?? null,
            // всегда будет null
            'answerForQuestion' => $answerForQuestion ?? null,
        ));
    }
}
