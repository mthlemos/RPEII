<?php

$myfile = fopen("tempo.txt", "r") or die("Unable to open file!");
echo '{';
echo fread($myfile,filesize("tempo.txt"));
echo '}';
fclose($myfile);

$fp = fopen('tempo.txt', 'w');
fwrite($fp, 0);
fclose($fp);

?>