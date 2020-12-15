<?php
Class UserController extends Controller
{ 
    /**
     * Proccess login form AJAX request
     */
    public function loginAction()
    {
        try {
            $this->layout = 'user';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['login']) && isset($_POST['password'])) {
                    $login    = $this->clean_post_params['login'];
                    $password = $this->clean_post_params['password'];
                    $admin    = $this->model->getAdmin();
                    // Add the salt
                    $saltStr  = '(['.md5($password).']@{'.md5($login).'})';
                    $hash     = hash('sha256', $saltStr);
                    $response = [];
                    if ($admin->pword == $hash) {
                        $roleHash = md5('role is '.md5($login));
                        $_SESSION['isAdmin'] = $roleHash;
                        $response['success'] = 1;
                    } else {
                        $response['success'] = 0;
                    }
                } else {
                    throw new Exception("Empty form parameters!");
                }
            } else {
                throw new Exception("The method isn't allowed!");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    /**
     * Logout user
     */
    public function logoutAction()
    {
      session_unset();
      session_destroy();
    }
    
    /**
     * Check is user an admin
     */
    public function secureAction()
    {
      echo $_SESSION['isAdmin'] == md5('role is '.md5('admin')) ? 1 : 0;
    }
}