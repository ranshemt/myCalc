<?php if(session_status() != PHP_SESSION_ACTIVE){session_start();}
    function compute($x, $func, $y){
        if($func == "Plus")
            return intval($x + $y);
        if($func == "-")
            return intval($x - $y);
        if($func == "*")
            return intval($x * $y);
        if($func == "/")
            return intval($x / $y);
    }
    //
    function getR_Val($totStr){
        $rightVal = 0;
        $tenthMult = 1;
        for(end($totStr); key($totStr)!==null; prev($totStr)){
            $currElem = current($totStr);
            //rVal extraction
            if($currElem == "Plus" || $currElem == "-" || $currElem == "*" || $currElem == "/"){
                break;
            }
            else{
                $rightVal = $rightVal + (intval($currElem) * $tenthMult);
                $tenthMult = $tenthMult * 10;
            }
        }
        return $rightVal;
    }
    //
    function getL_Val($totStr, $r, $lastFunc){
        $leftVal = 0;
        $yVal = -1;
        //$y = rVal / 10
        $yVal = intval(getR_Val($totStr) / 10);
        //lVal = calculate(result, oppsiteOf(lastFanc), $y)
        $oppFunc = "";
        if($lastFunc == "Plus"){
            $oppFunc = "-";
        }
        if($lastFunc == "-"){
            $oppFunc = "Plus";
        }
        if($lastFunc == "*"){
            $oppFunc = "/";
        }
        if($lastFunc == "/"){
            $oppFunc = "*";
        }
        
        $leftVal = compute(intval($r), $oppFunc, intval($yVal));
        return $leftVal;
    }
    /*
    MY CALCULATOR
    */
    //
    class Calculator {   
        //attributes
        private $id, $total, $funcs, $nums, $res, $curr, $newNumFlag, $newFuncFlag, $isRes, $manyDigits;
        //constructor
        public function __construct($newId){
            //static - keep value for all session
            $this->id=$newId;
            $this->total=array();
            $this->funcs=array();
            $this->nums=array();
            $this->newNumFlag=0;
            $this->newFuncFlag=0;
            $this->isRes=0;
            $this->manyDigits = 0;
            //normal - no real need to keep value for all session
            $this->res=0;
            $this->curr="";
        }
        public function __toString(){
            $str = "<br>Calculator id: " . $this->id . "<br>" . "Total: " . implode(",", $this->total) . "<br>" . "Nums: " . implode(",", $this->nums) . "<br>" . "Functions: " . implode(",", $this->funcs) . "<br>" . "Result: " . $this->res . " Current parameter: " . $this->curr . "<br>" . "newNumFlag: " . $this->newNumFlag . " newFuncFlag: " . $this->newFuncFlag . " isRes: " . $this->isRes . "<br>";
            return $str;
        }
        //methods
        public function resetCalc(){
            $tmp = $this->id;
            $this->id = $tmp + 1;
            $this->total=array();
            $this->funcs=array();
            $this->nums=array();
            $this->res=0;
            $this->curr="";
            $this->newNumFlag=0;
            $this->newFuncFlag=0;
            $this->isRes=0;
            $this->manyDigits = 0;
        }
        //
        public function CALCULATE()
        {
            //$resultStr = "";
            $msgReply = array("id", "Res", "rVal", "lVal", "Mult", "msg", "idAfter", "totArr");
            //add new parameter
            $this->curr = $_REQUEST['par'];
            array_push($this->total, $this->curr);
            $msgReply["totArr"] = $this->total;
            if($this->curr == "Plus" || $this->curr == "-" || $this->curr == "*" || $this->curr == "/"){
                array_push($this->funcs, $this->curr);
                $this->newFuncFlag=1;
            } else{
                array_push($this->nums, intval($this->curr));
                $this->newNumFlag=1;
            } 
            //
            //8 possible combinations
            //newNumFlag, newFuncFlag, isRes
            //
            //000
            //no input yet
            if($this->newNumFlag === 0 && $this->newFuncFlag === 0 && $this->isRes === 0){
                //$resultStr = "try me";
                //msg reply
                $msgReply["Res"] = "try me";  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = "nothing yet"; $msgReply["id"] = "000";
                $msgReply["idAfter"] = "000";
            }
            //001
            //echo result
            if($this->newNumFlag === 0 && $this->newFuncFlag === 0 && $this->isRes === 1){
                //$resultStr = $this->res;
                //msg reply
                $msgReply["Res"] = $this->res;  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = ""; $msgReply["id"] = "001";
                $msgReply["idAfter"] = "001";
            }
            //010
            //first input is func
            if($this->newNumFlag === 0 && $this->newFuncFlag === 1 && $this->isRes === 0){
                //$resultStr = end($this->funcs);
                //msg reply
                $msgReply["Res"] = end($this->res);  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = "first input is func"; $msgReply["id"] = "010";
                $msgReply["idAfter"] = "010";
            }
            //011
            //wait for new number
            if($this->newNumFlag === 0 && $this->newFuncFlag === 1 && $this->isRes === 1){
                //msg reply
                $msgReply["Res"] = intval($this->res) . end($this->funcs);
                $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = "wait for new num"; $msgReply["id"] = "011";
                $msgReply["idAfter"] = "011";
            }
            //100
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 0 && $this->isRes === 0){
                $this->newNumFlag = 0;
                $this->isRes = 1;
                $this->res = intval(end($this->nums));
                //$resultStr = $this->res;
                //msg reply
                $msgReply["Res"] = $this->res;  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = ""; $msgReply["id"] = "100";
                $msgReply["idAfter"] = "001";
            }
            //101
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 0 && $this->isRes === 1){
                //$resultStr = "101";
                $msgReply["id"] = "101";
            
                $totCpy = $this->total;
                $rVal = getR_Val($totCpy, 0);
                if(count($this->funcs) == 0){
                    $this->res = $rVal;
                    $lVal = -1;
                } else{
                    $lVal = getL_Val($this->total, intval($this->res), end($this->funcs));
                    $this->res = compute($lVal, end($this->funcs), $rVal);
                }
                $this->newNumFlag = 0;
                //msg reply
                $msgReply["lVal"] = $lVal;
                $msgReply["rVal"] = $rVal;
                $msgReply["Mult"] = "";
                $msgReply["msg"] = "";
                $msgReply["Res"] = $this->res;
                $msgReply["idAfter"] = "001";
               

                /*
                *
                *
                if(count($this->funcs) == 0){
                    $this->res = intval(($this->res * 10) + intval(end($this->nums)));
                    //$resultStr .= "no funcs";
                    //msg reply
                    $msgReply["msg"] = "no funcs ";
                }
                //
                if(count($this->funcs) >= 1){
                    $rightVal = 0;
                    $leftVal = 0;
                    //msg reply
                    $msgReply["msg"] = "complex number";
                    $tenthMult = 1;
                    for(end($this->total); key($this->total)!==null; prev($this->total)){
                        $currElem = current($this->total);
                        //$msgReply["msg"] .= "<br> performing currElem = current(this->total);  :  " . $currElem;
                        //lVal extraction
                        if($currElem == "Plus" || $currElem == "-" || $currElem == "*" || $currElem == "/"){
                            prev($this->total);
                            $leftVal = intval(current($this->total));
                            $msgReply["lVal"] = $leftVal;
                            break;
                        }
                        //rVal extraction
                        else{
                            $rightVal = $rightVal + (intval($currElem) * $tenthMult);
                            $tenthMult = $tenthMult * 10;
                            //$resultStr .= "rV:" . $rightVal . "M:" . $tenthMult;
                            //msg reply
                            $msgReply["rVal"] = $rightVal;
                            $msgReply["Mult"] = $tenthMult;
                        }
                    }
                    $this->res = compute($leftVal, end($this->funcs), $rightVal);
                }
                $this->newNumFlag = 0;
                //$resultStr .= "R:" . $this->res;
                //msg reply
                $msgReply["Res"] = $this->res;
                $msgReply["idAfter"] = "001";
                *
                *
                */
            }
            //110
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 1 && $this->isRes === 0){
                $this->res = compute(0, end($this->funcs), intval($this->res));
                $this->newNumFlag=0;
                $this->isRes = 1;
                //$resultStr = $this->res;
                //msg reply
                $msgReply["Res"] = $this->res;  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = ""; $msgReply["id"] = "110";
                $msgReply["idAfter"] = "011";
            }
            //111
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 1 && $this->isRes === 1){
                $this->res = compute(intval($this->res), end($this->funcs), intval(end($this->nums)));
                $this->newNumFlag=0;
                $this->newFuncFlag=0;
                //$resultStr = intval($this->res);
                //msg reply
                $msgReply["Res"] = $this->res;  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = ""; $msgReply["id"] = "111";
                $msgReply["idAfter"] = "001";
            }
            /*
            UPDATE calculator class in SESSION
            */
            $_SESSION['calc'] = serialize($this);  
            echo json_encode($msgReply);
            //return $resultStr;  
        }    
    }
?>
