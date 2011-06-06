<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract MVC view
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
 * Abstract MVC view
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_View_Abstract implements MindFrame2_View_Interface
{
   /**
    * @var MindFrame2_Controller
    */
   private $_controller;

   /**
    * Construct
    *
    * @param MindFrame2_Controller $controller The MVC controller object
    */
   public function __construct(MindFrame2_Controller $controller)
   {
      $this->_controller = $controller;
      $this->init();
   }
   
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
    * Returns the controller object
    *
    * @return MindFrame2_Controller
    */
   protected function getController()
   {
      return $this->_controller;
   }
}
