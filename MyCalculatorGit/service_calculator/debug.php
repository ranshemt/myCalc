<?php if(session_status() != PHP_SESSION_ACTIVE){session_start();}
include 'myCalc.php';
$mySessionCalculator = unserialize($_SESSION['calc']);
echo $mySessionCalculator;