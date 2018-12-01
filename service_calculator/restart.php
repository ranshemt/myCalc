<?php if(session_status() != PHP_SESSION_ACTIVE){session_start();}
include 'myCalc.php';

$mySessionCalculator = unserialize($_SESSION['calc']);
$mySessionCalculator->resetCalc();
echo "restarted";
$_SESSION['calc'] = serialize($mySessionCalculator);   