<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boom</title>
</head>
<body>
    <pre style="font-size: 10px;"><?php
    function branchout($treegrid, $settings, $x, $y, $depth){
        if(++$depth > $settings["maxdepth"])
            return $treegrid;
        $splitdirections = [
            ["x"=>0,"y"=>-1],
            ["x"=>1,"y"=>-1],
            ["x"=>-1,"y"=>-1],
            ["x"=>1,"y"=>0],
            ["x"=>-1,"y"=>0],
            ["x"=>1,"y"=>1],
            ["x"=>-1,"y"=>1],
            ["x"=>0,"y"=>1]
        ];
        $splitcount = 0;
        $failsplit = false;
        while(!$failsplit){
            if(random_int(0, 100) <= $settings["splitchance"]){
                $splitcount++;
            }
            else{
                $failsplit = true;
            }
        }
        for($i=0;$i < $splitcount; $i++){
            $splitdirection = $splitdirections[rand(0, sizeof($splitdirections) -1)];
            for($i2 = 0; $i2 < rand($settings["minlenght"], $settings["maxlenght"]); $i2++){
                //$treegrid[$splitpointy + ($i * $splitdirection['y'])][$splitpointx + ($i * $splitdirection['x'])] = "0";
                $treegrid = setspot($treegrid, $x + ($i2 * $splitdirection['x']), $y + ($i2 * $splitdirection['y']), "0", $settings);
                $treegrid = branchout($treegrid, $settings, $x + ($i2 * $splitdirection['x']), $y + ($i2 * $splitdirection['y']), $depth);
            }
        }
        return $treegrid;
    }
    function setspot($treegrid, $x, $y, $char, $settings){
        if($x >= 0 && $x < $settings['width'] && $y >= 0 && $y < $settings['height'])
            $treegrid[$y][$x] = $char;
        return $treegrid;
    }
    function treegen($treegrid, $settings){
        $centerx = $settings['startx'];
        $centery = $settings['starty'];
        
        $currentlocx = $centerx;
        $currentlocy = $centery;
        
        for($currentlocy; $currentlocy >= $centery - $settings['splitheight']; --$currentlocy){
            $treegrid[$currentlocy][$currentlocx] = "0";
        }
        $splitpointx = $currentlocx;
        $splitpointy = $currentlocy;
        $splitdirections = [
            ["x"=>0,"y"=>-1],
            ["x"=>1,"y"=>-1],
            ["x"=>-1,"y"=>-1],
            ["x"=>1,"y"=>0],
            ["x"=>-1,"y"=>0],
            ["x"=>1,"y"=>1],
            ["x"=>-1,"y"=>1],
            ["x"=>0,"y"=>1]
        ];
        for($i=0;$i < $settings['initialsplits']; $i++){
            $splitdirection = $splitdirections[rand(0, sizeof($splitdirections) -1)];
            for($i2 = 0; $i2 < rand(3, 10); $i2++){
                //$treegrid[$splitpointy + ($i * $splitdirection['y'])][$splitpointx + ($i * $splitdirection['x'])] = "0";
                $treegrid = setspot($treegrid, $splitpointx + ($i2 * $splitdirection['x']), $splitpointy + ($i2 * $splitdirection['y']), "0", $settings);
                $treegrid = branchout($treegrid, $settings, $splitpointx + ($i2 * $splitdirection['x']), $splitpointy + ($i2 * $splitdirection['y']), 0);
            }
        }

        return $treegrid;
    }

    $treetext = "";
    $settings = [
        "width"=>100,//the width of the resulting text
        "height"=>60,//the height of the resulting text
        "startx"=>50,//the tree's starting point x (left to right)
        "starty"=>59,//the tree's starting point y (top to bottom)
        "splitheight"=>20,//the height at which the tree will make it's first split
        "initialsplits"=>10,//the amount of branches the first split will make
        "splitchance"=>20,//the chance in % of all hte next splits
        "minlenght"=>3,//the minimum lenght of a branch
        "maxlenght"=>5,//the maximum lenght of a branch
        "maxdepth"=>10//the maximum amount of recursions (putting this too high can cause a memory error)
    ];

    $treegrid = [];
    for($y = 0; $y < $settings['height']; $y++){//create full empty grid with space characters
        for($x = 0; $x < $settings['width']; $x++){
            $treegrid[$y][$x] = " ";
        }
    }

    $treegrid = treegen($treegrid, $settings);//generate the tree and get the result

    foreach($treegrid as $vertical){//convert the grid into a string (the order doesn't change)
        foreach($vertical as $character){
            $treetext .= $character;
        }
        $treetext .= "\n";
    }
    
    echo $treetext;//add the resulting tree to the webpage
    ?></pre>
</body>
</html>
