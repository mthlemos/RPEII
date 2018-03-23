<?php
//$horas = $_REQUEST['hr'];
//$minutos = $_REQUEST['min'];
//$segundos = $_REQUEST['sec'];
$tempo = $_REQUEST['tempo'];
$tempo = explode(":", $tempo);

$hr = $tempo[0]*60*60*1000;
$min = $tempo[1]*60*1000;
$sec = $tempo[2]*1000;

$milli = $hr+$min+$sec;

$fp = fopen('tempo.txt', 'w');
fwrite($fp, $milli);
fclose($fp);

echo "Set";








?>