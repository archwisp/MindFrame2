<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract MVC controller class for MindFrame2_Application
 */

/**
 * Abstract MVC controller class for MindFrame2_Application
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Controller
{
   /**
    * @var MindFrame2_Application_Config_Interface
    */
   private $_application_config;

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
   public abstract function run();

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
    * @param MindFrame2_Application_Config_Interface $application_config
    * Application configuration
    * @param array $application_arguments Arguments passed to the application
    */
   public function __construct(
      MindFrame2_Application_Config_Interface $application_config,
      array $application_arguments)
   {
      $this->_application_config = $application_config;
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
      return  new MindFrame2_XmlRpc_Server(
         $this, $this->_xml_rpc_methods,
         $this->_application_config->getXmlRpcUsername(),
         $this->_application_config->getXmlRpcPassword()
      );
   }

   /**
    * Returns the application configuration
    *
    * @return mixed
    */
   public function getApplicationConfig()
   {
      return $this->_application_config;
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

   /**
    * Registers a public method for use via the XML-RPC server.
    *
    * @return void
    */
   protected function registerXmlRpcMethod($method_name)
   {
      $this->_xml_rpc_methods[] = $method_name;
   }
}
