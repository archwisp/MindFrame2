<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC view wrapper for the XML-RPC server
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
 * MVC view wrapper for the XML-RPC server. This view allows access to any
 * MVC controller via XML-RPC.
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
