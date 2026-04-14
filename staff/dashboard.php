<?php
require_once "../config/db.php";
require_once "../config/session.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM staff_details WHERE user_id = ?");
$stmt->execute([$userId]);
$staff = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE user_id = ? AND MONTH(date) = MONTH(CURDATE())");
$stmt->execute([$userId]);
$monthlyAttendance = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM cleaning_checklist WHERE staff_id = ? AND date = CURDATE()");
$stmt->execute([$userId]);
$todayCleaning = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints WHERE status = 'pending'");
$stmt->execute();
$pendingComplaints = $stmt->fetchColumn();

$page = 'dashboard';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Staff Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($staff['name'] ?? $_SESSION['username']); ?></p>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <h3>Monthly Attendance</h3>
        <div class="value"><?php echo $monthlyAttendance; ?></div>
    </div>
    <div class="stat-card success">
        <h3>Today's Cleaning</h3>
        <div class="value"><?php echo $todayCleaning ? 'Submitted' : 'Pending'; ?></div>
    </div>
    <div class="stat-card warning">
        <h3>Pending Complaints</h3>
        <div class="value"><?php echo $pendingComplaints; ?></div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>