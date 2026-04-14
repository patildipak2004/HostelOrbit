<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT aa.*, sd.room_number FROM admission_applications aa LEFT JOIN student_details sd ON aa.user_id = sd.user_id WHERE aa.user_id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE user_id = ? AND MONTH(date) = MONTH(CURDATE())");
$stmt->execute([$userId]);
$monthlyAttendance = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM leave_requests WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$userId]);
$pendingLeaves = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$userId]);
$pendingComplaints = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT 5");
$stmt->execute([$userId]);
$recentAttendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $stmt = $pdo->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
// $stmt->execute();
// $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'dashboard';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Student Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($student['full_name'] ?? $_SESSION['username']); ?></p>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <h3>Monthly Attendance</h3>
        <div class="value"><?php echo $monthlyAttendance; ?></div>
    </div>
    <div class="stat-card warning">
        <h3>Pending Leaves</h3>
        <div class="value"><?php echo $pendingLeaves; ?></div>
    </div>
    <div class="stat-card danger">
        <h3>Pending Complaints</h3>
        <div class="value"><?php echo $pendingComplaints; ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h2>My Attendance</h2>
            <a href="attendance.php" class="btn btn-view">View All</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAttendance as $record): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                        <td><?php echo $record['check_in_time'] ? date('h:i A', strtotime($record['check_in_time'])) : '-'; ?></td>
                        <td><?php echo $record['check_out_time'] ? date('h:i A', strtotime($record['check_out_time'])) : '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentAttendance)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No attendance records</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notices section removed as requested -->
</div>

<div class="card">
    <div class="card-header">
        <h2>Quick Actions</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="attendance.php" class="btn btn-primary" style="text-align: center;">Mark Attendance</a>
        <a href="leave.php" class="btn btn-secondary" style="text-align: center;">Apply for Leave</a>
        <a href="complaints.php" class="btn btn-warning" style="text-align: center;">Submit Complaint</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
