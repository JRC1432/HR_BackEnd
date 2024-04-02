<?php



$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");
 // cache for 10 minutes
if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{

 if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))

 header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support
  if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))

 header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  //Just exit with 200 OK with the above headers for OPTIONS method

 exit(0);

 }
//From here, handle the request as it is ok

try {
  $dsn = "pgsql:host=localhost;port=5432;dbname=hr;";
  // make a database connection
  $pdo = new PDO($dsn, "postgres", "143297", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

} catch (PDOException $e) {
  die($e->getMessage());
} 
session_start();
?>

