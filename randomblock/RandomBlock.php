<?php
/**
 * Created by PhpStorm.
 * User: aryx
 * Date: 2019.07.13.
 * Time: 15:24
 */

namespace Totoro\Modules;

class RandomBlock
{

    private $blockFolder;

    /**
     * RandomBlock constructor.
     * @param $blockFolder
     */
    public function __construct($blockFolder)
    {
        $this->blockFolder = $blockFolder;
    }

    public function renderImage($images, $width = 5, $height = 5, $scale = 1) {

        $imageHandlers = array();

        foreach ($images as $image) {
            $files = glob($this->blockFolder . $image . ".png");
            foreach ($files as $file) {
                array_push($imageHandlers, imagecreatefrompng($file));
            }
        }

        $loadedBlockMax = sizeof($imageHandlers)-1;
        $blockDimensions = imagesx($imageHandlers[0]); // getting dimensions from first image.

        $canvas = imagecreatetruecolor($blockDimensions * $width, $blockDimensions * $height);

        for ($x = 0; $x<$width; $x++) {
            for ($y = 0; $y<$height; $y++) {
                imagecopy(
                    $canvas,
                    $imageHandlers[rand(0, $loadedBlockMax)],
                    $x*$blockDimensions,
                    $y*$blockDimensions,
                    0,
                    0,
                    $blockDimensions,
                    $blockDimensions
                    );
            }
        }

        if ($scale > 1 || $scale < 1) {
            $canvas = imagescale(
                $canvas,
                $width * $blockDimensions * $scale,
                $height * $blockDimensions * $scale,
                IMG_NEAREST_NEIGHBOUR); // we want sharp edges, you know.. Minecraft :)
        }

        return $canvas;

    }


}