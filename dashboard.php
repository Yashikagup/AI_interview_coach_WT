<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include "db.php";

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
SELECT AVG(score) as overall_score
FROM interview_results
WHERE user_id=?
");

$stmt->execute([$user_id]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

$overall = round($data['overall_score'] ?? 0,1);

$status = "Needs Improvement";

if($overall >= 8){
    $status = "Excellent";
}
elseif($overall >= 6){
    $status = "Good";
}
elseif($overall >= 4){
    $status = "Average";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>AI Interview Coach</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background:#f5f7fb;
font-family:'Segoe UI',sans-serif;
margin:0;
padding:0;
}

.header{
background:white;
padding:18px 35px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo{
font-size:30px;
font-weight:bold;
color:#4f46e5;
}

.main{
width:90%;
margin:40px auto;
}

/* OVERALL PERFORMANCE */

.performance-box{
background:linear-gradient(135deg,#5b5cf0,#7c3aed);
padding:40px;
border-radius:28px;
margin-bottom:40px;
display:flex;
justify-content:space-between;
align-items:center;
color:white;
box-shadow:0 10px 30px rgba(0,0,0,0.12);
}

.performance-title{
font-size:20px;
margin-bottom:10px;
opacity:0.9;
}

.performance-score{
font-size:60px;
font-weight:bold;
line-height:1;
}

.performance-status{
margin-top:15px;
font-size:20px;
font-weight:600;
}

.performance-icon{
font-size:90px;
opacity:0.9;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

.card-box{
background:white;
padding:35px;
border-radius:24px;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
text-align:center;
transition:0.3s;
}

.card-box:hover{
transform:translateY(-5px);
}

.icon{
font-size:50px;
margin-bottom:20px;
color:#5b5cf0;
}

.card-box h3{
font-weight:bold;
margin-bottom:15px;
}

.card-box p{
color:#666;
min-height:55px;
}

.btn-custom{
width:100%;
padding:14px;
border:none;
border-radius:12px;
color:white;
font-size:18px;
font-weight:bold;
transition:0.3s;
}

.btn-custom:hover{
opacity:0.9;
}

.blue{
background:#5b5cf0;
}

.green{
background:#10b981;
}

.orange{
background:#f59e0b;
}

.red{
background:#ef4444;
}

.purple{
background:#7c3aed;
}

.welcome{
font-size:16px;
}

.dashboard-title{
font-size:40px;
font-weight:bold;
margin-bottom:5px;
}

.dashboard-sub{
color:#666;
margin-bottom:35px;
font-size:18px;
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

<div class="logo">
🤖 AI Interview Coach
</div>

<div class="welcome">

Welcome
<b><?php echo $_SESSION['username']; ?></b>

<a href="logout.php"
class="btn btn-danger ms-3">

Logout

</a>

</div>

</div>

<!-- MAIN -->

<div class="main">

<!-- PERFORMANCE -->

<div class="performance-box">

<div>

<div class="performance-title">
Overall Performance
</div>

<div class="performance-score">
<?php echo $overall; ?>/10
</div>

<div class="performance-status">
Status: <?php echo $status; ?>
</div>

</div>

<div class="performance-icon">
📊
</div>

</div>

<!-- TITLE -->

<h1 class="dashboard-title">
Interview Dashboard
</h1>

<p class="dashboard-sub">
Choose an interview round to begin your AI mock interview.
</p>

<!-- CARDS -->

<div class="cards">

<!-- APTITUDE -->

<div class="card-box">

<div class="icon">
<i class="fa-solid fa-brain"></i>
</div>

<h3>Aptitude Round</h3>

<p>
Practice TCS-level aptitude MCQs with AI scoring.
</p>

<button
onclick="window.location.href='aptitude.php'"
class="btn-custom orange">

Start Aptitude

</button>

</div>

<!-- TECHNICAL -->

<div class="card-box">

<div class="icon">
<i class="fa-solid fa-laptop-code"></i>
</div>

<h3>Technical Round</h3>

<p>
Role-based and resume-based AI technical interview.
</p>

<button
onclick="window.location.href='technical_select.php'"
class="btn-custom blue">

Start Technical

</button>

</div>

<!-- HR -->

<div class="card-box">

<div class="icon">
<i class="fa-solid fa-user-tie"></i>
</div>

<h3>HR Interview</h3>

<p>
Improve communication and HR interview confidence.
</p>

<button
onclick="window.location.href='hr_round.php'"
class="btn-custom green">

Start HR Round

</button>

</div>

<!-- REPORT -->

<div class="card-box">

<div class="icon">
<i class="fa-solid fa-file-pdf"></i>
</div>

<h3>Final Report</h3>

<p>
Download complete AI interview performance report.
</p>

<button
onclick="window.location.href='generate_pdf.php'"
class="btn-custom red">

Download Report

</button>

</div>

<!-- ANALYTICS -->

<div class="card-box">

<div class="icon">
<i class="fa-solid fa-chart-line"></i>
</div>

<h3>Analytics</h3>

<p>
Detailed analytics, strengths and weaknesses dashboard.
</p>

<button
onclick="window.location.href='analytics.php'"
class="btn-custom purple">

View Analytics

</button>

</div>

</div>

</div>

</body>
</html>