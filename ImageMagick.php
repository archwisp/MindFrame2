<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * ImageMagic utility
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
 * ImageMagic utility
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_ImageMagick
{
   protected $convert = '/usr/bin/convert';

   protected $identify = '/usr/bin/identify';

   /**
    * Creates a thumbnail
    *
    * @param string $source Source file
    * @param string $destination Destination file
    * @param int $size Size of thumbnail
    *
    * @return void
    *
    * @throws InvalidArgumentException if the source argument is an empty string
    * @throws InvalidArgumentException if the destination argument is an empty
    * string
    */
   public function createThumbnail($source, $destination, $size)
   {
      if (empty($source))
      {
         throw new InvalidArgumentException(
            'Argument #1 (source) cannot be empty');
      }

      if (empty($destination))
      {
         throw new InvalidArgumentException(
            'Argument #2 (destination) cannot be empty');
      }

      $geometry = $this->fetchImageGeometry($source);
      list($width, $height) = explode('x', $geometry);

      if ($width > $height)
      {
         $new_width = round($size * ($width / $height));
         $resize = sprintf('-resize %d ', $new_width);
      }
      else
      {
         $resize = sprintf('-resize %d ', $size);
      }

      $cmd = sprintf('%s "%s" %s-gravity center -crop %dx%d+0+0 "%s"',
         $this->convert, $source, $resize, $size, $size, $destination);

      $output = array();
      exec($cmd, $output);
   }

   /**
    * Creates a preview
    *
    * @param string $source Source file
    * @param string $destination Destination file
    * @param int $size Size of thumbnail
    *
    * @return void
    */
   public function createPreview($source, $destination, $size)
   {   
      if (empty($source))
      {   
         throw new InvalidArgumentException('Argument #1 (source) cannot be empty');
      }   
         
      if (empty($destination))
      {   
         throw new InvalidArgumentException('Argument #2 (destination) cannot be empty');
      }   

      $resize = sprintf('-resize %d', $size);

      $cmd = sprintf('%s "%s" %s "%s"', 
         $this->convert, $source, $resize, $destination);

      $output = array();
      exec($cmd, $output);
   }

   /**
    * Builds an associative array of the image meta-data
    *
    * @param string $image File system path
    *
    * @return array
    */
   public function fetchImageData($image)
   {
      $output = array();
      $cmd = sprintf('%s -verbose "%s"', $this->identify, $image);
      exec($cmd, $output);
      $output = MindFrame2_Array::reKey($output, ':');

      return $output;
   }

   /**
    * Returns the dimensions of the specified image
    *
    * @param string $image File system path
    *
    * @return string
    */
   public function fetchImageGeometry($image)
   {
      $output = $this->fetchImageData($image);
      $geometry = $output['Geometry'];

      return $geometry;
   }
}
