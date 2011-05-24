<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS cluster model
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
 * DBMS cluster model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Cluster
{
   private $_relationships = array();

   public function addRelationship(
      MindFrame2_NetworkNode $primary, MindFrame2_NetworkNode $secondary)
   {
      $primary_id = $primary->getNodeId();

      if (!array_key_exists($primary_id, $this->_relationships))
      {
         $this->_relationships[$primary_id] = array();
      }

      $this->_relationships[$primary_id][] = $secondary;
   }

   public function getRelationShipsForNode($node_id)
   {
      if (!array_key_exists($node_id, $this->_relationships))
      {
         return FALSE;
      }

      return $this->_relationships[$node_id];
   }
}
