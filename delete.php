<?php 
require("datacon.php");


if(isset($_GET['delete'])){


$salaryid = $_POST["id"];


$stnt = $pdo->prepare("DELETE FROM salary WHERE id=?");
$stnt -> execute([$salaryid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}




if(isset($_GET['deleteposition'])){


$jobid = $_POST["job_id"];


$stnt = $pdo->prepare("DELETE FROM job WHERE job_id=?");
$stnt -> execute([$jobid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


if(isset($_GET['deleteApplicants'])){


$regid = $_POST["reg_id"];


$stnt = $pdo->prepare("DELETE FROM registration WHERE reg_id=?");
$stnt -> execute([$regid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}


// Delete Competency

if(isset($_GET['deletecomp'])){


$compid = $_POST["comp_id"];


$stnt = $pdo->prepare("DELETE FROM competencies WHERE comp_id=?");
$stnt -> execute([$compid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

// Delete Duty and Responsibility


if(isset($_GET['deleteduty'])){


$dutid = $_POST["duties_id"];


$stnt = $pdo->prepare("DELETE FROM duties WHERE duties_id=?");
$stnt -> execute([$dutid]);
 if($stnt){
        $result =  true;
    } else{
        
        $result = false;
    }

    echo json_encode($result);

}

?>