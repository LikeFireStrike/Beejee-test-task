<?php
/**
 * @abstract controller
 * @author LikeFireStrike
 */
abstract class Controller
{
    /**
    * @var App $app Pointer to App object
    */
    protected $app;
    
    /**
    * @var Array Sanitized $_POST parameters
    */
    protected $post_params;
    
    /**
    * @var Model $model Controller's model
    */
    protected $model;
    
    /**
    * @var String $curName Current controller name without suffix
    */
    protected $curName;
    
    /**
    * @var String $actionName Current action name
    */
    protected $actionName;
    
    /**
    * @var String $layout Current layout name
    */
    protected $layout = 'main';
    
    /**
     * Set model and sanitize $_POST, $_GET parameters
     */
    function __construct($app, $action)
    {
        $this->app         = $app; 
        $this->curName     = str_replace('Controller', '', get_class($this));
        $this->actionName  = $action;
        $curModelName      = $this->curName . "Model";
        $this->model       = new $curModelName($app, strtolower($this->curName).'s');
        $this->clean_post_params = $this->wash_params($_POST);
        $this->clean_get_params  = $this->wash_params($_GET);
    }
    
    /**
    * Handle specific method
    * @param String $action name of file that will be loaded into empty page
    * @param Array $query query string params except action
    */
    public function doAction() {
        $action  = $this->actionName;
        $action .= 'Action';
        $this->$action();
    }
    
    /**
     * Pass variables to the view
     * @param String $file File name to draw 
     * @param Array $params The array with keys and values to replace
     */
     protected function drawView($file, $params = [])
     {
        $params['BASE_DIR'] = $this->app::BASE_DIR;
        $pathArr            = ['Views', $this->curName, $file.'.php'];
        $path               = implode(DIRECTORY_SEPARATOR, $pathArr);
        $html               = file_get_contents($path);
        $html               = View::setViewArgs($html, $params);
        $html               = View::applyLayout($this->layout, $html);
        echo $html;
     }
    
    /**
     * Pass variables to the view file and collect html into string
     * @param String $file File name to draw 
     * @param Array $params The array of arrays with keys and values to replace
     */
     protected function drawPartial($file, $items = [])
     {
        if (!empty($items)) {
            $template = '';
            $path = $this->combinePath($file);
            try {
                $template = file_get_contents($path);
            }
            catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
            $html = '';
            $lambdaBag = []; // Bag with stuff to pass into lambda function
            // Lambda function realize cutom loop cycle
            $lambda = function ($current, $key, $iterator) use (&$lambdaBag) {
                $lambdaBag['html'] .= View::setViewArgs(
                                          $lambdaBag['template'],
                                          $current
                                      );  
                $iterator->next();
                if ($iterator->valid()) { // check is it array end
                    // Lambda function recurcive call 
                    $lambda = $lambdaBag['lambda'];
                    $lambda($iterator->current(), $iterator->key(), $iterator);
                }
                return true;
            };
            $lambdaBag = [
                'lambda'   => $lambda,
                'html'     => &$html, // output
                'template' => &$template // Template with placeholders
            ];
            $iterator = new ArrayIterator($items);
            $iterator = new CallbackFilterIterator($iterator, $lambda);
            $iterator->rewind();
            return $lambdaBag['html'];
        }
     }
    
     /**
      * Create a path from a path array
      */
     private function combinePath($file)
     {
        $pathArr = ['Views', $this->curName, $file.'.php'];
        $path    = implode(DIRECTORY_SEPARATOR, $pathArr);
        return $path;
     }
     
    /**
    * XSS prevention
    * @param Array $elements array like $_GET or $_POST
    */
    private function wash_params($fresh_params)
    {
        $scrapped_params = array_map('htmlspecialchars', $fresh_params);
        return $scrapped_params;
    }
}
