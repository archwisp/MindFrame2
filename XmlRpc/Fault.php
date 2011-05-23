<?php // vim:ts=3:sts=3:sw=3:et:

/**
 > @file
* Fault class for server-side XML-RPC errors
*/

/**
* Fault class for server-side XML-RPC errors
*
* @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_XmlRpc_Fault
{
   /**
    * Converts an exception into an XML-RPC fault
    *
    * @param Exception $exception Exception to be converted
    *
    * @return string
    */
   public function convertException(Exception $exception)
   {
      $fault = array(
         'faultCode' => $exception->getCode(),
         'faultString' => sprintf('%s %s',
            $exception->getMessage(),
            $exception->getTraceAsString()));

      $xml = $this->encode($fault);

      return $xml;
   }

   /**
    * Builds an XML-RPC fault from the specified fault code and string.
    *
    * @param int $fault_code Fault code
    * @param string $fault_string Fault string
    *
    * @return string
    */
   public function build($fault_code, $fault_string)
   {
      $fault = array(
         'faultCode' => $fault_code,
         'faultString' => $fault_string
      );

      return $this->encode($fault);
   }

   /**
    * Encodes the array as an XML-RPC message. The goal here is to duplicate
    * the functionality of xmlrpc_encode so in the event that the xmlrpc
    * library is not installed, proper XML-RPC faults will still be sent back
    * to the client. If not, the XML-RPC client has no way of determining
    * whether the response is a fatal error, or a normal response.
    *
    * @param array $fault The fault definition
    *
    * @return string
    */
   protected function encode(array $fault)
   {
      $response = array();
      $response[] = '<?xml version="1.0" encoding="utf-8"?>';
      $response[] = '<methodResponse>';
      $response[] = '<fault>';
      $response[] = '   <value>';
      $response[] = '      <struct>';
      $response[] = '         <member>';
      $response[] = '            <name>faultCode</name>';
      $response[] = '            <value>';
      $response[] = '               <int>' . $fault['faultCode'] . '</int>';
      $response[] = '            </value>';
      $response[] = '         </member>';
      $response[] = '         <member>';
      $response[] = '            <name>faultString</name>';
      $response[] = '            <value>';
      $response[] = '               <string>' .
                                       htmlentities($fault['faultString']) .
                                    '</string>';
      $response[] = '            </value>';
      $response[] = '         </member>';
      $response[] = '      </struct>';
      $response[] = '   </value>';
      $response[] = '</fault>';
      $response[] = '</methodResponse>';

      return join("\n", $response);
   }
}
