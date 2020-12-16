<?php
Class TaskController extends Controller
{
    /**
     * Show tasks list
     */
    public function indexAction()
    {
        $this->validateSort($this->clean_post_params);
        $page      = $this->clean_post_params['page'];
        $column    = $this->clean_post_params['column'];
        $direction = $this->clean_post_params['direction'];
        //$tasks     = $this->model->selectPage($page, $column, $direction);
        $this->drawView('index');
    }
    
    /**
     * Get task item template
     */
    public function templateAction()
    {
      echo View::getTemplate([$this->curName, 'item']);
    }
    
    /**
     * Get sorted tasks list
     */
    public function listAction()
    {
      $tasks = $this->model->selectPage(
          $this->clean_post_params['page'],
          $this->clean_post_params['column'],
          $this->clean_post_params['direction']
      );
      
      echo json_encode($tasks);
    }
    
    public function countAction()
    {
      echo json_encode($this->model->countItems());
    }
    
    /**
     * Create new task
     */
    public function createAction()
    {
        try {
            if ($this->validate()) {
                $response = [];
                $name    = $this->clean_post_params['name'];
                $email   = $this->clean_post_params['email'];
                $content = $this->clean_post_params['content'];
                $res = $this->model->createTask($name, $email, $content);
                if ($res) {
                    $response['location'] = $this->app::BASE_DIR;
                    $response['success'] = 1;
                } else {
                    $response['success'] = 0;
                }
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }
    /**
     * Update task
     */
    public function updateAction()
    {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $response = [];
            if ($this->validate()) {
                $id = $this->clean_post_params['id'];
                $curRecord = $this->model->getById($id);
                if ($curRecord) { // record exist
                    $name    = $this->clean_post_params['name'];
                    $email   = $this->clean_post_params['email'];
                    $content = $this->clean_post_params['content'];
                    $status  = $this->clean_post_params['status'];
                    if (intVal($curRecord['moderated'])) { // Already moderated
                        if (
                            $name       != $curRecord['name']
                            || $email   != $curRecord['email']
                            || $content != $curRecord['content']
                            || $status  != $curRecord['status'] 
                          ) {
                          $moderated = 1;
                        }
                    } else {
                        // Same as $moderated = $curRecord['moderated']
                        $moderated = 1;
                    }
                    $res = $this->model->updateById(
                                                    $id,
                                                    $name,
                                                    $email,
                                                    $content, 
                                                    $status,
                                                    $moderated
                                                  );
                    $response['success'] = $res ? 1 : 0; 
                } else {
                    $response = ['success' => 0, 'error' => 'Record not exist!'];
                }
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
      }
    }
    
    /**
     * Validate task parameters
     * @return Mixed
     */
    private function validate()
    {   
        try {
            if (!empty($_POST)) {
                if (
                    empty($_POST['name'])
                    || !$this->checkEmail()
                    || empty($_POST['content'])
                    || !$this->checkState()
                ) {
                    throw new Exception("Invalid form parameters!");
                } 
            } else {
                throw new Exception("Empty form parameters!");
            }
        } catch (Exception $e){
            echo $e->getMessage();
            die();
        }
        return true;
    }
    /**
     * Validate email field
     */
    private function checkEmail()
    {
        if (!empty($_POST['email'])) {
            $pattern = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/';

            if (preg_match($pattern, $_POST['email']) === 1) {
                return true;
            } else {
                throw new Exception("Invalid email!!");
            }
        } else {
            throw new Exception("Empty email!!");
            return false;
        }
    }
    
    /**
     * Validate task state
     */
    private function checkState()
    {
        if (!empty($_POST['status']) && !is_null($_POST['status'])) {
            return in_array($_POST['status'], [0,1]);
        } else {
            return true;
        }
    }
    
    /**
     * Validate pagination parameters and set default values
     */
    private function validateSort(&$params)
    {
        $params['page']       = !array_key_exists('page', $params) || !is_int($params['page']) ? 1 : $params['page'];
        $params['column']     = !array_key_exists('column',$params) ? 'id'  : $params['column'];
        $params['direction']  = !array_key_exists('order',$params) ? 'DESC' : $params['order'];
    }
}