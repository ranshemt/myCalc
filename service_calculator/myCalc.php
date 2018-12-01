<?php if(session_status() != PHP_SESSION_ACTIVE){session_start();}
    function compute($x, $func, $y){
        if($func == "+")
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
            if($currElem == "+" || $currElem == "-" || $currElem == "*" || $currElem == "/"){
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
        if($lastFunc == "+"){
            $oppFunc = "-";
        }
        if($lastFunc == "-"){
            $oppFunc = "+";
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
        private $id, $total, $funcs, $nums, $res, $curr, $currID, $prevID;
        private $newNumFlag, $newFuncFlag, $isRes, $manyDigits, $results;
        //constructor
        public function __construct($newId){
            //static - keep value for all session
            $this->id=$newId;
            $this->total=array();
            $this->funcs=array();
            $this->nums=array();
            $this->results=array();
            $this->newNumFlag=0;
            $this->newFuncFlag=0;
            $this->isRes=0;
            $this->manyDigits = 0;
            $this->currID = 000;
            $this->prevID = 000;
            //normal - no real need to keep value for all session
            $this->res=0;
            $this->curr="";
        }
        public function __toString(){
            $str = "<br>Calculator id: " . $this->id . "<br>" . "Total: " . implode(",", $this->total) . "<br>" . "Nums: " . implode(",", $this->nums) . "<br>" . "Functions: " . implode(",", $this->funcs) . "<br>" . "Result: " . $this->res . " Current parameter: " . $this->curr . "<br>" . "newNumFlag: " . $this->newNumFlag . " newFuncFlag: " . $this->newFuncFlag . " isRes: " . $this->isRes . "<br>Results: " . implode(",", $this->results) . "<br>" . "<br>";
            return $str;
        }
        //methods
        public function resetCalc(){
            $tmp = $this->id;
            $this->id = $tmp + 1;
            $this->total=array();
            $this->funcs=array();
            $this->nums=array();
            $this->results=array();
            $this->res=0;
            $this->curr="";
            $this->newNumFlag=0;
            $this->newFuncFlag=0;
            $this->isRes=0;
            $this->manyDigits = 0;
            $this->currID = 000;
            $this->prevID = 000;
        }
        //
        public function CALCULATE()
        {
            //$resultStr = "";
            $msgReply = array("id", "Res", "rVal", "lVal", "Mult", "msg", "idAfter", "totArr", "LVAL", "RVAL", "printPattern", "lastFunc");
            //add new parameter
            $this->curr = $_REQUEST['par'];
            array_push($this->total, $this->curr);
            $msgReply["totArr"] = $this->total;
            if($this->curr == "+" || $this->curr == "-" || $this->curr == "*" || $this->curr == "/"){
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
                $this->currID = "000";
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
                $this->currID = "001";
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
                $this->currID = "010";
                //$resultStr = end($this->funcs);
                //msg reply
                $msgReply["Res"] = intval($this->res);  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = "first input is func"; $msgReply["id"] = "010";
                $msgReply["idAfter"] = "010";
            }
            //011
            //wait for new number
            if($this->newNumFlag === 0 && $this->newFuncFlag === 1 && $this->isRes === 1){
                $this->currID = "011";
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
                $this->currID = "100";
                $this->newNumFlag = 0;
                $this->isRes = 1;
                $this->res = intval(end($this->nums));
                array_push($this->results, intval($this->res));
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
                $this->currID = "101";
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
                    //array_push($this->results, intval($this->res));
                }
                if(($this->prevID != "101" && $this->currID == "101") || ($this->prevID == "101" && $this->currID == "101")){
                    array_pop($this->results);
                    array_push($this->results, intval($this->res));
                } else{
                    array_push($this->results, intval($this->res));
                }
                $this->newNumFlag = 0;
                //msg reply
                $msgReply["lVal"] = $lVal;
                $msgReply["rVal"] = $rVal;
                $msgReply["Mult"] = "";
                $msgReply["msg"] = "";
                $msgReply["Res"] = $this->res;
                $msgReply["idAfter"] = "001";
            }
            //110
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 1 && $this->isRes === 0){
                $this->currID = "110";
                $this->res = compute(0, end($this->funcs), intval($this->res));
                array_push($this->results, intval($this->res));
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
                $this->currID = "111";
                $this->res = compute(intval($this->res), end($this->funcs), intval(end($this->nums)));
                array_push($this->results, intval($this->res));
                $this->newNumFlag=0;
                $this->newFuncFlag=0;
                //$resultStr = intval($this->res);
                //msg reply
                $msgReply["Res"] = $this->res;  $msgReply["rVal"] = "";
                $msgReply["lVal"] = ""; $msgReply["Mult"] = "";
                $msgReply["msg"] = ""; $msgReply["id"] = "111";
                $msgReply["idAfter"] = "001";
            }
            //
            //update prevID
            $this->prevID = $this->currID;
            //
            //LVAL & RVAL
            if(count($this->results) == 0){
                $msgReply["LVAL"] = 0;
                $msgReply["RVAL"] = 0;
            }
            if(count($this->results) == 1){
                $lastRes = array_pop($this->results);
                $msgReply["LVAL"] = $lastRes;
                $msgReply["RVAL"] = 0;
                array_push($this->results, $lastRes);
            }
            if(count($this->results) >= 2){
                $lastRes = array_pop($this->results);
                $beforeLast = array_pop($this->results);
                $msgReply["LVAL"] = $beforeLast;
                $totCpy = $this->total;
                $msgReply["RVAL"] = getR_Val($totCpy, 0);
                array_push($this->results, $beforeLast);
                array_push($this->results, $lastRes);
            }
            if($this->currID == "011"){
                $msgReply["LVAL"] = $this->res;
            }
            if($this->currID == "011" || $this->currID == "100"){
                $msgReply["printPattern"] = "short";
            } else{
                $msgReply["printPattern"] = "full";
            }
            
            if(count($this->funcs) == 0){
                $msgReply["lastFunc"] = "&";
                $msgReply["printPattern"] = "short";
            } else{
                $msgReply["lastFunc"] = array_pop($this->funcs);
                array_push($this->funcs, $msgReply["lastFunc"]);
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
