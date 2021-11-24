<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree</title>
    <style>
        body{
            background: black;
        }
        a{
            color: white;
        }

        @keyframes ornamentAnim {
            0%{
                filter: saturate(100%);
            }
            50%{
                filter: saturate(60%);
            }
            100%{
                filter: saturate(100%);
            }
        }
        .orn{
            animation-name: ornamentAnim;
            animation-duration: 1s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }
    </style>
</head>
<body>
    <pre style="font-size: 10px;"><?php
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
                $treegrid[$currentlocy][$currentlocx] = "<a style='color:brown;'>@<a>";
            }
            $splitpointx = $currentlocx;
            $splitpointy = $currentlocy;
            for ($i=0;$i<$settings['brancheslenght'];$i++){
                $partofbranch=$settings['brancheslenght']-$i;
                $evenpart=$i%2==0;
                $oneinafew=$i%5==0;
                for ($j=0;$j<2;$j++){
                    $lenght = ($partofbranch/2)+($evenpart?2:0)+($oneinafew?1:0);;//$partofbranch+$evenpart?2:0;
                    for ($k=0;$k<$lenght;$k++){
                        $x = $splitpointx + ($j%2==0?1:-1)*$k;
                        $y = $splitpointy - $i;
                        $treegrid = setspot($treegrid, $x, $y, "<a style='color:darkgreen;'>@<a>", $settings);
                    }

                }
            }

            //add ornaments
            $l=0;
            foreach ($treegrid as $line){
                $c=0;
                foreach ($line as $character){
                    $repl=rand(0, 100)<$settings['ornament%'];
                    if ($repl&&$character=="<a style='color:darkgreen;'>@<a>"){//if character is leaf
                        $orncols=["red","yellow"];$orncol=$orncols[rand(0,sizeof($orncols)-1)];
                        $animdelay = rand(0, 1000);
                        $treegrid=setspot($treegrid, $c, $l, "<a class='orn' style='color:$orncol;animation-delay: {$animdelay}ms'>@<a>", $settings);
                    }
                    $c++;
                }
                $l++;
            }

            return $treegrid;
        }

        $treetext = "<a style='color: red'>
  __  __                         _____ _          _     _                       
 |  \/  |                       / ____| |        (_)   | |                      
 | \  / | ___ _ __ _ __ _   _  | |    | |__  _ __ _ ___| |_ _ __ ___   __ _ ___ 
 | |\/| |/ _ \ '__| '__| | | | | |    | '_ \| '__| / __| __| '_ ` _ \ / _` / __|
 | |  | |  __/ |  | |  | |_| | | |____| | | | |  | \__ \ |_| | | | | | (_| \__ \
 |_|  |_|\___|_|  |_|   \__, |  \_____|_| |_|_|  |_|___/\__|_| |_| |_|\__,_|___/
                         __/ |                                                  
                        |___/                                                   

</a>";
        $settings = [
            "width"=>100,//the width of the resulting text
            "height"=>40,//the height of the resulting text
            "startx"=>50,//the tree's starting point x (left to right)
            "starty"=>39,//the tree's starting point y (top to bottom)
            "splitheight"=>5,//the height at which the tree will start showing needles
            "brancheslenght"=>30,//for how long should the tree be splitting
            "ornament%"=>10,//what percentage of leaves should be an ornament
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
