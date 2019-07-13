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

    private $topLayerEnable = false;
    private $topLayerImages;
    private $topLayerAdditive = false;

    private $backgroundEnable = false;
    private $backgroundImages;

    private $backgroundColorR = 0;
    private $backgroundColorG = 0;
    private $backgroundColorB = 0;

    private $randomEmptyChange;

    /**
     * RandomBlock constructor.
     * @param $blockFolder
     */
    public function __construct($blockFolder)
    {
        $this->blockFolder = $blockFolder;
    }

    public function fillTop($images, $additive = false) {
        $this->topLayerEnable = true;
        $this->topLayerImages = $images;
        $this->topLayerAdditive = $additive;
    }

    public function setBackground($images) {
        $this->backgroundEnable = true;
        $this->backgroundImages = $images;
    }

    public function setBackgroundColor($r, $g, $b) {
        $this->backgroundColorR = $r;
        $this->backgroundColorG = $g;
        $this->backgroundColorB = $b;
    }

    public function setRandomEmptyChance($chance) {
        $this->randomEmptyChange = $chance;
    }

    public function renderImage($images, $width = 5, $height = 5, $scale = 1) {

        $imageHandlers = array();
        $topLayerImageHandlers = array();
        $backgroundImageHandlers = array();

        foreach ($images as $image) {
            $files = glob($this->blockFolder . $image);
            foreach ($files as $file) {
                array_push($imageHandlers, imagecreatefrompng($file));
            }
        }

        if ($this->backgroundEnable) {
            foreach ($this->backgroundImages as $image) {
                $files = glob($this->blockFolder . $image);
                foreach ($files as $file) {
                    array_push($backgroundImageHandlers, imagecreatefrompng($file));
                }
            }
        }

        if ($this->topLayerEnable) {
            foreach ($this->topLayerImages as $image) {
                $files = glob($this->blockFolder . $image);
                foreach ($files as $file) {
                    array_push($topLayerImageHandlers, imagecreatefrompng($file));
                }
            }
        }

        $loadedBlockMax = sizeof($imageHandlers)-1;
        $topLayerloadedBlockMax = sizeof($topLayerImageHandlers)-1;
        $backgroundloadedBlockMax = sizeof($backgroundImageHandlers)-1;
        $blockDimensions = imagesx($imageHandlers[0]); // getting dimensions from first image.

        if ($this->topLayerEnable && $this->topLayerAdditive) {
            $height++;
        }

        $canvas = imagecreatetruecolor($blockDimensions * $width, $blockDimensions * $height);
        $alpha_channel = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagecolortransparent($canvas, $alpha_channel);

        imagefill($canvas, 0,0, imagecolorallocate ($canvas, $this->backgroundColorR, $this->backgroundColorG, $this->backgroundColorB));

        if ($this->backgroundEnable) {
            for ($x = 0; $x<$width; $x++) {
                for ($y = 0; $y<$height; $y++) {

                        imagecopy(
                            $canvas,
                            $backgroundImageHandlers[rand(0, $backgroundloadedBlockMax)],
                            $x*$blockDimensions,
                            $y*$blockDimensions,
                            0,
                            0,
                            $blockDimensions,
                            $blockDimensions
                        );

                }
            }
        }


        for ($x = 0; $x<$width; $x++) {
            for ($y = 0; $y<$height; $y++) {

                if (isset($this->randomEmptyChange)) {
                    if (rand(0, $this->randomEmptyChange) == 0) {
                        continue;
                    }
                }

                if ($y == 0 && $this->topLayerEnable) {
                    imagecopy(
                        $canvas,
                        $topLayerImageHandlers[rand(0, $topLayerloadedBlockMax)],
                        $x*$blockDimensions,
                        $y*$blockDimensions,
                        0,
                        0,
                        $blockDimensions,
                        $blockDimensions
                    );
                } else {
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