<?php 
class View
{
    /**
    * The recursive function which setting up view variables
    * passed from a controller
    * Placeholders like {example} will replaced with variable value 
    * @param String $html The html with placeholders for variables
    * @param Array $params Varriables to use in the view
    * @param String $padding if an array found in $params
    */
    public static function setViewArgs($html, $params, $padding = null)
    {
        // if recursive call
        if (!is_null($padding)) {
            $inner = '';
            foreach ($params as $key => $value) {
                $inner .= $value;
            }
            $html = str_replace($padding, $inner, $html);
        } else {
            if (!empty($params)) {
                $keyArr = [];
                $valArr = [];
                foreach ($params as $key => $value) {
                    if (is_array($value)) {
                        $html = self::setViewArgs($html, $value, $key);
                    } else {
                        $keyArr[] = '{' . $key . '}';
                        $valArr[] = $value;
                    }
                }
                $html = str_replace($keyArr, $valArr, $html);
            }
        }
        // remove unused placeholders
        $html = preg_replace('/{(.*?)}/', '', $html);
        return $html;
    }
    
    /**
     * Insert the view html into selected layout
     * @param String layout file name
     * @param String view html
     * @return String page to draw
     */
     public static function applyLayout($name, $html)
     {
        $pathArr   = ['Views', 'layouts', $name.'.php'];
        $path      = implode(DIRECTORY_SEPARATOR, $pathArr);
        $layout    = file_get_contents($path);
        $toReplace = ['content' => $html];
        $html      = self::setViewArgs($layout, $toReplace);
        return $html;
     }
     /**
      * Get file content by specifed path and return it's content
      * @return String File content
      */
     public static function getTemplate($pathArr)
     {
       array_unshift($pathArr, 'Views');
       $pathArr[key(array_slice($pathArr, -1, 1,true))] .= '.php';
       $path      = implode(DIRECTORY_SEPARATOR, $pathArr);
       return file_get_contents($path);
     }
     
     
}