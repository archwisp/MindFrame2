<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Xhtml element builder
 */

/**
 * Xhtml element builder
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
interface MindFrame2_Xhtml_Interface
{
   /**
    * Executed as part of the command pattern chain
    *
    * @return string
    */
   public function render();
}
