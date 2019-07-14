# randomblock
Minecraft texture pack tile renderer in PHP.

# What does this thing?
This thing renders a small randomized tile with the selected block textures, from your Minecraft texture pack. You can use it on your homepage as a background or... dunno.

Example output is like this:

![randomblock output](https://i.imgur.com/nNztUqc.png)

For this image i used the *chiseled_sandstone* textures from the John Smith Legacy texture pack.

# How to use?
Well, first extract your desired texture pack and copy *minecraft/textures/block/* folder to the folder of this script.
Now check the *test.php*. It should work. You need php-gd for this to work!

About the *renderImage* function:
```
$rblock->renderImage(
  array(
    "dirt.png",
    "sandstone*.png"
  ),
  10, 10,
  2
);
```

The first parameter is an array, that should contain all textures you want to use. You can use wildcards, so if you want to load
all sandstone textures, you should write "sandstone*.png". You can even load all textures with a simple asterisk, but please don't do that, it will be ugly.

Second two (optional) parameter is how many rows/columns you want. So if your textures are 32x32, getting a 10 row/10 column image will result in a 320x320 image. Default is 5 rows by 5 columns.

Last (optional) parameter used for scaling the image. Using 2 will result in a 2 times scaled image (in this example 640x640).

# fillTop function
If you want to create a dirty-grassy background, you probably want grass blocks at the top, and random things below. For this, you can use the *fillTop* function.

```
$rblock->fillTop(
    array(
        "grass_block_side.png"      // setting the first line to grass blocks
    )
);

$im = $rblock->renderImage(

    array(                      // block textures, you can use * wildcard
        "dirt*.png",
        "gravel*.png"
    ),
    10, 10,                     // rows/columns
    2                           // 2x scale output
);
```

Filltop example output:

![filltop example](https://i.imgur.com/qRnWBJQ.png)

The first parameter for *fillTop* is like the first parameter for *renderImage*. You can select your textures for it. The second (optional) parameter (additive) is a boolean, if you set it true, then the top layer will be added to your image as a new row (so if you set 10 rows in renderImage, it will be 11), if false, then the first row became the "top layer". Easy.

# setBackground and setBackgroundColor function
You may see, that there are some transparent textures in Minecraft. By default *randomblock* doesn't care about transparency, and gives you a clean and beloved black background. But what do you do, when - for example - you want some underwater-themed thing?
You need to set the background color for some watery - i preferred 38,92,255 - and set the background to the water texture. I added some watery things too.

```
$rblock->setBackgroundColor(38,92,255);
$rblock->setBackground(array("water_flow.png"));

$im = $rblock->renderImage(

    array(
        "brain_coral.png",
        "brain_coral_fan.png",

        "bubble_coral.png",
        "bubble_coral_fan.png",

        "fire_coral_fan.png",
        "fire_coral_fan.png",

        "tube_coral_fan.png",
        "tube_coral_fan.png",

        "horn_coral_fan.png",
        "horn_coral_fan.png",

        "sea_lantern.png",
        "kelp.png",
        "seagrass.png"
    ),
    10, 10,
    2
);
```

It looks like this:

![watery thingie](https://i.imgur.com/ga2b7Xt.png)


# setRandomEmptyChance - WTF?
If you look at the previous example, you may think this is too much. And yes, it is, so you need to get some free space in your watery thingie.
Then comes *setRandomEmptyChance* where you can set the upper limit of a random generator. Think it's like a dice, but you can set how many sides the dice have. If you roll 0 - yeah, the dice starts at zero, as every good dice - then you will have an empty block. If you set the dice to have 12 side, you will get a very small chance, to get empty space. If you set it to 0, you will have plenty :)
So, add 
```$rblock->setRandomEmptyChance(2);```
after the *setBackgroundColor* function calls, and voilá:

![random empty watery thingie](https://i.imgur.com/FzAnw1h.png)
