<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Standard
 *
 * @author kthant
 */
class Ezy_Route_Standard extends Zend_Controller_Router_Route_Module{
    //put your code here

     /**
     * Instantiates route based on passed Zend_Config structure
     */
    public static function getInstance(Zend_Config $config)
    {
        $frontController = Zend_Controller_Front::getInstance();

        $defs       = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        $dispatcher = $frontController->getDispatcher();
        $request    = $frontController->getRequest();

        return new self($defs, $dispatcher, $request);
    }
    
     /**
     * Assembles user submitted parameters forming a URL path defined by this route
     *
     * @param array $data An array of variable and value pairs used as parameters
     * @param bool $reset Weither to reset the current params
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = true, $partial = false)
    {
        if (!$this->_keysSet) {
            $this->_setRequestKeys();
        }

        $params = (!$reset) ? $this->_values : array();

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $params[$key] = $value;
            } elseif (isset($params[$key])) {
                unset($params[$key]);
            }
        }

        $params += $this->_defaults;

        $url = '';

        if ($this->_moduleValid || array_key_exists($this->_moduleKey, $data)) {
            if ($params[$this->_moduleKey] != $this->_defaults[$this->_moduleKey]) {
                $module = $params[$this->_moduleKey];
            }
        }
        unset($params[$this->_moduleKey]);

        $controller = $params[$this->_controllerKey];
        unset($params[$this->_controllerKey]);

        $action = $params[$this->_actionKey];
        unset($params[$this->_actionKey]);

        foreach ($params as $key => $value) {
            $key = ($encode) ? urlencode($key) : $key;
            if (is_array($value)) {
                foreach ($value as $arrayValue) {
                    $arrayValue = ($encode) ? urlencode($arrayValue) : $arrayValue;
                    $url .= '&'.$key . '=' . $arrayValue;
                }
            } else {
                if ($encode) $value = urlencode($value);
                $url .= '&'. $key . '=' .$value;
            }
        }

        if(!empty($url)){
            $url = ltrim($url, '&');
            $url = '?'.$url;
        }

        if (!empty($url) || $action !== $this->_defaults[$this->_actionKey]) {
            if ($encode) $action = urlencode($action);
            $url = self::URI_DELIMITER . $action . $url;
        }

        if (!empty($url) || $controller !== $this->_defaults[$this->_controllerKey]) {
            if ($encode) $controller = urlencode($controller);
            $url = self::URI_DELIMITER . $controller . $url;
        }

        if (isset($module)) {
            if ($encode) $module = urlencode($module);
            $url = self::URI_DELIMITER . $module . $url;
        }

        return ltrim($url, self::URI_DELIMITER);
    }
}
?>
