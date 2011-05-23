<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * MVC application director class
 */

/**
 * MVC application director class
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Application
{
   /**
    * @var MindFrame2_Application_Config_Interface
    */
   private $_config;

   /**
    * @var MindFrame2_Controller
    */
   private $_controller;

   /**
    * @var string
    */
   private $_controller_prefix = 'Controller';

   /**
    * @var string
    */
   private $_default_module_name = 'Default';

   /**
    * @var MindFrame2_View_Interface
    */
   private $_view;

   /**
    * @var string
    */
   private $_view_prefix = 'View';

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

      $this->getController()->run();
      $this->getView()->run();
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

      $this->_controller =
         new $controller_class($this->getConfig(), $arguments);

      if (!$this->_controller instanceof MindFrame2_Controller)
      {
         throw new UnexpectedValueException(sprintf(
            'Controller object "%s" must be an instance of MindFrame2_Controller',
            $controller_class));
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
         $arguments = $_REQUEST;
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

      $module_name = isset($arguments['module'])
         ? ucfirst($arguments['module']) : $this->getDefaultModuleName();

      return $module_name;
   }

   /**
    * Controller class name abstraction function for custom extensions
    *
    * @return string
    */
   protected function buildControllerClassName()
   {
      return $this->getControllerPrefix() . '_' . $this->fetchModuleName();
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
      $this->_view = new $view_class($this->getController());

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
      if ($this->isXmlRpcRequest())
      {
         $class_name = 'MindFrame2_View_XmlRpc';
      }
      else
      {
         $class_name =
            $this->getViewPrefix() . '_' . $this->fetchModuleName();
      }

      return $class_name;
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
    * Returns the name of the default module
    *
    * @return string
    */
   public function getDefaultModuleName()
   {
      return $this->_default_module_name;
   }

   /**
    * Returns the application configuration object
    *
    * @return MindFrame2_Application_Config_Interface
    */
   public function getConfig()
   {
      return $this->_config;
   }

   /**
    * Returns the controller object
    *
    * @return MindFrame2_Controller
    */
   public function getController()
   {
      return $this->_controller;
   }

   /**
    * Returns the prefix in which the controller classes exist. This will
    * also be the controller class prefix.
    *
    * @return string
    */
   public function getControllerPrefix()
   {
      return $this->_controller_prefix;
   }

   /**
    * Returns the view object
    *
    * @return MindFrame2_View
    */
   public function getView()
   {
      return $this->_view;
   }

   /**
    * Returns the prefix in which the view classes exist. This will also be
    * the view class prefix.
    *
    * @return string
    */
   public function getViewPrefix()
   {
      return $this->_view_prefix;
   }

   /**
    * Sets the prefix for the controller classes. This will also determine the
    * filesystem location of the class files as interpreted by the auto-loader.
    *
    * @param string $prefix Controller class prefix
    *
    * @return void
    */
   public function setControllerPrefix($prefix)
   {
      $this->_controller_prefix = $prefix;
   }

   /**
    * Sets the name of the default module. The default module is loaded when no
    * module is specified.
    *
    * @param string $module_name Name of the default module
    *
    * @return void
    */
   public function setDefaultModuleName($module_name)
   {
      $this->_default_module_name = $module_name;
   }

   /**
    * Sets the prefix for the view classes. This will also determine the
    * filesystem location of the class files as interpreted by the auto-loader.
    *
    * @param string $prefix View class prefix
    *
    * @return void
    */
   public function setViewPrefix($prefix)
   {
      $this->_view_prefix = $prefix;
   }

   /**
    * Sets the config parameter which is passed to the controller upon
    * creation. Can be used to create custom config objects.
    *
    * @param MindFrame2_Application_Config_Interface $config Config object
    *
    * @return void
    */
   public function setConfig(MindFrame2_Application_Config_Interface $config)
   {
      $this->_config = $config;
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
