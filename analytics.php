<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH RESULTS
========================= */

$stmt = $pdo->prepare("
SELECT * FROM interview_results
WHERE user_id=?
");

$stmt->execute([$user_id]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   INITIALIZE
========================= */

$totalScore = 0;
$totalQuestions = 0;

$aptitude = 0;
$technical = 0;
$hr = 0;

$aptitudeCount = 0;
$technicalCount = 0;
$hrCount = 0;

/* =========================
   CALCULATE SCORES
========================= */

foreach($results as $row){

    $score = (int)($row['score'] ?? 0);

    $totalScore += $score;
    $totalQuestions++;

    if($row['round_type'] == "aptitude"){
        $aptitude += $score;
        $aptitudeCount++;
    }

    if($row['round_type'] == "technical"){
        $technical += $score;
        $technicalCount++;
    }

    if($row['round_type'] == "hr"){
        $hr += $score;
        $hrCount++;
    }
}

/* =========================
   AVERAGES
========================= */

$overall =
$totalQuestions > 0
? round($totalScore / $totalQuestions,1)
: 0;

$aptitudeAvg =
$aptitudeCount > 0
? round($aptitude / $aptitudeCount,1)
: 0;

$technicalAvg =
$technicalCount > 0
? round($technical / $technicalCount,1)
: 0;

$hrAvg =
$hrCount > 0
? round($hr / $hrCount,1)
: 0;

/* =========================
   PERFORMANCE ANALYSIS
========================= */

$status = "Needs Improvement";
$recommendation = "Not Recommended";
$confidence = "Low";

if($overall >= 8){

    $status = "Excellent";
    $recommendation = "Highly Recommended";
    $confidence = "High";

}
elseif($overall >= 6){

    $status = "Good";
    $recommendation = "Recommended";
    $confidence = "Medium";

}
elseif($overall >= 4){

    $status = "Average";
    $recommendation = "Can Improve";
    $confidence = "Medium";

}

/* =========================
   BAR COLOR
========================= */

$barClass = "bg-danger";

if($overall >= 8){
    $barClass = "bg-success";
}
elseif($overall >= 6){
    $barClass = "bg-primary";
}
elseif($overall >= 4){
    $barClass = "bg-warning";
}

/* =========================
   STRONGEST + WEAKEST
========================= */

$scores = [
    "Aptitude" => $aptitudeAvg,
    "Technical" => $technicalAvg,
    "HR" => $hrAvg
];

$strongest = array_search(max($scores), $scores);
$weakest = array_search(min($scores), $scores);

/* =========================
   IMPROVEMENT TIPS
========================= */

$tips = [];

if($aptitudeAvg < 5){
    $tips[] = "Practice aptitude daily for better speed and accuracy.";
}

if($technicalAvg < 5){
    $tips[] = "Improve coding and technical concepts.";
}

if($hrAvg < 5){
    $tips[] = "Work on communication and confidence.";
}

if(empty($tips)){
    $tips[] = "Excellent overall performance. Keep practicing.";
}

?>

<!DOCTYPE html>
<html>
<head>

<title>AI Interview Analytics</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background:#f5f7fb;
font-family:'Segoe UI',sans-serif;
}

.main{
width:90%;
margin:40px auto;
}

.heading{
text-align:center;
margin-bottom:40px;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:25px;
}

.card-box{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
text-align:center;
}

.score{
font-size:42px;
font-weight:bold;
color:#5b5cf0;
}

.label{
font-size:20px;
margin-top:10px;
font-weight:600;
}

.analysis-box{
background:white;
padding:35px;
border-radius:20px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
margin-top:35px;
}

.progress{
height:30px;
border-radius:20px;
margin-top:15px;
}

.progress-bar{
font-size:16px;
font-weight:bold;
}

.btn-custom{
padding:14px 25px;
border:none;
border-radius:10px;
font-size:18px;
font-weight:bold;
color:white;
text-decoration:none;
margin-right:10px;
}

.blue{
background:#5b5cf0;
}

.green{
background:#10b981;
}

</style>

</head>

<body>

<div class="main">

<div class="heading">

<h1>
📊 AI Interview Analytics Dashboard
</h1>

<p>
Complete performance analysis based on interview rounds
</p>

</div>

<!-- SCORE CARDS -->

<div class="cards">

<div class="card-box">
<div class="score"><?php echo $aptitudeAvg; ?>/10</div>
<div class="label">Aptitude</div>
</div>

<div class="card-box">
<div class="score"><?php echo $technicalAvg; ?>/10</div>
<div class="label">Technical</div>
</div>

<div class="card-box">
<div class="score"><?php echo $hrAvg; ?>/10</div>
<div class="label">HR</div>
</div>

<div class="card-box">
<div class="score"><?php echo $overall; ?>/10</div>
<div class="label">Overall</div>
</div>

</div>

<!-- OVERALL PERFORMANCE -->

<div class="analysis-box">

<h3>
Overall Performance
</h3>

<div class="progress">

<div
class="progress-bar <?php echo $barClass; ?>"

role="progressbar"

style="width: <?php echo $overall * 10; ?>%;">

<?php echo $overall; ?>/10

</div>

</div>

<br>

<p>
<b>Status:</b>
<?php echo $status; ?>
</p>

<p>
<b>Hiring Recommendation:</b>
<?php echo $recommendation; ?>
</p>

<p>
<b>Confidence Level:</b>
<?php echo $confidence; ?>
</p>

<p>
<b>Strongest Area:</b>
<?php echo $strongest; ?>
</p>

<p>
<b>Weakest Area:</b>
<?php echo $weakest; ?>
</p>

<p>
<b>Total Questions Attempted:</b>
<?php echo $totalQuestions; ?>
</p>

</div>

<!-- IMPROVEMENT TIPS -->

<div class="analysis-box">

<h3>
AI Improvement Suggestions
</h3>

<ul class="list-group mt-4">

<?php
foreach($tips as $tip){
?>

<li class="list-group-item">
<?php echo $tip; ?>
</li>

<?php
}
?>

</ul>

</div>

<!-- BUTTONS -->

<div class="text-center mt-5">

<a
href="dashboard.php"
class="btn-custom blue">

Back To Dashboard

</a>

<a
href="generate_pdf.php"
class="btn-custom green">

Download PDF Report

</a>

</div>

</div>

</body>
</html>