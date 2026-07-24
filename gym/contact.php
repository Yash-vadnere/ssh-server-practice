<?php
$host='localhost'; $user='gymadmin'; $pass='Gym@2026'; $db='gymdb';
$conn = new mysqli($host,$user,$pass,$db);

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name    = $conn->real_escape_string($_POST['name']);
  $email   = $conn->real_escape_string($_POST['email']);
  $phone   = $conn->real_escape_string($_POST['phone']);
  $program = $conn->real_escape_string($_POST['program']);
  $message = $conn->real_escape_string($_POST['message']);
  
  $conn->query("INSERT INTO enquiries (name,email,phone,program,message) VALUES ('$name','$email','$phone','$program','$message')");
  
  echo json_encode(['status'=>'success']);
} else {
  echo json_encode(['status'=>'error']);
}
?>
