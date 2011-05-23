<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Application config interface
 */

/**
 * Application config interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
interface MindFrame2_Application_Config_Interface
{
   /**
    * Returns the username to be used for XML-RPC services
    *
    * @return string
    */
   public function getXmlRpcUsername();

   /**
    * Returns the password to be used for XML-RPC services
    *
    * @return string
    */
   public function getXmlRpcPassword();
}
