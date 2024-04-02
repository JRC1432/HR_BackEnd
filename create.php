<?php 
require("datacon.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';



// Verification


if(isset($_GET['verify'])){

   
$vemail = $_POST["emailaddress"];
$verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jrcastanares@sei.dost.gov.ph';
    $mail->Password = '@cc3$$D3n!3d1432';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;


    $mail->setFrom('jrcastanares@sei.dost.gov.ph');
    $mail->addAddress($_POST["emailaddress"]);

    

    $mail->isHTML(true);

    $mail->Subject = "DOST-SEI VERIFICATION_CODE";
    $mail->Body = "<p>Your Verification Code is:</p> <br>". $verification_code;

    $mail->send();



$stnt = $pdo->prepare("INSERT INTO verify(verify_email,verify_code) VALUES (?,?)");
$params = array($vemail,$verification_code);
$stnt -> execute($params);

 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);


} 


// Registration


if(isset($_GET['register'])){

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $remail = $_POST["remail"];
    $passwords = $_POST["passwords"];
    $passwordsHash = password_hash($passwords, PASSWORD_DEFAULT);

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jrcastanares@sei.dost.gov.ph';
    $mail->Password = '@cc3$$D3n!3d1432';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;


    $mail->setFrom('jrcastanares@sei.dost.gov.ph');
    $mail->addAddress($_POST["remail"]);

    

    $mail->isHTML(true);

    $mail->Subject = "Registration Sucessful";
    $mail->Body = "Hi ". $fname . "<p> Greetings from DOST-SEI HR Department. We would like to inform you that you are now successfully registered on our system.</p>";

    $mail->send();


$stnt = $pdo->prepare("INSERT INTO auth(first_name,last_name,email,password) VALUES (?,?,?,?)");
$params = array($fname,$lname,$remail,$passwordsHash);
$stnt -> execute($params);

 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);


} 




//Create A Job

if(isset($_GET['createjob'])){

$salid = $_POST["salarygrade"];
$job = $_POST["job"];
$plantilla = $_POST["plantilla"];
$stats = $_POST["group5"];
$area = $_POST["group3"];
$eligibility = $_POST["group2"];
$educ = $_POST["group4"];
$yrsexp = $_POST["yrsexp"];
$exp = $_POST["exp"];
$hrstraining = $_POST["hrstraining"];
$training = $_POST["training"];
$postopt = $_POST["postopt"];
$enddate = $_POST["date"];

$duties = json_decode($_POST['duties'], true);
$competency = json_decode($_POST['ccompetencies'], true);
$errors = array();


$pdo->beginTransaction();
$stnt = $pdo->prepare("INSERT INTO job(salary_id,jobtitle,plantilla,stats,area,eligibility,educ,yrsexp,exp,hrstraining,training,postopt,enddate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING job_id");
$dstnt = $pdo->prepare("INSERT INTO duties(job_id,description) VALUES (?,?)");
$cstnt = $pdo->prepare("INSERT INTO competencies(job_id,description) VALUES (?,?)");
$params = array($salid,$job,$plantilla,$stats,$area,$eligibility,$educ,$yrsexp,$exp,$hrstraining,$training,$postopt,$enddate);



$stnt -> execute($params);
 if($stnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }




    $jid = "";
    try{

        $result = $stnt->fetch();
        $jid = $result["job_id"];
    }catch(Exception $e){
        echo $e;

    }
    
foreach ($duties as $key => $value) {
    $dparams = array($jid,$value["content"]);
    $dstnt -> execute($dparams);


 if($dstnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }

}


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
       $pdo->rollback();
    } else{
    echo "true";
    $pdo->commit();
    }

}




// Create Applicant

if(isset($_GET['applicant'])){

    $jobid = $_POST["jobsname"];
    $authid = $_POST["authname"];
    $regjob = $_POST["regjob"];
    $regnon = $_POST["nonreq"];
    $regfirstname = $_POST["regfirstname"];
    $reglastname = $_POST["reglastname"];
    $regmidname = $_POST["regmidname"];
    $regdate = $_POST["date"];
    $reggender = $_POST["gender"];
    $regcontact = $_POST["contact"];
    $regemail = $_POST["regemail"];
    $reglicense = $_POST["license"];


    $regfeedback = $_POST["feedback"];
    $regvacgroup = $_POST["vacgroup"];
    $regquality = $_POST["quality"];


// CV
$regcv = $_FILES['cv']['name'];
$pathcv = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regcv, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathcv)){

            mkdir($pathcv, 0775, true);
            }

            $temp_file = $_FILES['cv']['tmp_name'];

            if($temp_file !=""){
                $newcv = $pathcv."/cv.".$extension;

                if(move_uploaded_file($temp_file,$newcv)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    } 


// PDS

    $regpds = $_FILES['pds']['name'];
     $pathpds = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regpds, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathpds)){

            mkdir($pathpds, 0775, true);
            }

            $temp_file = $_FILES['pds']['tmp_name'];

            if($temp_file !=""){
                $newpds = $pathpds."/pds.".$extension;

                if(move_uploaded_file($temp_file,$newpds)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    }



// WES

    $regwes = $_FILES['wes']['name'];
     $pathwes = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regwes, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathwes)){

            mkdir($pathwes, 0775, true);
            }

            $temp_file = $_FILES['wes']['tmp_name'];

            if($temp_file !=""){
                $newwes = $pathwes."/wes.".$extension;

                if(move_uploaded_file($temp_file,$newwes)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    } 


// Diploma

$regdiploma = $_FILES['diploma']['name'];
     $pathdiploma = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regdiploma, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathdiploma)){

            mkdir($pathdiploma, 0775, true);
            }

            $temp_file = $_FILES['diploma']['tmp_name'];

            if($temp_file !=""){
                $newdiploma = $pathdiploma."/diploma.".$extension;

                if(move_uploaded_file($temp_file,$newdiploma)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    }

// TOR

    $regtor = $_FILES['tor']['name'];
     $pathtor = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regtor, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathtor)){

            mkdir($pathtor, 0775, true);
            }

            $temp_file = $_FILES['tor']['tmp_name'];

            if($temp_file !=""){
                $newtor = $pathtor."/tor.".$extension;

                if(move_uploaded_file($temp_file,$newtor)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    } 


// Eligibility

$regeligibility = $_FILES['eligibility']['name'];
     $patheligibility = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regeligibility, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($patheligibility)){

            mkdir($patheligibility, 0775, true);
            }

            $temp_file = $_FILES['eligibility']['tmp_name'];

            if($temp_file !=""){
                $neweligibility = $patheligibility."/eligibility.".$extension;

                if(move_uploaded_file($temp_file,$neweligibility)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    } 


$regapplet = $_FILES['appletter']['name'];
     $pathapplet = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regapplet, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathapplet)){

            mkdir($pathapplet, 0775, true);
            }

            $temp_file = $_FILES['appletter']['tmp_name'];

            if($temp_file !=""){
                $newapplet = $pathapplet."/appletter.".$extension;

                if(move_uploaded_file($temp_file,$newapplet)){
                    echo "";
                } else{
                    echo "failed";
                }
            }

    }else{
        echo "Failed";
    } 


$regtraincert = $_FILES['traincert']['name'];
     $pathtraincert = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regtraincert, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathtraincert)){

            mkdir($pathtraincert, 0775, true);
            }

            $temp_file = $_FILES['traincert']['tmp_name'];

            if($temp_file !=""){
                $newtraincert = $pathtraincert."/traincert.".$extension;

                if(move_uploaded_file($temp_file,$newtraincert)){
                    echo "";
                } else{
                    echo "";
                }
            }

    }else{
        echo "";
    } 

    $regper = $_FILES['per']['name'];
     $pathper = 'upload/'.$authid.$reglastname;
     $allowed_extensions = array('doc','docx','pdf');
      $extension = pathinfo($regper, PATHINFO_EXTENSION);
       if(in_array(strtolower($extension),$allowed_extensions) ) {

        if(!file_exists($pathper)){

            mkdir($pathper, 0775, true);
            }

            $temp_file = $_FILES['per']['tmp_name'];

            if($temp_file !=""){
                $newper = $pathper."/per.".$extension;

                if(move_uploaded_file($temp_file,$newper)){
                    echo "";
                } else{
                    echo "";
                }
            }

    }else{
        echo "";
    } 


    
    $pdo->beginTransaction();
    $stnt = $pdo->prepare("INSERT INTO registration(job_id,auth_id,regjob,regfirstname,reglastname,regmidname,regdate,gender,contact,regemail,license) VALUES (?,?,?,?,?,?,?,?,?,?,?) RETURNING reg_id");
    $stntfiles = $pdo->prepare("INSERT INTO documents(reg_id,cv,pds,wes,diploma,tor,eligibility,appletter,traincert,per) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stntfeed = $pdo->prepare("INSERT INTO feedback(reg_id,description,vacancy,star) VALUES (?,?,?,?)");




if($regnon == "None Required"){

    $params = array($jobid,$authid,$regjob,$regfirstname,$reglastname,$regmidname,$regdate,$reggender,$regcontact,$regemail,$reglicense);
$stnt -> execute($params);
    if($stnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }


    $rid = "";
    try{

        $result = $stnt->fetch();
        $rid = $result["reg_id"];
    }catch(Exception $e){
        echo $e;

    }



    if(empty($_FILES['traincert']['tmp_name']) && empty($_FILES['per']['tmp_name']) ){
        $newtraincert = "No file Uploaded";
        $newper = "No file Uploaded";
    }

    $fparams = array($rid,$newcv,$newpds,$newwes,$newdiploma,$newtor,$neweligibility,$newapplet,$newtraincert,$newper);
    $stntfiles -> execute($fparams);
    if($stntfiles){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }




 $fdparams = array($rid,$regfeedback,$regvacgroup,$regquality);
     $stntfeed -> execute($fdparams);
    if($stntfeed){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }



     if(in_array(false, $errors)){
       echo "false";
       $pdo->rollback();
    } else{
    echo "true";
    $pdo->commit();
    }

}else{
$params = array($jobid,$authid,$regjob,$regfirstname,$reglastname,$regmidname,$regdate,$reggender,$regcontact,$regemail,$reglicense);
$stnt -> execute($params);
    if($stnt){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }


    $rid = "";
    try{

        $result = $stnt->fetch();
        $rid = $result["reg_id"];
    }catch(Exception $e){
        echo $e;

    }


    if(empty($_FILES['traincert']['tmp_name']) && empty($_FILES['per']['tmp_name']) ){
        $errors[] = false;
    }
    $fparams = array($rid,$newcv,$newpds,$newwes,$newdiploma,$newtor,$neweligibility,$newapplet,$newtraincert,$newper);
    $stntfiles -> execute($fparams);
    if($stntfiles){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }




 $fdparams = array($rid,$regfeedback,$regvacgroup,$regquality);
     $stntfeed -> execute($fdparams);
    if($stntfeed){
        $errors[] =  true;
    } else{
        
        $errors[] = false;
    }



     if(in_array(false, $errors)){
       echo "false";
       $pdo->rollback();
    } else{
    echo "true";
    $pdo->commit();
    }
}



}




// Event upload

if(isset($_GET['event'])){

$appid = $_POST["idonly"];
$eventname = $_POST["eventtitle"];
$eventfeed = $_POST["feedback"];

$mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jrcastanares@sei.dost.gov.ph';
    $mail->Password = '@cc3$$D3n!3d1432';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;


    $mail->setFrom('jrcastanares@sei.dost.gov.ph');
    $mail->addAddress($_POST["email"]);

    

    $mail->isHTML(true);

    $mail->Subject = $eventname;
    $mail->Body = $eventfeed;

    $mail->send();


$stnt = $pdo->prepare("INSERT INTO status(auth_id,event_title,event_desc) VALUES (?,?,?)");
$params = array($appid,$eventname,$eventfeed);
$stnt -> execute($params);

 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}




?>