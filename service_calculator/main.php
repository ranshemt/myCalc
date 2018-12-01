<?php if(session_status() != PHP_SESSION_ACTIVE){session_start();}
include 'myCalc.php';
//          //
//  MAIN    //
//          //
//
//create session if not created
if(session_status() == PHP_SESSION_ACTIVE){
    //echo "sesstion did start";
    if(isset($_SESSION['calc'])){
        //echo "session started before";
    } else{
        //echo "session started now";
        $myCalc = new Calculator(0);
        $_SESSION['calc'] = serialize($myCalc);
    }
} else{
    //echo "session did NOT start";
}
//              //
//  calculation //
//              //
//
//get calculator class from session
$mySessionCalc = unserialize($_SESSION['calc']);
$strResult="";
$strResult = $mySessionCalc->CALCULATE();
echo $strResult;