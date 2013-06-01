<?php
namespace Sphax\SpriteHmiBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Sphax\SpriteBundle\Exception\DirectoryException;

/**
 * Upload d'image
 *
 * @author julien besnard
 */
class UploadService
{
	/**
     * upload
     *
     * @param array $requestFile
     * @param string $dir
     * @access public
     * @return mixed
    */
    public function upload($dir, $requestFile) {

    	$typeAccepted = array("image/jpeg", "image/gif", "image/png");

        if (self::dirExists($dir) === true) {
            // upload files
            $i = 0;
            foreach ($requestFile as $key => $value)  {
                if (in_array($value->getMimeType(), $typeAccepted)) {
                    $value->move($dir, $value->getClientOriginalName());
                } else {
                    echo "ce fichier n'est pas une image";
                }
                $i++;
            }
        }

        return true;
    }

	/**
     * dirExists
     *
     * @param string $dir
     * @access private
     * @return mixed
    */
    private function dirExists($dir) {
        $fs = new Filesystem();
        try {
            if ($fs->exists($dir) === false) {
                $fs->mkdir($dir);
            }   
        } catch (DirectoryException $e) {
            printf("Unable to create directory: %s", $e->getMessage());
        }
    	
        return true;
    }




}