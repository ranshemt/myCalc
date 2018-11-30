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
    /*
    MY CALCULATOR
    */
    //
    class Calculator {   
        //attributes
        private $id, $total, $funcs, $nums, $res, $curr, $newNumFlag, $newFuncFlag, $isRes;
        //constructor
        public function __construct($newId){
            $this->id=$newId;
            $this->total=array();
            $this->funcs=array();
            $this->nums=array();
            $this->res=0;
            $this->curr="";
            $this->newNumFlag=0;
            $this->newFuncFlag=0;
            $this->isRes=0;
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
        }
        public function CALCULATE()
        {
            $resultStr = "";
            //add new parameter
            $this->curr = $_REQUEST['par'];
            array_push($this->total, $this->curr);
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
                //echo "try me";
                $resultStr = "try me";
                //echo "<br>000";
            }
            //001
            //echo result
            if($this->newNumFlag === 0 && $this->newFuncFlag === 0 && $this->isRes === 1){
                //echo $this->res;
                $resultStr = $this->res;
                //echo "<br>001";
            }
            //010
            //first input is func
            if($this->newNumFlag === 0 && $this->newFuncFlag === 1 && $this->isRes === 0){
                //echo end($this->funcs);
                $resultStr = end($this->funcs);
                //echo "<br>010";
            }
            //011
            //wait for new number
            if($this->newNumFlag === 0 && $this->newFuncFlag === 1 && $this->isRes === 1){
                //echo intval($this->res) . end($this->funcs);
                $resultStr = intval($this->res) . end($this->funcs);
                //echo "<br>011";
            }
            //100
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 0 && $this->isRes === 0){
                $this->newNumFlag = 0;
                $this->isRes = 1;
                $this->res = intval(end($this->nums));
                //echo $this->res;
                $resultStr = $this->res;
                //echo "<br>100";
            }
            //101
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 0 && $this->isRes === 1){
                $this->res = intval(($this->res * 10) + intval(end($this->nums)));
                $this->newNumFlag = 0;
                //echo $this->res;
                $resultStr = $this->res;
                //echo "<br>101";
            }
            //110
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 1 && $this->isRes === 0){
                $this->res = compute(0, end($this->funcs), intval($this->res));
                //echo $this->res;
                $resultStr = $this->res;
                $this->newNumFlag=0;
                $this->isRes = 1;
                //echo "<br>110";
            }
            //111
            //
            if($this->newNumFlag === 1 && $this->newFuncFlag === 1 && $this->isRes === 1){
                $this->res = compute(intval($this->res), end($this->funcs), intval(end($this->nums)));
                //echo intval($this->res);
                $resultStr = intval($this->res);
                $this->newNumFlag=0;
                $this->newFuncFlag=0;
                //echo "<br>111";
            }
            /*
            UPDATE calculator class in SESSION
            */
            $_SESSION['calc'] = serialize($this);  
            return $resultStr;  
        }    
    }
?>
