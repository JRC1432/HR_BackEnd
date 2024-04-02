<?php 
require("datacon.php");






//Read Salary

if(isset($_GET['read'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM salary ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}
//Create A Salary

if(isset($_GET['create'])){

$amount = $_POST["amount"];
$salary = $_POST["salarygrade"];




$stnt = $pdo->prepare("INSERT INTO salary(salgrade,amount) VALUES (?,?)");
$params = array($salary,$amount);
$stnt -> execute($params);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// Populate Salary
if(isset($_GET['populate'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM salary ORDER BY id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch()){

    $data[] = array("label" => $row["salgrade"],
        "value" => $row["id"]
        );
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Verification Confirmation

if(isset($_GET['verifycodes'])){

    $out = array('error' => false);
    $verifycode = $_POST['vcode'];

    $stnt = $pdo->prepare("SELECT * FROM verify WHERE verify_code = ?");
    $params = array($verifycode);
    $stnt -> execute($params);
    $row=$stnt->fetch();

    if ($row){
        $result =  true;
    }else{

        $result = false;
    }

    echo json_encode($result);
   

}




// Authentication

if(isset($_GET['auth'])){


$out = array('error' => false);

$email = $_POST['email'];
$password = $_POST['password'];
$passwordsHash = password_hash($password, PASSWORD_DEFAULT);


    $stnt = $pdo->prepare("SELECT * FROM auth WHERE email = ?");
    $params = array($email);
    $stnt->execute($params);
    $row=$stnt->fetch();


    if ($row) {
    if (password_verify($password, $row["password"])){
        $_SESSION['loggedInUser']=$row;
        unset($_SESSION['loggedInUser']["password"]);
        $out['message'] = "Login Successful";
    }else{
        $out['error'] = true;
        $out['message'] = "Login Failed.";
    }
}else{
    echo "Error Email does not match";
    

}


echo json_encode($out);
die();
}


if (isset($_GET['authLog'])){
    // session_destroy();
    if(isset($_SESSION['loggedInUser'])){
       echo json_encode($_SESSION["loggedInUser"]);
    }else{
        echo json_encode(false);
    }
}




// Authentication Logout

if(isset($_GET['authlogout'])){
    session_destroy();
    echo "Log out";
}






//Read Job

if(isset($_GET['readJob'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT j.job_id, s.id, j.jobtitle, j.plantilla, j.educ, j.yrsexp, j.exp, j.hrstraining, j.training, j.enddate, s.salgrade, s.amount, j.eligibility, j.area, j.datetime, j.stats  FROM job AS j
LEFT OUTER JOIN salary AS s
ON j.salary_id = s.id
WHERE postopt = true ORDER BY job_id;");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

//Read Duties and Responsibilities

if(isset($_GET['readduty'])){
$data = array();
$jobid = $_POST["job_id"];
try
{

    $stnt = $pdo->prepare("SELECT j.job_id,d.job_id, d.duties_id, j.jobtitle, d.description
FROM job AS j
LEFT OUTER JOIN duties AS d
ON d.job_id = j.job_id
WHERE j.job_id=?;");
    $params = array($jobid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}

// Read Competencies

if(isset($_GET['readcompetency'])){
$data = array();
$jobid = $_POST["job_id"];
try
{

    $stnt = $pdo->prepare("SELECT j.job_id,c.job_id, c.comp_id, j.jobtitle, c.description
FROM job AS j
LEFT OUTER JOIN competencies AS c
ON c.job_id = j.job_id WHERE j.job_id=?;");
    $params = array($jobid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



//Read Position

if(isset($_GET['readPosition'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT j.job_id, s.id, j.jobtitle, j.plantilla, j.educ, j.yrsexp, j.exp, j.hrstraining, j.training, j.postopt, s.salgrade, s.amount, j.eligibility, j.area, j.datetime, j.stats, j.enddate  FROM job AS j
LEFT OUTER JOIN salary AS s
ON s.id = j.salary_id ORDER BY job_id");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



//Read Applicants


if(isset($_GET['readApplicants'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT * FROM auth");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Status

if(isset($_GET['readstatus'])){



$data = array();
$statsid = $_POST["auth_id"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM status WHERE auth_id=?;");
    $params = array($statsid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Read Applicants

if(isset($_GET['readApplications'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT r.reg_id, r.auth_id, r.regjob, u.first_name, u.last_name, u.email, 
r.regmidname, r.regdate, r.gender, r.contact, r.license, r.datetime, d.cv, d.pds, d.wes, d.diploma, d.tor, d.eligibility, d.appletter, d.traincert, d.per FROM registration AS r
LEFT OUTER JOIN auth AS u ON u.id = r.auth_id
LEFT OUTER JOIN documents AS d ON d.reg_id = r.reg_id
ORDER BY reg_id
;");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read files


if(isset($_GET['readfiles'])){

$data = array();

$regid = $_POST["reg_id"];

try
{

    $stnt = $pdo->prepare("SELECT * FROM documents WHERE reg_id=?");
    $params = array($regid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Survey


if(isset($_GET['readsurveys'])){
$data = array();
try
{

    $stnt = $pdo->prepare("SELECT f.reg_id, f.description, f.vacancy, f.star, f.datetime, r.regfirstname, r.reglastname, r.regemail FROM feedback AS f
LEFT OUTER JOIN registration as r ON f.reg_id = r.reg_id
ORDER BY feed_id;");
    $stnt->execute();

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Registration from Applicants View



if(isset($_GET['readappreg'])){

$data = array();

$authid = $_POST["userid"];

try
{

    $stnt = $pdo->prepare("SELECT r.reg_id, r.auth_id, r.regjob, j.job_id, j.plantilla, j.educ, j.area, u.first_name, u.last_name, u.email, 
r.regmidname, r.regdate, r.gender, r.contact, r.license, r.datetime, d.cv, d.pds, d.wes, d.diploma, d.tor, d.eligibility, d.appletter, d.traincert, d.per FROM registration AS r
LEFT OUTER JOIN auth AS u ON u.id = r.auth_id
LEFT OUTER JOIN documents AS d ON d.reg_id = r.reg_id
LEFT OUTER JOIN job AS j ON j.job_id = r.job_id WHERE r.auth_id=?;");
    $params = array($authid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}



// Read Job with id

if(isset($_GET['readjobid'])){



$data = array();
$jobid = $_POST["job_id"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM job WHERE job_id=?;");
    $params = array($jobid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Competencies

if(isset($_GET['editcomp'])){



$data = array();
$jobid = $_POST["jobidssss"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM competencies WHERE job_id=?;");
    $params = array($jobid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Duties and Responsibilities


if(isset($_GET['editduty'])){



$data = array();
$jobid = $_POST["jobidssss"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM duties WHERE job_id=?;");
    $params = array($jobid);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data[] = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}


// Read Salary

if(isset($_GET['readsalids'])){



$data = array();
$sal_id = $_POST["sal_id"];
try
{

    $stnt = $pdo->prepare("SELECT * FROM salary WHERE id=?;");
    $params = array($sal_id);
    $stnt -> execute($params);

}catch (Exception $ex){
    die("Failed to run query". $ex);

}

http_response_code(200);

while ($row = $stnt->fetch(PDO::FETCH_ASSOC)){
    $data = $row;
}

echo json_encode($data);

$stnt = null;
$pdo = null;

}






 ?>