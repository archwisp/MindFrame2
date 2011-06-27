<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Authorization organization model interface
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
 * Authorization organization model interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_Authorization_OrganizationInterface
{
   /**
    * Returns the identifier associated with the organization
    *
    * @reutn mixed
    */
   public function getOrganizationId();

   /**
    * Returns the parent organization if one exists
    *
    * @return MindFrame2_Authorization_OrganizationInterface or NULL
    */
   public function getParentOrganization();

   /**
    * Sets the parent organization
    *
    * @param MindFrame2_Authorization_OrganizationInterface $parent_organization
    * Parent Organization
    *
    * @return void
    */
   public function setParentOrganization(
      MindFrame2_Authorization_OrganizationInterface $organization);
}
