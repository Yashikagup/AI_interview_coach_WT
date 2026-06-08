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
<title>Aptitude Round</title>

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

.option{
background:#f9fafb;
padding:15px;
margin-bottom:12px;
border-radius:10px;
cursor:pointer;
border:2px solid transparent;
font-size:18px;
}

.option:hover{
border-color:#6366f1;
}

.selected{
border-color:#6366f1;
background:#e0e7ff;
}

.btn-next{
background:#6366f1;
color:white;
padding:12px 30px;
border:none;
border-radius:10px;
font-size:18px;
font-weight:bold;
margin-top:20px;
}
</style>
</head>

<body>

<div class="box">

<h2>AI Aptitude Round</h2>

<div id="content">
<div>Loading Questions...</div>
</div>

</div>

<script>

let questions = [];
let current = 0;
let score = 0;
let selected = "";

/* LOAD QUESTIONS */
window.onload = function(){

fetch("process_interview.php",{
    method:"POST",
    body:new URLSearchParams({
        action:"generate_aptitude"
    })
})
.then(res=>res.json())
.then(data=>{

    console.log("APTITUDE:", data);

    if(data.status !== "success" || !data.questions){
        alert(data.message || "Failed to load questions");
        return;
    }

    questions = data.questions;
    showQuestion();
})
.catch(err=>{
    console.log(err);
    alert("Server error");
});

};

/* SHOW QUESTION */
function showQuestion(){

if(current >= questions.length){
    finishRound();
    return;
}

let q = questions[current];

let html = `
<h5>Question ${current+1} of ${questions.length}</h5>

<div class="question-box">
${q.question}
</div>
`;

q.options.forEach(opt=>{
html += `
<div class="option" onclick="selectOption(this,'${opt}')">
${opt}
</div>`;
});

html += `
<button class="btn-next" onclick="nextQuestion()">
Next Question
</button>
`;

document.getElementById("content").innerHTML = html;

selected = "";
}

/* SELECT OPTION */
function selectOption(el,val){

document.querySelectorAll(".option")
.forEach(o => o.classList.remove("selected"));

el.classList.add("selected");

selected = val;
}

/* NEXT */
function nextQuestion(){

if(selected === ""){
alert("Select option");
return;
}

if(selected === questions[current].answer){
score++;
}

current++;
showQuestion();
}

/* FINISH */
function finishRound(){

fetch("process_interview.php",{
    method:"POST",
    headers:{
        "Content-Type":"application/x-www-form-urlencoded"
    },
    body:new URLSearchParams({
        action:"save_round",
        type:"aptitude",
        data: JSON.stringify(questions)
    })
});

document.getElementById("content").innerHTML = `
<div class="text-center">

    <h2>🎉 Aptitude Completed</h2>
    <h4>Your Score: ${score}/${questions.length}</h4>

    <br><br>

    <a href="dashboard.php" class="btn btn-secondary btn-lg">
        Go to Dashboard
    </a>

    <a href="technical_round.php" class="btn btn-primary btn-lg">
        Continue to Technical Round
    </a>

</div>
`;
}

</script>

</body>
</html>