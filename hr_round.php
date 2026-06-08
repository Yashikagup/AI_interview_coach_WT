<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>HR Interview Round</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f7fb;
font-family:'Segoe UI',sans-serif;
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
font-size:22px;
font-weight:600;
margin-bottom:20px;
}

textarea{
width:100%;
height:140px;
padding:15px;
border-radius:10px;
border:1px solid #ccc;
resize:none;
}

.btn-main{
background:#6366f1;
color:white;
padding:12px 25px;
border:none;
border-radius:10px;
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

</style>

</head>

<body>

<div class="box">

<h2>HR Interview Round</h2>

<h5 id="progress">
Loading Questions...
</h5>

<br>

<div class="question-box" id="question">

Please wait...

</div>

<textarea
id="answer"
placeholder="Type your HR answer..."></textarea>

<br><br>

<button
class="btn-main"
onclick="submitAnswer()">

Submit Answer

</button>

<div
class="feedback"
id="feedback"></div>

</div>

<script>

let questions = [];
let current = 0;
let results = [];

// ==========================
// LOAD HR QUESTIONS
// ==========================
fetch("process_interview.php",{
    method:"POST",
    body:new URLSearchParams({
        action:"generate_hr_questions"
    })
})
.then(res=>res.json())
.then(data=>{

    console.log("HR RESPONSE:", data);

    if(data.status !== "success" || !data.questions){
        document.getElementById("question").innerHTML =
        "<h5>Failed to load HR questions</h5>";
        return;
    }

    questions = data.questions;
    showQuestion();
})
.catch(err=>{
    console.log(err);
    document.getElementById("question").innerHTML =
    "<h5>Server Error</h5>";
});

// ==========================
// SHOW QUESTION
// ==========================
function showQuestion(){

    if(current >= questions.length){
        finishHR();
        return;
    }

    document.getElementById("progress").innerHTML =
    "Question " + (current+1) + " of " + questions.length;

    document.getElementById("question").innerText =
    questions[current];

    document.getElementById("answer").value = "";
    document.getElementById("feedback").innerHTML =

"<h4>Score: " + (data.score || 0) + "/10</h4>" +

"<h5>AI Feedback</h5>" +

"<p style='white-space:pre-line;'>"
+ (data.feedback || "No feedback") +
"</p>";

// ==========================
// SUBMIT ANSWER
// ==========================
function submitAnswer(){

    let answer = document.getElementById("answer").value.trim();

    if(answer === ""){
        alert("Please enter answer");
        return;
    }

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

        document.getElementById("feedback").style.display = "block";

        document.getElementById("feedback").innerHTML =
        "<h5>AI HR Feedback</h5>" +
        "<p style='white-space:pre-line;'>" +
        (data.feedback || "No feedback") +
        "</p>";

        results.push({
    question: questions[current],
    answer: answer,
    feedback: data.feedback || "No feedback",
    score: data.score || 0
});

        document.getElementById("feedback").innerHTML += `
            <br><br>
            <button class="btn btn-primary" onclick="goNextQuestion()">
                Next Question
            </button>
        `;
    })
    .catch(err=>{
        console.log(err);
        alert("Feedback error");
    });
}

// ==========================
// NEXT QUESTION (FIXED OUTSIDE)
// ==========================
function goNextQuestion(){
    current++;
    showQuestion();
}

// ==========================
// FINISH HR
// ==========================
function finishHR(){

fetch("process_interview.php",{

    method:"POST",

    body:new URLSearchParams({

        action:"save_round",

        type:"hr",

        data:JSON.stringify(results)

    })

})

.then(res=>res.json())

.then(data=>{

    console.log(data);

    document.body.innerHTML = `

    <div class="text-center mt-5">

        <h2>🎉 HR Round Completed</h2>

        <h4>Interview Finished Successfully</h4>

        <br><br>

        <a href="analytics.php" class="btn btn-primary btn-lg">
            View Analytics
        </a>

        <a href="generate_pdf.php" class="btn btn-success btn-lg">
            Download Report
        </a>

    </div>
    `;

})

.catch(err=>{

    console.log(err);

    alert("Failed to save HR round");

});

}
</script>

</body>
</html>