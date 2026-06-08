<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$role = $_GET['role'] ?? 'Software Developer';
$difficulty = $_GET['difficulty'] ?? 'Easy';
$type = $_GET['type'] ?? 'role';

$_SESSION['role'] = $role;
$_SESSION['difficulty'] = $difficulty;
$_SESSION['interview_type'] = $type;
?>

<!DOCTYPE html>
<html>
<head>

<title>AI Technical Interview</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f7fb;
font-family:Segoe UI;
}

.box{
width:80%;
margin:40px auto;
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.question-box{
background:#eef2ff;
padding:20px;
border-radius:12px;
font-size:20px;
font-weight:600;
margin-bottom:20px;
}

textarea{
width:100%;
height:140px;
padding:15px;
border-radius:10px;
border:1px solid #ccc;
}

.btn-main{
background:#5b5cf0;
color:white;
padding:12px 25px;
border:none;
border-radius:10px;
font-size:18px;
font-weight:bold;
}

.btn-main:hover{
background:#4338ca;
}

.progress-box{
margin-bottom:20px;
font-size:18px;
font-weight:bold;
}

.feedback{
background:#f0fdf4;
padding:20px;
border-radius:10px;
margin-top:20px;
display:none;
}

.loader{
display:none;
font-weight:bold;
color:#5b5cf0;
margin-top:10px;
}

</style>

</head>

<body>

<div class="box">

<h2>AI Technical Interview</h2>

<div class="progress-box" id="progress">
Loading Questions...
</div>

<div class="question-box" id="question">
Please wait...
</div>

<textarea
id="answer"
placeholder="Type your answer here..."></textarea>

<br><br>

<button
class="btn-main"
onclick="submitAnswer()">

Submit Answer

</button>

<div class="loader" id="loader">
AI is evaluating your answer...
</div>

<div class="feedback" id="feedback"></div>

</div>

<script>

let questions = [];
let current = 0;
let results = [];

// LOAD QUESTIONS
window.onload = function(){

fetch("process_interview.php",{

method:"POST",

body:new URLSearchParams({

action:"generate_questions"

})

})

.then(res=>res.json())

.then(data=>{

questions = data.questions;

showQuestion();

})

.catch(err=>{

alert("Failed to load questions");

console.log(err);

});

};

// SHOW QUESTION
function showQuestion(){

if(current >= questions.length){

finishInterview();
return;

}

document.getElementById("progress").innerText =
"Question " + (current+1) + " of " + questions.length;

document.getElementById("question").innerText =
questions[current];

document.getElementById("answer").value = "";

document.getElementById("feedback").style.display =
"none";
}

// SUBMIT ANSWER
function submitAnswer(){

let answer =
document.getElementById("answer").value.trim();

if(answer == ""){

alert("Please enter answer");
return;

}

document.getElementById("loader").style.display =
"block";

fetch("process_interview.php",{

method:"POST",

body:new URLSearchParams({

action:"evaluate_answer",
question:questions[current],
answer:answer

})

})

.then(res=>res.json())

.then(data=>{

document.getElementById("loader").style.display =
"none";

document.getElementById("feedback").style.display =
"block";

document.getElementById("feedback").innerHTML =

"<h5>AI Feedback</h5>" +
"<p>"+data.feedback+"</p>";

results.push({

question:questions[current],
answer:answer,
feedback:data.feedback

});

setTimeout(()=>{

current++;
showQuestion();

},3000);

});

}

// FINISH INTERVIEW
function finishInterview(){

fetch("process_interview.php",{

method:"POST",

body:new URLSearchParams({

action:"save_results",
data:JSON.stringify(results)

})

})

.then(res=>res.json())

.then(data=>{

alert("Technical Round Completed!");

window.location.href = "hr_round.php";

});

}

</script>

</body>
</html>