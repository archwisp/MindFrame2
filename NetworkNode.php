<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Network node model
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
 * Network node model
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_NetworkNode extends MindFrame2_Object
{
   const STATE_DOWN = 0;
   const STATE_NORMAL = 1;

   /**
    * @var string
    */
   protected $node_id;

   /**
    * @var string
    */
   protected $ip_address;

   /**
    * @var int
    */
   protected $state;

   /**
    * Model construct
    *
    * @param string $node_id Physical address
    * @param string $ip_address IPv4 address
    * @param int $state The node's state
    *
    * @throws InvalidArgumentException If node id is not a valid MAC address
    * @throws InvalidArgumentException If ip address is not a valid IP address
    * @throws InvalidArgumentException If state is not an integer value
    */
   public function __construct($node_id, $ip_address, $state)
   {
      if (!MindFrame2_Validate::isValidMacAddress($node_id))
      {
         throw new InvalidArgumentException(
            'Expected valid MAC address for argument #1 (node id)');
      }

      if (!MindFrame2_Validate::isValidIPv4Address($ip_address))
      {
         throw new InvalidArgumentException(
            'Expected valid IP address for argument #2 (ip address)');
      }

      $this->node_id = $node_id;
      $this->ip_address = $ip_address;
      $this->state = $state;
   }

   /**
    * Fetches the node's ID (physical address).
    *
    * @return string
    */
   public function getNodeId()
   {
      return $this->node_id;
   }

   /**
    * Fetches the node's IP address.
    *
    * @return string
    */
   public function getIpAddress()
   {
      return $this->ip_address;
   }

   /**
    * Returns the node's state
    *
    * @return int
    */
   public function getState()
   {
      return $this->state;
   }

   /**
    * Sets the node's state
    *
    * @param string $state Node's state
    *
    * @return void
    */
   public function setState($state)
   {
      $this->state = $state;
   }
}
