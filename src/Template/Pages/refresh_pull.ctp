<?php


use Cake\Error\Debugger;

$log = "pull.txt";
$file = fopen($log,"a");

fwrite($file,"---------------------------------------------------------------------------\n");
fwrite($file,"bbaabbaaaaa");
fwrite($file,"---------------------------------------------------------------------------\n");

//debug($_POST);
echo Debugger::exportVar($_POST, 25);

?>
