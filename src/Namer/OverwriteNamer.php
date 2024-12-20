<?php
/**
 * Created by PhpStorm.
 * User: mountin
 * Date: 12/19/24
 * Time: 2:49 PM
 */
namespace App\Namer;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class OverwriteNamer implements NamerInterface
{
    public function name($object, PropertyMapping $mapping): string
    {
        // Use the original file name to allow overwriting
        return $mapping->getFile($object)->getClientOriginalName();
    }
}
