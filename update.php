<?php 
require("datacon.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';



// Update salary

if(isset($_GET['updateSalary'])){


$salid = $_POST["id"];
$salgrade = $_POST["salgrade"];
$amount = $_POST["amount"];
$amount2 = $_POST["amounttwo"];
$amount3 = $_POST["amounthree"];
$amount4 = $_POST["amountfour"];
$amount5 = $_POST["amountfive"];
$amount6 = $_POST["amountsix"];
$amount7 = $_POST["amountseven"];
$amount8 = $_POST["amounteight"];


$stnt = $pdo->prepare("UPDATE salary SET salgrade = ?, amount = ?, amounttwo = ?, amountthree = ?, amountfour = ?, amountfive = ?, amountsix = ?, amountseven = ?, amounteight = ? WHERE id = ?");
$stnt -> execute([$salgrade,$amount,$amount2,$amount3,$amount4,$amount5,$amount6,$amount7,$amount8,$salid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// update filled

if(isset($_GET['updateFilled'])){


$jobid = $_POST["job_id"];



$stnt = $pdo->prepare("UPDATE job SET postopt = false WHERE job_id = ?");
$stnt -> execute([$jobid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// update unfilled

if(isset($_GET['updateUnfilled'])){


$jobid = $_POST["job_id"];



$stnt = $pdo->prepare("UPDATE job SET postopt = true WHERE job_id = ?");
$stnt -> execute([$jobid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


// UPdate end date

if(isset($_GET['updateenddate'])){


$date = $_POST["date"];
$jobid = $_POST["job_id"];



$stnt = $pdo->prepare("UPDATE job SET enddate = ? WHERE job_id = ?");
$stnt -> execute([$date,$jobid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


// update job



if(isset($_GET['updatejobs'])){

$jobids = $_POST["jobidss"];

$salid = $_POST["upsalarygrade"];
$job = $_POST["upjob"];

$plantilla = $_POST["upplantilla"];
$stats = $_POST["upgroup5"];
$area = $_POST["upgroup3"];
$eligibility = $_POST["upgroup2"];
$educ = $_POST["upgroup4"];
$yrsexp = $_POST["model1"];
$exp = $_POST["group6"];
$hrstraining = $_POST["model2"];
$training = $_POST["group7"];
$postopt = $_POST["group8"];
$enddate = $_POST["date"];





// $pdo->beginTransaction();
$stnt = $pdo->prepare("UPDATE job SET salary_id = ?, jobtitle = ?, plantilla = ?, stats = ?,
area = ?, eligibility = ?, educ = ?, yrsexp = ?, exp = ?, hrstraining = ?,
training = ?, postopt = ?, enddate = ? WHERE job_id = ?");
$stnt -> execute([$salid,$job,$plantilla,$stats,$area,$eligibility,$educ,$yrsexp,$exp,$hrstraining,$training,$postopt,$enddate,$jobids]);

 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

echo json_encode($result);
 
}



// Update Competencies

if(isset($_GET['editcompetencies'])){



$jid = $_POST["jobidssss"];
$competency = json_decode($_POST['ccompetencies'], true);
$errors = array();



$cstnt = $pdo->prepare("INSERT INTO competencies(job_id,description) VALUES (?,?)");

foreach ($competency as $key => $value) {
    $cparams = array($jid,$value["name"]);
    $cstnt -> execute($cparams);

 if($cstnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }

}

 if(in_array(false, $errors)){
       echo "false";
      
    } else{
    echo "true";
   
    }
}



// Update Duty

if(isset($_GET['editduty'])){



$jid = $_POST["jobidssss"];
$duties = json_decode($_POST['duties'], true);
$errors = array();



$dstnt = $pdo->prepare("INSERT INTO duties(job_id,description) VALUES (?,?)");

foreach ($duties as $key => $value) {
    $dparams = array($jid,$value["content"]);
    $dstnt -> execute($dparams);


 if($dstnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }

}

 if(in_array(false, $errors)){
       echo "false";
      
    } else{
    echo "true";
   
    }
}



// Update Password

if(isset($_GET['passwordReset'])){


$emailreset = $_POST["emailreset"];
$pass = $_POST["passwords"];
$passwordsHash = password_hash($pass, PASSWORD_DEFAULT);

$mail = new PHPMailer(true);


    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jrcastanares@sei.dost.gov.ph';
    $mail->Password = '@cc3$$D3n!3d1432';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;


    $mail->setFrom('jrcastanares@sei.dost.gov.ph');
    $mail->addAddress($_POST["emailreset"]);

    

    $mail->isHTML(true);

    $mail->Subject = "DOST-SEI PASSWORD RESET";
    $mail->Body = "<p>Your Password has been reset.</p> <br> Email:    ". $emailreset;

    $mail->send();



$stnt = $pdo->prepare("UPDATE auth SET password = ? WHERE email = ?");
$stnt -> execute([$passwordsHash,$emailreset]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

































?>