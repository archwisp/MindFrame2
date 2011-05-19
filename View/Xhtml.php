<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * XHTML MVC view class for MindFrame2_Application
 */

/**
 * XHTML MVC view class for MindFrame2_Application
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-23
 */
class MindFrame2_View_Xhtml extends MindFrame2_View_Abstract
{
   /**
    * @var Xhtml_Html
    */
   protected $html;

   /**
    * @var Xhtml_Head
    */
   protected $head;

   /**
    * @var Xhtml_Body
    */
   protected $body;

   /**
    * View execution entry point. Executed as part of the MVC command pattern
    * chain by the MindFrame2_Application object.
    *
    * @return void
    */
   public function run()
   {
      $this->render();
   }

   /**
    * Builds the underlying structure for the object
    *
    * @return void
    */
   protected function init()
   {
      $this->html = new MindFrame2_Xhtml_Html(NULL,
         array('xmlns' => 'http://www.w3.org/1999/xhtml', 'xml:lang' => 'en'));

      $this->html->addContent(
         $this->head = new MindFrame2_Xhtml_Head(NULL, array()));

      $this->html->addContent(
         $this->body = new MindFrame2_Xhtml_Body(NULL, array()));

      $this->head->addContent(new MindFrame2_Xhtml_Title(NULL, array()));

      $this->head->addContent(new MindFrame2_Xhtml_Meta(array(
         'http-equiv' => 'content-type',
         'content' => 'text/html; charset=UTF-8')));

      $this->head->addContent(new MindFrame2_Xhtml_Meta(
         array('http-equiv' => 'content-language', 'content' => 'en-us')));
   }

   /**
    * Executes the XHTML command patern chain. This function produces the actual
    * output from the application.
    *
    * @return void
    */
   protected function render()
   { 
      if (is_array($this->html))
      {
         $xhtml = NULL;

         foreach ($this->html as $element)
         {
            $xhtml .= $element->render();
         }
      }
      else
      {
         $xhtml = $this->html->render();
      }

      echo preg_replace('/\n(\n)/', '\1', $xhtml) . "\n";
   }

   /**
    * Returns the $head property of the object
    *
    * @return Xhtml_Head
    */
   protected function getHead()
   {
      return $this->head;
   }

   /**
    * Returns the $body property of the object
    *
    * @return Xhtml_Body
    */
   protected function getBody()
   {
      return $this->body;
   }

   /**
    * Returns the document title
    *
    * @return string
    */
   protected function getTitle()
   {
      return $this->getHead()->getContentsByTagName('title')->renderContents();
   }

   /**
    * Sets the document title
    *
    * @param string $title New title
    *
    * @return void
    */
   protected function setTitle($title)
   {
      $this->getHead()->getContentsByTagName('title')
         ->setContents(array($title));
   }

   /**
    * Adds a css link element to the header
    *
    * @param string $file_name Path to the CSS file
    *
    * @return void
    */
   protected function addCssFile($file_name)
   {
      $css = new MindFrame2_Xhtml_Link(array(
         'rel' => 'stylesheet',
         'type' => 'text/css',
         'href' => $file_name));

      $this->getHead()->addContent($css);
   }

   /**
    * Adds the specified content to the body of the document
    *
    * @param string $content Body content
    *
    * @return void
    */
   protected function addToBody($content)
   {
      $this->getBody()->addContent($content);
   }

   /**
    * Replaces the HTML in the buffer with the specified content.
    *
    * @param mixed $content Content with which to replace all existing content
    *
    * @return void
    */
   protected function replaceHtml($content)
   {
      $this->html = $content;
   }
}
