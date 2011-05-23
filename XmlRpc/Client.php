<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * XML-RPC client utility
 */

/**
 * XML-RPC client utility
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_XmlRpc_Client
{
   /**
    * @var resource
    */
   private $_connection;

   /**
    * @var string
    */
   private $_server_url;

   /**
    * @var string
    */
   private $_username;

   /**
    * @var string
    */
   private $_password;

   /**
    * Sets the XML-RPC server URL
    *
    * @param string $server_url The XML-RPC server URL
    * @param string $username The username for server authentication
    * @param string $password The password for server authentication
    */
   public function __construct($server_url, $username, $password)
   {
      $this->_server_url = $server_url;
      $this->_username = $username;
      $this->_password = $password;
   }

   /**
    * Closes the connection resource if it had been opened durring the
    * existence of the object.
    */
   public function __destruct()
   {
      if (!is_null($this->_connection))
      {
         curl_close($this->_connection);
      }
   }

   /**
    * Magic method automatically handles and executes the sepecified
    * remote method with the specified arguments.
    *
    * @param string $method_name The method to be executed
    * @param array $arguments The arguments to be passed to the method
    *
    * @return mixed
    *
    * @throws MindFrame2_XmlRpc_Exception If a remote error occurs
    */
   public function __call($method_name, array $arguments)
   {
      $connection = $this->_connectOnce();
      $request = xmlrpc_encode_request($method_name, $arguments);
      curl_setopt($connection, CURLOPT_POSTFIELDS, $request);

      $raw_response = curl_exec($connection);

      if ($raw_response === FALSE)
      {
         throw new MindFrame2_XmlRpc_Exception(curl_error($connection));
      }
      // end if // ($raw_response === FALSE) //

      $response = xmlrpc_decode($raw_response, 'iso-8859-1');

      if (is_null($response) && strlen($raw_response) > 0)
      {
         new MindFrame2_Debug($raw_response);

         throw new MindFrame2_XmlRpc_Exception(
            'XML-RPC response could not be decoded');
      }
      elseif (is_array($response) && (xmlrpc_is_fault($response)))
      {
         throw new MindFrame2_XmlRpc_Exception($response['faultString']);
      }
      // end elseif // (is_array($response) && (xmlrpc_is_fault($response))) //

      return unserialize(gzuncompress(base64_decode($response)));
   }

   /**
    * Builds the HTTP Basic authentication string
    *
    * @return string
    */
   private function _buildAuthenticationString()
   {
      return base64_encode($this->_username .':'. $this->_password);
   }

   /**
    * Creates and caches the connection resource.
    *
    * @return resource
    */
   private function _connectOnce()
   {
      if (is_null($this->_connection))
      {
         $this->_connection = curl_init($this->_server_url);

         $header = array(
            'Content-Type: text/xml',
            sprintf('Authorization: Basic %s',
               $this->_buildAuthenticationString())
         );

         curl_setopt_array($this->_connection, array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_USERAGENT => 'MindFrame2_XmlRpc_Client'
         ));
      }
      // end if // (is_null($this->_connection)) //

      return $this->_connection;
   }
}
