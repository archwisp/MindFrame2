<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MVC view interface for MindFrame2_Application
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
 * MVC view interface for MindFrame2_Application
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_View_Interface
{
   /**
    * Construct
    *
    * @param MindFrame2_Controller $controller The MVC controller object
    */
   public function __construct(MindFrame2_Controller $controller);

   /**
    * View execution entry point. Executed as part of the MVC command pattern
    * chain by the MindFrame2_Application object.
    *
    * @return void
    */
   public function run();
}
