<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$role = $_SESSION['role'] ?? 'Software Developer';
?>

<!DOCTYPE html>
<html>
<head>

<title>Technical Interview Round</title>

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

.btn-main:hover{
background:#4f46e5;
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
margin-top:10px;
color:#6366f1;
}

</style>

</head>

<body>

<div class="box">

<h2>Technical Interview Round</h2>

<h5>
Role:
<?php echo $role; ?>
</h5>

<br>

<div id="progress">
Loading AI Questions...
</div>

<br>

<div class="question-box" id="question">
Please wait...
</div>

<textarea
id="answer"
placeholder="Type your technical answer..."></textarea>

<br><br>

<button
class="btn-main"
id="mainBtn"
onclick="submitAnswer()">

Submit Answer

</button>

<div class="loader" id="loader">
AI is evaluating...
</div>

<div class="feedback" id="feedback"></div>

</div>

<script>

let questions = [];

let current = 0;

let results = [];

// ==========================
// LOAD AI QUESTIONS
// ==========================

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

console.log(err);

alert("Failed to load questions");

});

// ==========================
// SHOW QUESTION
// ==========================

function showQuestion(){

if(current >= questions.length){

finishTechnical();
return;

}

document.getElementById("progress").innerHTML =

"<b>Question "
+ (current+1)
+ " of "
+ questions.length
+ "</b>";

document.getElementById("question").innerText =
questions[current];

document.getElementById("answer").value = "";

document.getElementById("feedback").style.display =
"none";

}

// ==========================
// SUBMIT ANSWER
// ==========================

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

document.getElementById("feedback").innerHTML =

"<h4>Score: " + (data.score || 0) + "/10</h4>" +

"<h5>AI Feedback</h5>" +

"<p style='white-space:pre-line;'>"
+ (data.feedback || "No feedback") +
"</p>";

results.push({
    question: questions[current],
    answer: answer,
    feedback: data.feedback || "No feedback",
    score: data.score || 0
});

console.log(results);

let btn =
document.getElementById("mainBtn");

btn.innerText =
"Next Question";

btn.setAttribute(
"onclick",
"goNextQuestion()"
);

})
// CHANGE BUTTON TO NEXT

let btn =
document.getElementById("mainBtn");

btn.innerText =
"Next Question";

btn.setAttribute(
"onclick",
"goNextQuestion()"
);

})

.catch(err=>{

console.log(err);

alert("AI evaluation failed");

document.getElementById("loader").style.display =
"none";

});

}

// ==========================
// NEXT QUESTION
// ==========================

function goNextQuestion(){

current++;

showQuestion();

let btn =
document.getElementById("mainBtn");

btn.innerText =
"Submit Answer";

btn.setAttribute(
"onclick",
"submitAnswer()"
);

}

// ==========================
// FINISH TECHNICAL ROUND
// ==========================

function finishTechnical(){

fetch("process_interview.php",{

    method:"POST",

    body:new URLSearchParams({

        action:"save_round",

        type:"technical",

        data:JSON.stringify(results)

    })

})

.then(res=>res.json())

.then(data=>{

    console.log(data);

    document.body.innerHTML = `

    <div class="text-center mt-5">

        <h2>🎉 Technical Round Completed</h2>

        <br>

        <h4>Your responses saved successfully</h4>

        <br><br>

        <a href="dashboard.php" class="btn btn-secondary btn-lg">
            Go to Dashboard
        </a>

        <a href="hr_round.php" class="btn btn-primary btn-lg">
            Continue to HR Round
        </a>

    </div>
    `;

})

.catch(err=>{

    console.log(err);

    alert("Failed to save technical round");

});

}
</script>

</body>
</html>