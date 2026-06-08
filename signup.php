<?php
include 'db.php';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (!$user || !$pass) {
        $msg = "All fields required!";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $check->execute([$user]);

        if ($check->fetch()) {
            $msg = "Username already exists!";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users(username,password) VALUES(?,?)");
            $stmt->execute([$user, $hash]);
            $msg = "Signup successful! <a href='index.php'>Login</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow mx-auto" style="max-width:400px;">
        <h3 class="mb-3">Create Account</h3>
        <p class="text-danger"><?php echo $msg; ?></p>

        <form method="POST">
            <input name="username" class="form-control mb-3" placeholder="Username">
            <input name="password" type="password" class="form-control mb-3" placeholder="Password">
            <button class="btn btn-primary w-100">Sign Up</button>
        </form>

        <p class="mt-3 text-center">
            <a href="index.php">Already have account?</a>
        </p>
    </div>
</div>

</body>
</html>