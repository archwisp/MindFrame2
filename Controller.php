<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract MVC controller class for MindFrame2_Application
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
 * Abstract MVC controller class for MindFrame2_Application
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Controller
{
   /**
    * @var MindFrame2_Application_Config_Interface
    */
   private $_config_loader;

   /**
    * @var array
    */
   private $_application_arguments = array();

   /**
    * @var array
    */
   private $_xml_rpc_methods = array();

   /**
    * Controller execution entry point. Executed as part of the MVC command
    * pattern chain by the MindFrame2_Application object.
    *
    * @return void
    */
   public function run()
   {
   }

   /**
    * Builds the underlying structure for the object. This function is called
    * from the construct
    *
    * @return void
    */
   protected function init()
   {
   }

   /**
    * Sets the application config parameter
    *
    * @param MindFrame2_ConfigurationLoader $configuration_loader
    * @param array $application_arguments Arguments passed to the application
    */
   public function __construct(array $application_arguments)
   {
      $this->_application_arguments = $application_arguments;
      $this->init();
   }

   /**
    * Creates an XML-RPC server
    *
    * @return MindFrame2_XmlRpc_Server
    */
   public function createXmlRpcServer()
   {
      $username = NULL;
      $password = NULL;

      if ($this->config_loader instanceof MindFrame2_ConfigLoader_Interface)
      {
         $config = $this->_config_loader->load('xml-rpc', 'authentication');

         $username = $config['username'];
         $password = $config['password'];
      }

      return  new MindFrame2_XmlRpc_Server(
         $this, $this->_xml_rpc_methods, $username, $password);
   }

   /**
    * Returns the application configuration
    *
    * @return mixed
    */
   public function loadConfiguration($part)
   {
      return $this->_configuration_loader->load($part);
   }

   /**
    * Returns the application configuration
    *
    * @return array or NULL
    */
   public function getApplicationArguments()
   {
      return $this->_application_arguments;
   }

   /**
    * Attempts to retreive the specified application argument
    *
    * @param string $argument The argument to be retrieved
    *
    * @return string or FALSE
    */
   public function getApplicationArgument($argument)
   {
      if (is_array($this->_application_arguments)
         && array_key_exists($argument, $this->_application_arguments))
      {
         return $this->_application_arguments[$argument];
      }

      return FALSE;
   }
   
   protected function getBaseUrl()
   {
      return sprintf('http%s://%s',
         isset($_SERVER['HTTPS']) ? 's' : NULL,
         $_SERVER['HTTP_HOST']);
   }

   /**
    * Registers a public method for use via the XML-RPC server.
    *
    * @return void
    */
   protected function registerXmlRpcMethod($method_name)
   {
      $this->_xml_rpc_methods[] = $method_name;
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
}
