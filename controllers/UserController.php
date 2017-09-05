<?php

class UserController extends Controller
{
    protected $user;
    protected $category;
    protected $questions;

    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();
        $this->category = new CategoryController();
        $this->questions = new QuestionController();
    }

    public function changeAdminPassAction()
    {
        $listOfCategories = $this->category->listOfCategoryAction();
        if (isset($_GET['action'])) {
            $id = $_GET['id'] ?? '';
            if ($_GET['action'] === 'change') {
                $listOfUsers = $this->user->allMembers();
                $this->user->change($id);
                $idForChanging = $_GET['id'] ?? '';
                $listOfQuestions = $this->questions->listOfQuestionAction();
                $this->view->generate('admin.tmpl', array(
                    'idForChanging' => $idForChanging,
                    'listOfUsers' => $listOfUsers,
                    'action' => $_GET['action'] ?? null,
                    'listOfCategories' => $listOfCategories ?? null,
                    // тут всегда будет null
                    'message' => $message ?? null,
                    'rows' => $listOfQuestions ?? null,
                ));
            }
        }
    }

    public function deleteAction()
    {
        $listOfCategories = $this->category->listOfCategoryAction();
        if (isset($_GET['action'])) {
            $id = $_GET['id'] ?? '';
            if ($_GET['action'] === 'delete') {
                $this->user->delete($id);
            }
        }
        $listOfQuestions = $this->questions->listOfQuestionAction();
        $listOfUsers = $this->user->allMembers();
        $this->view->generate('admin.tmpl', array(
            'listOfUsers' => $listOfUsers,
            'listOfCategories' => $listOfCategories ?? null,
            'message' => $message ?? null, // всегда будет null
            'rows' => $listOfQuestions ?? null,
        ));
    }

    public function createAdminAction()
    {
        if (isset($_POST['new-admin-create'])) {
            if (isset ($_POST['idForChanging']) && !empty ($_POST['idForChanging'])) {
                $codeForMessage = $this->user->changeAdministratorPassword ($_POST['new-admin-password'], $_POST['idForChanging']);
				if ($codeForMessage[0] == 0) {
					if ($codeForMessage[1] == 6) {
						$message = 'Введите пароль.';
					}
				} else if ($codeForMessage[0] == 1) {
					if ($codeForMessage[1] = 1) {
						$message = 'Пароль изменён.';
					}
				}
            } else {
                $codeForMessage = $this->user->register ($_POST['new-admin-name'],$_POST['new-admin-password']);
				if ($codeForMessage[0] == 0) {
					if ($codeForMessage[1] == 3) {
						$message = 'Введите логин.';
					} else if ($codeForMessage[1] == 4) {
						$message = 'Введите пароль.';
					} else if ($codeForMessage[1] == 5) {
						$message = 'Логин занят.';
					}
				} else if ($codeForMessage[0] == 1) {
					if ($codeForMessage[1] = 0) {
						$message = 'Новый пользователь создан.';
					}
				}
			}
            $listOfUsers = $this->user->allMembers();
            $idForChanging = $_GET['id'] ?? '';
            $listOfCategories = $this->category->listOfCategoryAction();
            $listOfQuestions = $this->questions->listOfQuestionAction();
            $this->view->generate('admin.tmpl', array(
                'idForChanging' => $idForChanging,
                'listOfUsers' => $listOfUsers,
                'action' => $_GET['action'] ?? null,
                'message' => $message ?? null,
                'listOfCategories' => $listOfCategories ?? null,
                'rows' => $listOfQuestions ?? null,
            ));
        }
    }

    //список пользователей
    public function getUsersAction()
    {
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $listOfCategories = $this->category->listOfCategoryAction();
        $listOfUsers = $this->user->allMembers();
        $listOfQuestions = $this->questions->listOfQuestionAction();
        $this->view->generate('admin.tmpl', array(
            'listOfUsers' => $listOfUsers,
            'listOfCategories' => $listOfCategories ?? null,
            'message' => $message ?? null,
            'rows' => $listOfQuestions ?? null,
        ));
    }


    //Авторизация
    public function loginAction()
    {
        if (isset($_POST['authorization']) && empty($_SESSION['user'])) {
            $codeForMessage = $this->user->login ($_POST['login'],$_POST['password']);
			if ($codeForMessage[0] == 0) {
				if ($codeForMessage[1] == 0) {
					$message = 'Введите логин.';
				} else if ($codeForMessage[1] == 1) {
					$message = 'Введите пароль.';
				} else if ($codeForMessage[1] == 2) {
					$message = 'Данные не совпадают.';
				}
			}
			$this->view->generate('login.tmpl', array (
				'message' => isset($message) ? $message : null,
			));
            /*header( 'Location: /' );*/
        } else {
            $this->view->generate('login.tmpl', array (
				'message' => isset($message) ? $message : null,
			));
		
        }
    }

    //Выход/смена пользователя
    public function logoutAction()
    {
        $this->user->logout();
        $this->view->generate('login.tmpl');
    }
}
