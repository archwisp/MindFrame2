<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Abstract MVC view
 */

/**
 * Abstract MVC view
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_View_Abstract implements MindFrame2_View_Interface
{
   /**
    * @var MindFrame2_Controller
    */
   protected $controller;

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
    * Construct
    *
    * @param MindFrame2_Controller $controller The MVC controller object
    */
   public function __construct(MindFrame2_Controller $controller)
   {
      $this->controller = $controller;
      $this->init();
   }

   /**
    * Returns the controller object
    *
    * @return MindFrame2_Controller
    */
   protected function getController()
   {
      return $this->controller;
   }
}
