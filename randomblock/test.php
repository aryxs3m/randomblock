<?php
/**
 * Created by PhpStorm.
 * User: aryx
 * Date: 2019.07.13.
 * Time: 15:27
 */

include_once "RandomBlock.php";

use Totoro\Modules\RandomBlock;

$rblock = new RandomBlock("block/");
$im = $rblock->renderImage(

    array(                      // block textures, you can use * wildcard
        "brick_*"
    ),
    10, 10,                     // rows/columns
    2                           // 2x scale output
);


header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);