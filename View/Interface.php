<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * MVC view interface for MindFrame2_Application
 */

/**
 * MVC view interface for MindFrame2_Application
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-23
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
