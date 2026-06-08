<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode([
        "status"=>"error",
        "message"=>"Unauthorized"
    ]);
    exit();
}

$type = $_POST['type'] ?? '';
$data = json_decode($_POST['data'], true);

if(!$data){
    echo json_encode([
        "status"=>"error",
        "message"=>"No data"
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$clean = [];

foreach($data as $row){

    $question = $row['question'] ?? '';
    $answer   = $row['answer'] ?? '';
    $feedback = $row['feedback'] ?? 'No feedback';
    $score    = $row['score'] ?? 0;

    // SAVE INTO DATABASE
    $stmt = $pdo->prepare("
        INSERT INTO interview_results
        (user_id, round_type, question, answer, feedback, score)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $type,
        $question,
        $answer,
        $feedback,
        $score
    ]);

    // SAVE SESSION
    $clean[] = [
        "question" => $question,
        "answer" => $answer,
        "feedback" => $feedback,
        "score" => $score
    ];
}

$_SESSION[$type] = $clean;

echo json_encode([
    "status"=>"success",
    "saved"=>count($clean)
]);
?>