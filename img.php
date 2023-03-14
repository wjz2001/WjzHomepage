<?php
$img_array = glob("img/background/*.{webp,jpg,png}",GLOB_BRACE); 
$img_array_json = json_encode($img_array);
echo "var img_array_json='$img_array_json';";
/*
$img = array_rand($img_array); 
$dz = $img_array[$img];
header("Location:".$dz);
*/
?> 