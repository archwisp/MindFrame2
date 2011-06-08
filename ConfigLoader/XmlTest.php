<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Unit tests for MindFrame2_ConfigLoader_Yaml
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
 * Unit tests for MindFrame2_ConfigLoader_Yaml
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_ConfigLoader_XmlTest extends PHPUnit_Framework_TestCase
{
   private $_instance;

   public function setUp()
   {
      $this->_instance = new MindFrame2_ConfigLoader_Xml(
         'MindFrame2/Test/fixtures/ConfigLoader/Xml', 'xml');
   }

   public function testLoadDatabaseEnvironment()
   {
      $actual = $this->_instance->load('environment', 'database');

      $expected = array(
         'dbms' => 'mysql',
         'host' => 'localhost',
         'port' => '3306',
         'username' => 'MindFrame2',
         'password' => '');

      $this->assertEquals($expected, $actual);

   }

   public function testLoadXmlRpcAuthentication()
   {
      $actual = $this->_instance->load('xml-rpc', 'authentication');

      $expected = array(
         'username' => 'MindFrame2',
         'password' => 'FooBar');

      $this->assertEquals($expected, $actual);

   }

   public function testLoadBlankComponent()
   {
      $this->setExpectedException('InvalidArgumentException');
      $this->_instance->load(' ', 'foo');
   }

   public function testLoadBlankSetting()
   {
      $this->setExpectedException('InvalidArgumentException');
      $this->_instance->load('database', ' ');
   }
}
