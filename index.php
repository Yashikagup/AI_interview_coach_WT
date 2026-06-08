<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>AI Interview Coach</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* 🌈 SOFT PROFESSIONAL AI BACKGROUND */
body {
    background: linear-gradient(135deg, #e8edff, #f5f7fb, #eef2ff, #e0e7ff);
    background-size: 300% 300%;
    animation: gradientMove 12s ease infinite;
    font-family: 'Segoe UI', sans-serif;
    color: #333;
}

/* BACKGROUND ANIMATION */
@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* HERO */
.hero {
    text-align: center;
    padding: 60px 20px 20px;
}

.hero h1 {
    color: #1e293b;
}

.hero p {
    color: #64748b;
}

/* 🧊 GLASS CARDS */
.step-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    border: 1px solid #e5e7eb;
    transition: 0.3s;
}

.step-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* ICON */
.step-icon {
    font-size: 28px;
    margin-bottom: 12px;
    background: linear-gradient(45deg, #2563eb, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* LOGIN CARD */
.login-card {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(15px);
    border-radius: 16px;
    padding: 30px;
    max-width: 400px;
    margin: 40px auto;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* INPUT */
.login-card input {
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 10px;
}

.login-card input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
}

/* BUTTON */
.login-card button {
    background: linear-gradient(45deg, #2563eb, #3b82f6);
    border: none;
    padding: 10px;
    border-radius: 10px;
    color: white;
    font-weight: 500;
    transition: 0.3s;
}

.login-card button:hover {
    background: linear-gradient(45deg, #1d4ed8, #2563eb);
}

/* LINK */
.login-card a {
    color: #2563eb;
    text-decoration: none;
}

.login-card a:hover {
    text-decoration: underline;
}

</style> <!-- ✅ FIXED: STYLE CLOSED PROPERLY -->

</head>

<body>

<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>AI Interview Coach</h1>
        <p>Practice interviews with AI and improve your performance.</p>
    </div>

    <!-- STEPS -->
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="step-card">
                <i class="fa-solid fa-bullseye step-icon"></i>
                <h5>Enter Role</h5>
                <p>Select your target job role</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="step-card">
                <i class="fa-solid fa-question step-icon"></i>
                <h5>Get Questions</h5>
                <p>AI generates interview questions</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="step-card">
                <i class="fa-solid fa-pen step-icon"></i>
                <h5>Answer</h5>
                <p>Respond like real interview</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="step-card">
                <i class="fa-solid fa-chart-line step-icon"></i>
                <h5>Feedback</h5>
                <p>Get score & improvements</p>
            </div>
        </div>

    </div>

    <!-- LOGIN -->
    <div class="login-card">

        <h4 class="mb-3 text-center">
            <i class="fa-solid fa-lock"></i> Login
        </h4>

        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <input name="username" class="form-control mb-3" placeholder="Username" required>
            <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
            <button class="btn w-100">Login</button>
        </form>

        <p class="mt-3 text-center">
            <a href="signup.php">Create account</a>
        </p>

    </div>

</div>

</body>
</html>