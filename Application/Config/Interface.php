<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Application config interface
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
 * Application config interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
