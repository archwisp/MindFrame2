<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Xhtml element builder
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
 * Xhtml element builder
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Xhtml_AbstractContainerElement
   extends MindFrame2_Xhtml_Abstract
{
   /**
    * @var array
    */
   protected $contents = array();

   /**
    * Creates the object
    *
    * @param array $contents The initial contents of the element
    * @param array $options The HTML params of the element
    */
   public function __construct($contents, array $options)
   {
      $this->contents[] = $contents;
      $this->options = $options;
   }

   /**
    * Adds content to the collection
    *
    * @param mixed $content Content to be added to the collection
    *
    * @return void
    */
   public function addContent($content)
   {
      $this->contents[] = $content;
   }

   /**
    * Overwrites the existing contents
    *
    * @param array $contents New contents
    *
    * @return void
    */
   public function setContents(array $contents)
   {
      $this->contents = $contents;
   }

   /**
    * Adds content the beginning of the collection
    *
    * @param mixed $content Content to be added to the beginning of the
    * collection
    *
    * @return void
    */
   public function unshiftContent($content)
   {
      array_unshift($this->contents, $content);
   }

   /**
    * Renders the contents in the collection
    *
    * @return string
    *
    * @throws UnexpectedValueException If an object which does not implement
    * MindFrame2_Xhtml_Interface is encountered
    * @throws UnexpectedValueException If an array is encountered
    */
   public function renderContents()
   {
      $xhtml = NULL;

      foreach ($this->contents as $element)
      {
         if ($element instanceof MindFrame2_Xhtml_Interface)
         {
            $xhtml .= "\n" . $element->render() . "\n";
         }
         elseif (is_object($element))
         {
            throw new UnexpectedValueException(sprintf(
               'Invalid object encounterd while '.
               'rendering the XHTML stack: (%s)',
               get_class($element)));
         }
         elseif (is_array($element))
         {
            throw new UnexpectedValueException(
               sprintf('Array encounterd while rendering the XHTML stack: (%s)',
               print_r($element, TRUE)));
         }
         else
         {
            $xhtml .= htmlentities($element);
         }
      }
      // end foreach // ($this->contents as $element) //

      return $xhtml;
   }

   /**
    * Renders the XHTML
    *
    * @return string
    */
   public function render()
   {
      $xhtml = sprintf("<%s%s>%s</%s>",
         $this->tag,
         $this->renderOptions(),
         $this->renderContents(),
         $this->tag);

      return $xhtml;
   }

   /**
    * Searches through the contents collection and tries looks for an element
    * with the specified tag
    *
    * @param string $tag The tag to search for
    *
    * @return Xhtml_Abstract
    */
   public function getContentsByTagName($tag)
   {
      foreach ($this->contents as $element)
      {
         if ($element instanceof MindFrame2_Xhtml_Abstract)
         {
            if (strtolower($element->getTag()) == strtolower($tag))
            {
               return $element;
            }
         }
      }

      return FALSE;
   }

   /**
    * Searches through the contents collection and tries looks for an element
    * with the specified id
    *
    * @param string $element_id The ID to search for
    *
    * @return Xhtml_Abstract
    */
   public function getElementById($element_id)
   {
      foreach ($this->contents as $element)
      {
         if ($element instanceof MindFrame2_Xhtml_Abstract)
         {
            if (strcasecmp($element->getOptionByName('id'), $element_id) === 0)
            {
               return $element;
            }
            elseif (($element instanceof MindFrame2_Xhtml_AbstractContainerElement)
               && (($child = $element->getElementById($element_id)) !== FALSE))
            {
               return $child;
            }
         }
         // end if // ($element instanceof MindFrame2_Xhtml_Abstract) //
      }

      return FALSE;
   }
}
