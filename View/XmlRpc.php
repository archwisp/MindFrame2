<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * MVC view wrapper for the XML-RPC server
 */

/**
 * MVC view wrapper for the XML-RPC server. This view allows access to any
 * MVC controller via XML-RPC.
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_View_XmlRpc extends MindFrame2_View_Abstract
{
   /**
    * @var MindFrame2_XmlRpc_Server
    */
   protected $xmlrpc_server;

   /**
    * View execution entry point. Executed as part of the MVC command pattern
    * chain by the MindFrame2_Application object.
    *
    * @return void
    */
   public function run()
   {
      echo $this->xmlrpc_server->run();
   }

   /**
    * Initializes the XML-RPC server
    *
    * @return void
    */
   protected function init()
   {
      $this->xmlrpc_server = $this->getController()->createXmlRpcServer();
   }
}
