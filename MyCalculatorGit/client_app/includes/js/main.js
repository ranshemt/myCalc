function printFullDebugResponse(jsonParsed){
    var msgTxt = "<br>id: " + jsonParsed["id"] + " Result: " + jsonParsed["Res"];
    if(jsonParsed["id"] == "101"){
        //array("id", "Res", "rVal", "lVal", "Mult", "msg", "idAfter", "totArr");
        msgTxt += "<br>rVal: " + jsonParsed["rVal"] + " lVal: " + jsonParsed["lVal"] + " Mult: " + jsonParsed["Mult"];
    }
    msgTxt += "<br>msg: " + jsonParsed["msg"];
    msgTxt += " - idAfter: " + jsonParsed["idAfter"];
    msgTxt += "<br>-------<br>";
    var old = document.getElementById("myFullDebug").innerHTML;
    document.getElementById("myFullDebug").innerHTML = msgTxt + old;
}

function printHistory(totalArr){
    //array("id", "Res", "rVal", "lVal", "Mult", "msg", "idAfter", "totArr");
    var i;
    document.getElementById("history").innerHTML = "";
    for(i=0; i<totalArr.length; i++){
        if(totalArr[i] == "Plus" || totalArr[i] == "-" || totalArr[i] == "*" || totalArr[i] == "/"){
            document.getElementById("history").innerHTML += " " + totalArr[i] + " ";  
        } else{
            document.getElementById("history").innerHTML += totalArr[i];
        }
    }
}

function calculate(str){
    if(str.length == 0){
        document.getElementById("RES").innerHTML="";
        return;
    }
    //else
    var ajaxRequest = new XMLHttpRequest();
    ajaxRequest.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            //document.getElementById("RES").innerHTML=this.responseText;
            var resp = JSON.parse(this.responseText);
            //history
            //document.getElementById("history").innerHTML+=str+" ";  
            printHistory(resp["totArr"]);
            //result
            document.getElementById("RES").innerHTML = resp["Res"];
            //full debug
            printFullDebugResponse(resp);
        }
    };
    var root="/MyCalculatorGit/service_calculator/main.php";
    ajaxRequest.open("POST", root + "?par=" + str, true);
    ajaxRequest.send(null);
    //
    // debug print
    var ajaxRequest = new XMLHttpRequest();
    ajaxRequest.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("myDebug").innerHTML=this.responseText;
        }
    };
    var root="/MyCalculatorGit/service_calculator/debug.php";
    ajaxRequest.open("GET", root + "?par=" + str, true);
    ajaxRequest.send(null);
}
//              //
//              //
//  OPTION 1    //
//              //
//              //
function restart(){
    var ajaxRequest = new XMLHttpRequest();
    ajaxRequest.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("myDebug").innerHTML=this.responseText;
        }
    };
    var root="/MyCalculatorGit/service_calculator/restart.php";
    ajaxRequest.open("PUT", root + "?par=Y", true);
    ajaxRequest.send(null); 
    document.getElementById("history").innerHTML="";
    document.getElementById("RES").innerHTML="try me";
    document.getElementById("history").innerHTML=""; 
    document.getElementById("myFullDebug").innerHTML=""; 
}
//              //
//              //
//  OPTION 2    //
//              //
//              //
// function initSess(){
//     var ajaxRequest = new XMLHttpRequest();
//     ajaxRequest.onreadystatechange = function(){
//         if(this.readyState == 4 && this.status == 200){
//             document.getElementById("myDebug").innerHTML=this.responseText;
//         }
//     };
//     var root="/MyCalculator-Class/service_calculator/initSess.php";
//     ajaxRequest.open("GET", root + "?par=Y", true);
//     ajaxRequest.send(null);
// }
// function endSess(){
//     var ajaxRequest = new XMLHttpRequest();
//     ajaxRequest.onreadystatechange = function(){
//         if(this.readyState == 4 && this.status == 200){
//             document.getElementById("myDebug").innerHTML=this.responseText;
//         }
//     };
//     var root="/MyCalculator-Class/service_calculator/endSess.php";
//     ajaxRequest.open("GET", root + "?par=Y", true);
//     ajaxRequest.send(null);
// }
// function restart(){
//     endSess();
//     initSess();
//     document.getElementById("history").innerHTML="";  
// }
// windows.onload = function(){
//     restart();
// }
