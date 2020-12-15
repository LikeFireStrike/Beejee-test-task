<?php
/**
* The autoloader for core and app classes
*/
class Autoloader
{
    /**
     * Register autoload function
     * <ul>
     *     <li>
     *         All core classes named with exat one word
     *     </li>
     *     <li>
     *         If class name consist of few words then it's an app class
     *         In this case we need only add a 's' letter to the class type
     *     </li>
     * </ul>
     * @return Boolean
     */
    public static function register()
    {
        spl_autoload_register(function ($className) {
            // Split the CamelCase class name by capital letters
            $fileName  = $className.'.php';
            $nameParts = preg_split('/(?=[A-Z])/', $className);
            if (count($nameParts) == 2) {
                $path = ['Core'];
            } else { 
                // only one an array usage in this case so we dosen't
                // worry about a pointer
                $directory = end($nameParts).'s';
                $path = [$directory]; 
            }
            array_push($path, $fileName);
            $file = implode(DIRECTORY_SEPARATOR, $path);
            if (file_exists($file)) {
                require $file;
                return true;
            } else {
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                echo file_get_contents('404.html');
                die();
            }
            return false;
        });
    }
}
