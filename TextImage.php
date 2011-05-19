<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Renders an image containing text
 */

/**
 * Renders an image containing text
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-19
 */
class MindFrame2_TextImage
{
   private $_char_height = 14;
   private $_char_width = 8;
   private $_file_name;
   private $_height;
   private $_padding = 2;
   private $_width;

   public function __construct($width, $height, $file_name)
   {
      $this->_file_name = $file_name;
      $this->_height = $height;
      $this->_width = $width;
   }

   public function build($text)
   {
      $image = imagecreate($this->_width, $this->_height);

      $white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
      $black = imagecolorallocate($image, 0x00, 0x00, 0x00);

      $column_count = $this->_calculateColumnCount();
      $line_count = $this->_calculateLineCount();

      // If there is more than one line worth of text to render, we need to 
      // break it into chunks that will fit into the column count for each 
      // line.  We'll then count the number of chunks we have and subtract half
      // of that number times the height of a character (to get pixels) from 
      // the normal Y position (middle). We'll then write each line and 
      // increment the Y position by the height of a character each time so 
      // each line is written on a new line.

      if (strlen($text) >= $column_count)
      {
         $chunks = str_split($text, $column_count);
         
         $y_position = $this->_calculateYPosition();
         $y_position -= round(count($chunks) / 2) * $this->_char_height;

         foreach ($chunks as $chunk)
         {
            $x_position = $this->_calculateXPosition($column_count, $chunk);
            imagestring($image, 4, $x_position, $y_position, $chunk, $black);
            $y_position += $this->_char_height;
         }
         // end foreach // ($chunks as $chunk) //
      }
      else
      {
         $x_position = $this->_calculateXPosition($column_count, $text);
         $y_position = $this->_calculateYPosition();

         imagestring($image, 4, $x_position, $y_position, $text, $black);
      }

      imagepng($image, $this->_file_name);
      
      imagecolordeallocate($image, $black);
      imagecolordeallocate($image, $white);
      imagedestroy($image);
   }

   private function _calculateColumnCount()
   {
      return round($this->_width/$this->_char_width, 0) - ($this->_padding * 2) - 1;
   }
      
   private function _calculateLineCount()
   {
      return round($this->_height/$this->_char_height, 0) - ($this->_padding * 2) - 1;
   }

   private function _calculateXPosition($column_count, $text)
   {
      $position = NULL;

      if (strlen($text) >= $column_count)
      {
         $position = ($this->_padding * $this->_char_width);
      }
      else
      {
         $middle_pixel = round(($this->_width / 2), 2);

         $text_middle_char = round((strlen($text) / 2), 2);
         $text_middle_pixel = $text_middle_char * $this->_char_width;

         $position = $middle_pixel - $text_middle_pixel;
      }
      
      return $position;
   }

   private function _calculateYPosition()
   {
      return round($this->_height / 2, 0);
   }
}
