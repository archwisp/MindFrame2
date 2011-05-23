<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * XML-RPC server utility
 */

/**
 * XML-RPC server utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_XmlRpc_Server
{
   /**
    * @var object
    */
   private $_controller;

   /**
    * @var array
    */
   private $_allowed_methods = array();

   /**
    * @var array
    */
   private $_method_names = array();

   /**
    * @var string
    */
   private $_username;

   /**
    * @var string
    */
   private $_password;

   /**
    * @var object
    */
   private $_xmlrpc_server;

   /**
    * The server object's error handler
    *
    * @param int $errno The level of the error raised
    * @param string $errstr The error message
    * @param string $errfile The name of the file in which the error was raised
    * @param int $errline The line number on which the error was raised
    *
    * @return FALSE
    */
   public static function handleError($errno, $errstr, $errfile, $errline)
   {
      $exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);

      $fault = new MindFrame2_XmlRpc_Fault();
      $fault->convertException($exception);

      return FALSE;
   }

   /**
    * Configures the object to be accessed via the server.
    *
    * @param object $controller Reference to the object to be accessed via the
    * server
    * @param array $allowed_methods Methods that will not be registered with
    * the XML-RPC server
    * @param string $username The username to expect for http authentication
    * @param string $password The password to expect for http authentication
    *
    * @throws InvalidArgumentException If $controller is not an object
    */
   public function __construct(
      $controller, array $allowed_methods, $username, $password)
   {
      if (!is_object($controller))
      {
         throw new InvalidArgumentException(
            'Expected object for arument #1 ($controller)');
      }

      $this->_controller = $controller;
      $this->_allowed_methods = $allowed_methods;
      $this->_username = $username;
      $this->_password = $password;
   }

   /**
    * Create the server and handle requests.
    *
    * @return void
    */
   public function run()
   {
      header('Content-Type: text/xml');

      if (!$this->_authenticate())
      {
         header('HTTP/1.0 401 Unauthorized');

         $fault = new MindFrame2_XmlRpc_Fault();
         return $fault->build(401, 'Unauthorized');
      }

      try
      {
         $this->_createServer();
         $this->_buildMethodNames();
         $this->_registerMethods();

         return $this->_parseMethodCall();
      }
      catch (Exception $exception)
      {
         $fault = new MindFrame2_XmlRpc_Fault();
         return $fault->convertException($exception);
      }
   }

   /**
    * Returns the list of method names available to the end-user.
    *
    * @return array
    */
   public function getMethodNames()
   {
      return $this->_method_names;
   }

   /**
    * Tests the HTTP username and password
    *
    * @return bool
    */
   private function _authenticate()
   {
      if ($this->_fetchUsername() === $this->_username
         && $this->_fetchPassword() === $this->_password)
      {
         return TRUE;
      }

      return FALSE;
   }

   /**
    * Uses the reflection class to list the public methods of the object being
    * accessed by the server. Methods are filtered by checking the
    * $exclude_methods property.
    *
    * @return void
    */
   private function _buildMethodNames()
   {
      $reflection = new ReflectionObject($this->_controller);
      $methods = $reflection->getMethods();

      foreach ($methods as $method)
      {
         if (in_array($method->name, $this->_allowed_methods))
         {
            $this->_method_names[] = $method->name;
         }
         // end if // (in_array($method->name, $this->_allowed_methods)) //
      }
      // end foreach // ($methods as $method) //
   }

   /**
    * Invoked by xmlrpc_call_method. All controller methods registered with the
    * XML-RPC server are actually proxied through this method.
    *
    * @param string $method Method name
    * @param array $arguments Method arguments
    *
    * @return mixed
    */
   private function _callMethod($method, array $arguments)
   {
      return base64_encode(gzcompress(serialize(call_user_func_array(
         array($this->_controller, $method), $arguments))));
   }

   /**
    * Creates the internal server instance
    *
    * @return bool
    *
    * @throws RuntimeException If the XML-RPC extension is not installed
    */
   private function _createServer()
   {
      set_error_handler(get_class($this) . '::handleError', E_ALL);

      if (!function_exists('xmlrpc_server_create'))
      {
         throw new RuntimeException('This server does not support XML-RPC.');
      }

      $this->_xmlrpc_server = xmlrpc_server_create();
   }

   /**
    * Registers the available object methods as well as this object's
    * getMethodNames method with the XML-RPC server.
    *
    * @return void
    */
   private function _registerMethods()
   {
      $method_names = $this->getMethodNames();

      xmlrpc_server_register_method($this->_xmlrpc_server,
            'getMethodNames', array($this, 'getMethodNames'));

      foreach ($method_names as $method_name)
      {
         xmlrpc_server_register_method($this->_xmlrpc_server,
            $method_name, array($this, '_callMethod'));
      }
      // end foreach // ($methods as $method) //
   }

   /**
    * Handles the XML-RPC request
    *
    * @return string
    */
   private function _parseMethodCall()
   {
      $post_data = $this->_fetchPostData();

      return xmlrpc_server_call_method($this->_xmlrpc_server, $post_data, NULL);
   }

   /**
    * Fetches the XML-RPC request data
    *
    * @return string
    */
   private function _fetchPostData()
   {
      return $GLOBALS['HTTP_RAW_POST_DATA'];
   }

   /**
    * Fetches the HTTP authentication username
    *
    * @return string or FALSE
    */
   private function _fetchUsername()
   {
      return array_key_exists('PHP_AUTH_USER', $_SERVER) ?
         $_SERVER['PHP_AUTH_USER'] : FALSE;
   }

   /**
    * Fetches the HTTP authentication password
    *
    * @return string or FALSE
    */
   private function _fetchPassword()
   {
      return array_key_exists('PHP_AUTH_PW', $_SERVER) ?
         $_SERVER['PHP_AUTH_PW'] : FALSE;
   }
}
