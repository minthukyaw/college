<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Browser
 *
 * @author kthant
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
abstract class Ezy_Test_Browser extends PHPUnit_Extensions_SeleniumTestCase{
    //put your code here
    protected $bootstrap = null; //Just optional
    private static $_settings = array();

     /**
     * Set up MVC app
     *
     * Calls {@link bootstrap()} by default
     *
     * @return void
     */
    protected function setUp()
    {
        $this->bootstrap();
    }
    
    public static function setSettings(array $settings){
        self::$_settings = $settings;
    }

    protected function get($key){
        if(isset(self::$_settings[$key])){
            return self::$_settings[$key];
        }
        return false;
    }


    /**
     * Bootstrap the front controller
     *
     * Resets the front controller, and then bootstraps it.
     *
     * If {@link $bootstrap} is a callback, executes it; if it is a file, it include's
     * it. When done, sets the test case request and response objects into the
     * front controller.
     *
     * @return void
     */
    final public function bootstrap()
    {
        $this->reset();
        if (null !== $this->bootstrap) {
            if ($this->bootstrap instanceof Zend_Application) {
                $this->bootstrap->bootstrap();
            } elseif (is_callable($this->bootstrap)) {
                call_user_func($this->bootstrap);
            } elseif (is_string($this->bootstrap)) {
                require_once 'Zend/Loader.php';
                if (Zend_Loader::isReadable($this->bootstrap)) {
                    include $this->bootstrap;
                }
            }
        }
       
    }

    /**
     * Reset MVC state
     *
     * Creates new request/response objects, resets the front controller
     * instance, and resets the action helper broker.
     *
     * @todo   Need to update Zend_Layout to add a resetInstance() method
     * @return void
     */
    public function reset()
    {
        $_SESSION = array();
        $_GET     = array();
        $_POST    = array();
        $_COOKIE  = array();
        
        Zend_Layout::resetMvcInstance();
        Zend_Controller_Action_HelperBroker::resetHelpers();
        Zend_Session::$_unitTestEnabled = true;
    }

    public function  assertElementPresent($locator){

        if(!$this->isElementPresent($locator)){
            sleep(15);
            parent::assertElementPresent($locator);
        }
    }

    protected function takeScreenshot($filename){
        $this->drivers[0]->captureEntirePageScreenshot($filename);
    }
}
?>
