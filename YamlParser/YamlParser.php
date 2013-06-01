<?php
namespace Sphax\SpriteHmiBundle\YamlParser;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * YamlParser
 *
 * @author julien besnard
 */
class YamlParser
{
	/**
     * createSprite
     *
     * @param object $sprite
     * @param string $configFile
     * @access public
     * @return boolean
    */
    public function createSprite($configFile, $sprite) {

    	/*
         * lecture du fichier yaml
         */ 
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents($configFile));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        if (array_key_exists('sphax_sprite', $value)) {
            if (array_key_exists('sprite', $value['sphax_sprite'])) {
                $dumper = new Dumper();
                $arrayOptions = array();

                foreach ($sprite['options'] as $keyOptions => $valueOptions) {
                    $arrayOptions[$keyOptions] = $valueOptions;
                }

                
                $newSprite = array( 
                    'sourceSpriteImage' => $sprite['sourceSpriteImage'],
                    'outputSpriteImage' => $sprite['outputSpriteImage'],
                    'options' => $arrayOptions
                );
                
                $value['sphax_sprite']['sprite'][$sprite['name']] = $newSprite;

                $newConf = $dumper->dump($value, 5);

                file_put_contents($configFile, $newConf);
            }
        }
        return true;
    }

    /**
     * updateSprite
     *
     * @param object $sprite
     * @param string $configFile
     * @access public
     * @return boolean
    */
    public function updateSprite($configFile, $sprite) {
        /*
         * lecture du fichier yaml
         */ 
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents($configFile));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        if (array_key_exists('sphax_sprite', $value)) {
            if (array_key_exists('sprite', $value['sphax_sprite'])) {
                if (array_key_exists($sprite['name'], $value['sphax_sprite']['sprite'])) {
                    
                    unset($value['sphax_sprite']['sprite'][$sprite['name']]);
                    $dumper = new Dumper();
                    $arrayOptions = array();

                    foreach ($sprite['options'] as $keyOptions => $valueOptions) {
                        $arrayOptions[$keyOptions] = $valueOptions;
                    }
                    
                    $newSprite = array( 
                        'sourceSpriteImage' => $sprite['sourceSpriteImage'],
                        'outputSpriteImage' => $sprite['outputSpriteImage'],
                        'options' => $arrayOptions
                    );
                    
                    $value['sphax_sprite']['sprite'][$sprite['name']] = $newSprite;

                    $newConf = $dumper->dump($value, 5);

                    file_put_contents($configFile, $newConf);
                }
            }
        }
    	
        return true;
    }

    /**
     * deleteSprite
     *
     * @param object $sprite
     * @param string $configFile
     * @access public
     * @return boolean
    */
    public function deleteSprite($configFile, $sprite) {
        /*
         * lecture du fichier yaml
         */ 
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents($configFile));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

        if (array_key_exists('sphax_sprite', $value)) {
            if (array_key_exists('sprite', $value['sphax_sprite'])) {
                if (array_key_exists($sprite, $value['sphax_sprite']['sprite'])) {
                    
                    unset($value['sphax_sprite']['sprite'][$sprite]);
                    
                    $dumper = new Dumper();
                    $newConf = $dumper->dump($value, 5);
                    file_put_contents($configFile, $newConf);
                }
            }
        }
        
        return true;
    }


}