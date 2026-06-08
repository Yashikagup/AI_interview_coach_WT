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

<title>Technical Interview Setup</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f7fb;
font-family:'Segoe UI',sans-serif;
}

.box{
width:60%;
margin:50px auto;
background:white;
padding:50px;
border-radius:25px;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

h1{
text-align:center;
margin-bottom:40px;
font-weight:bold;
}

label{
font-size:18px;
font-weight:600;
margin-bottom:10px;
}

.form-control,
.form-select{
padding:15px;
border-radius:12px;
font-size:18px;
margin-bottom:25px;
}

.btn-main{
width:100%;
background:#6366f1;
color:white;
padding:15px;
border:none;
border-radius:12px;
font-size:22px;
font-weight:bold;
}

.btn-main:hover{
background:#4f46e5;
}

.hidden{
display:none;
}

</style>

</head>

<body>

<div class="box">

<h1>Technical Interview Setup</h1>

<form
action="interview.php"
method="GET"
enctype="multipart/form-data">

<!-- INTERVIEW TYPE -->

<label>Select Interview Type</label>

<select
name="type"
id="type"
class="form-select"
onchange="toggleFields()"
required>

<option value="">
Choose Type
</option>

<option value="resume">
Resume Based Interview
</option>

<option value="role">
Role Based Interview
</option>

</select>

<!-- ROLE -->

<div id="roleBox" class="hidden">

<label>Enter Job Role</label>

<input
type="text"
name="role"
class="form-control"
placeholder="Example: Data Analyst">

</div>

<!-- RESUME -->

<div id="resumeBox" class="hidden">

<label>Upload Resume</label>

<input
type="file"
name="resume"
class="form-control">

</div>

<!-- DIFFICULTY -->

<label>Select Difficulty</label>

<select
name="difficulty"
class="form-select"
required>

<option value="Easy">
Easy
</option>

<option value="Medium" selected>
Medium
</option>

<option value="Hard">
Hard
</option>

</select>

<button
type="submit"
class="btn-main">

Start Technical Interview

</button>

</form>

</div>

<script>

function toggleFields(){

let type =
document.getElementById("type").value;

document.getElementById("roleBox")
.style.display = "none";

document.getElementById("resumeBox")
.style.display = "none";

if(type == "role"){

document.getElementById("roleBox")
.style.display = "block";

}

if(type == "resume"){

document.getElementById("resumeBox")
.style.display = "block";

}

}

</script>

</body>
</html>