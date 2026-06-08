<?php
include 'db.php';
session_start();

$stmt = $pdo->prepare("SELECT * FROM responses WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<h3>📊 Interview Report</h3>

<?php foreach($data as $row): ?>
<div class="card p-3 mt-3 shadow-sm">
    <b>Q:</b> <?php echo $row['question']; ?><br>
    <b>Your Answer:</b> <?php echo $row['user_answer']; ?><br>
    <b>Feedback:</b><br><?php echo nl2br($row['ai_feedback']); ?>
</div>
<?php endforeach; ?>

</div>

</body>
</html>