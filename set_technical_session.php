<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$type = $_POST['type'] ?? '';
$role = $_POST['role'] ?? '';
$difficulty = $_POST['difficulty'] ?? 'Medium';

$_SESSION['difficulty'] = $difficulty;
$_SESSION['tech_type'] = $type;

// ROLE BASED
if($type == "role"){
    $_SESSION['role'] = $role;
}

// RESUME BASED (basic placeholder)
if($type == "resume"){

    if(isset($_FILES['resume'])){

        $file = file_get_contents($_FILES['resume']['tmp_name']);

        // store raw text (you can upgrade with PDF parser later)
        $_SESSION['resume_text'] = $file;
    }
}

header("Location: technical_round.php?type=$type");
exit();
?>