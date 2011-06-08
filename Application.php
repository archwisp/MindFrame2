<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC application director class
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   MindFrame2
 * @author    Bryan C. Geraghty <bryan@ravensight.org>
 * @copyright 2005-2011 Bryan C. Geraghty
 * @license   http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link      https://github.com/archwisp/MindFrame2
 */

/**
 * MVC application director class
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Application
{
   /**
    * @var MindFrame2_ConfigLoader_Interface
    */
   private $_config_loader;

   /**
    * @var MindFrame2_Controller
    */
   private $_controller;

   /**
    * @var string
    */
   private $_controller_prefix;

   /**
    * @var string
    */
   private $_default_module;

   /**
    * @var string
    */
   private $_module_parameter;

   /**
    * @var MindFrame2_View_Interface
    */
   private $_view;

   /**
    * @var string
    */
   private $_view_prefix;

   public function __construct(
      $controller_prefix, $view_prefix, $module_parameter, $default_module)
   {
      MindFrame2_Core::assertArgumentIsNotBlank($controller_prefix, 1, 'controller_prefix');
      MindFrame2_Core::assertArgumentIsNotBlank($view_prefix, 1, 'view_prefix');
      MindFrame2_Core::assertArgumentIsNotBlank($module_parameter, 1, 'module_parameter');
      MindFrame2_Core::assertArgumentIsNotBlank($default_module, 1, 'default_module');

      $this->_controller_prefix = $controller_prefix;
      $this->_view_prefix = $view_prefix;
      $this->_module_parameter = $module_parameter;
      $this->_default_module = $default_module;
   }

   /**
    * Executes the command pattern chain
    *
    * @return void
    */
   public function run()
   {
      if (!$this->isCliRequest())
      {
         session_start();
      }

      $this->createController();
      $this->createView();

      $this->_controller->run();
      $this->_view->run();
   }

   /**
    * Sets the config loader to be passed down the stack
    *
    * @param MindFrame2_ConfigLoader_Interface $config_loader Config Loader
    */
   public function setConfigLoader(MindFrame2_ConfigLoader_Interface $config_loader)
   {
      $this->_config_loader = $config_loader;
   }

   /**
    * Creates the internal controller object
    *
    * @return void
    *
    * @throws UnexpectedValueException If the resulting controller
    * object is not an instance of MindFrame2_Controller
    */
   protected function createController()
   {
      $controller_class = $this->buildControllerClassName();
      $arguments = $this->fetchArguments();

      $this->_controller = new $controller_class($arguments);

      if ($this->_config_loader instanceof MindFrame2_ConfigLoader_Interface)
      {
         $this->_controller->setConfigLoader($this->_config_loader);
      }

      if (!$this->_controller instanceof MindFrame2_Controller)
      {
         throw new UnexpectedValueException(sprintf(
            'Controller object "%s" must be an instance of ' .
            'MindFrame2_Controller', $controller_class));
      }
   }

   /**
    * Builds an associative array of the arguments passed to the application
    *
    * @return array
    */
   protected function fetchArguments()
   {
      if (!$this->isCliRequest())
      {
         $arguments = array_merge($_GET, $_POST);
         $arguments = array_map(array($this, '_stripSlashes'), $arguments);
      }
      else
      {
         global $argv;
         $arguments = is_array($argv) ? $argv : array();
         array_shift($arguments);
         $arguments = MindFrame2_Array::rekey($arguments, '=');
      }

      return $arguments;
   }

   /**
    * Fetches the name of the module to load. Has the ability to load CLI
    * environment variables for testing.
    *
    * @return string
    */
   protected function fetchModuleName()
   {
      $arguments = $this->fetchArguments();
      
      $module_name = isset($arguments[$this->_module_parameter])
         ? ucfirst($arguments[$this->_module_parameter])
         : $this->_default_module;

      return $module_name;
   }

   /**
    * Controller class name abstraction function for custom extensions
    *
    * @return string
    */
   protected function buildControllerClassName()
   {
      return $this->_controller_prefix . '_' . $this->fetchModuleName();
   }

   /**
    * Creates the internal view object
    *
    * @return void
    *
    * @throws UnexpectedValueException If the resulting view object is not an
    * instance of MindFrame2_View_Interface
    */
   protected function createView()
   {
      $view_class = $this->buildViewClassName();
      $this->_view = new $view_class($this->_controller);

      if (!$this->_view instanceof MindFrame2_View_Interface)
      {
         throw new UnexpectedValueException(sprintf(
            'View object "%s" must be an instance of MindFrame2_View_Interface',
            $view_class));
      }
   }

   /**
    * View class name abstraction function for custom extensions
    *
    * @return string
    */
   protected function buildViewClassName()
   {
      if ($this->isXmlHttpRequest())
      {
         $class_name = 'MindFrame2_View_XmlHttp';
      }
      elseif ($this->isXmlRpcRequest())
      {
         $class_name = 'MindFrame2_View_XmlRpc';
      }
      else
      {
         $class_name =
            $this->_view_prefix . '_' . $this->fetchModuleName();
      }

      return $class_name;
   }
   
   public function isXmlHttpRequest()
   {
      if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER)
         && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
      {
         return true;
      }

      return FALSE;
   }

   /**
    * Determines whether or not the current request is being made via XML-RPC
    *
    * @return bool
    */
   public function isXmlRpcRequest()
   {
      $input = file_get_contents('php://input', FALSE, NULL, 0, 19);

      if (strpos($input, '<?xml version="1.0"') === 0)
      {
         return TRUE;
      }

      return FALSE;
   }

   /**
    * Determines whether or not the current request is being made via a web
    * browser
    *
    * @return bool
    */
   protected function isCliRequest()
   {
      if (isset($_SERVER['HTTP_USER_AGENT']))
      {
         return FALSE;
      }

      return TRUE;
   }

   /**
    * Recursively strips slashes from the specified value
    *
    * @param mixed $value The value to be stripped of slashes
    *
    * @return mixed
    */
   private function _stripSlashes($value)
   {
      if (is_array($value))
      {
         return array_map(array($this, '_stripSlashes'), $value);
      }
      else
      {
         return stripslashes($value);
      }
   }
}
