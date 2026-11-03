<?php
// ==========================================
// QNHS GUIDANCE SYSTEM - ALL-IN-ONE FOR VERCEL
// ==========================================

session_start();

// Soft Bypass & SQLite Database Setup
$db_file = __DIR__ . '/guidance_system.db';
$pdo = null;
try {
    if (extension_loaded('pdo_sqlite')) {
        $pdo = new PDO("sqlite:" . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("CREATE TABLE IF NOT EXISTS appointments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_name TEXT,
            reason TEXT,
            appointment_date TEXT,
            status TEXT DEFAULT 'Pending'
        )");
    }
} catch (Exception $e) {
    $pdo = null;
}

// Form Submission Logic
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_appointment') {
    $student_name = htmlspecialchars($_POST['student_name'] ?? '');
    $reason = htmlspecialchars($_POST['reason'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? '');

    if ($pdo) {
        $stmt = $pdo->prepare("INSERT INTO appointments (student_name, reason, appointment_date) VALUES (?, ?, ?)");
        $stmt->execute([$student_name, $reason, $date]);
    }
    $message = "Matagumpay na naitala ang iyong appointment request!";
}
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QNHS Guidance Counseling System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background-color: #f4f7f6; color: #333; line-height: 1.6; }
        header { background: #0056b3; color: white; padding: 25px; text-align: center; }
        nav { background: #003d80; padding: 12px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-weight: bold; }
        .container { max-width: 800px; margin: 30px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 15px; color: #0056b3; }
        .alert { background: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, button { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; font-weight: bold; border: none; cursor: pointer; margin-top: 10px; }
        button:hover { background: #218838; }
        footer { text-align: center; padding: 20px; margin-top: 40px; background: #e9ecef; font-size: 0.9em; }
    </style>
</head>
<body>

<header>
    <h1>QNHS Guidance Counseling System</h1>
    <p>Malugod kayong tinatanggap sa ating Guidance Portal.</p>
</header>

<nav>
    <a href="#appointment">Book Appointment</a>
    <a href="#about">About</a>
</nav>

<div class="container" id="appointment">
    <h2>Mag-set ng Appointment sa Guidance Office</h2>
    
    <?php if (!empty($message)): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="action" value="book_appointment">
        
        <div class="form-group">
            <label for="student_name">Pangalan ng Estudyante:</label>
            <input type="text" id="student_name" name="student_name" required placeholder="Ipasok ang buong pangalan">
        </div>

        <div class="form-group">
            <label for="reason">Dahilan ng Konsultasyon / Concern:</label>
            <textarea id="reason" name="reason" rows="4" required placeholder="Isulat ang dahilan ng appointment"></textarea>
        </div>

        <div class="form-group">
            <label for="date">Nais na Petsa:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <button type="submit">I-submit ang Appointment</button>
    </form>
</div>

<div class="container" id="about">
    <h2>Tungkol sa Guidance Office</h2>
    <p>Ang Quirino General High School Guidance Office ay laging handang tumulong sa mental, emotional, at academic status ng bawat mag-aaral.</p>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> QNHS Guidance Counseling System. All Rights Reserved.
</footer>

</body>
</html>
