<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$stats = [
    'total_students' => 0,
    'total_staff' => 0,
    'pending_admissions' => 0,
    'today_student_attendance' => 0,
    'today_staff_attendance' => 0
];

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student' AND status = 'active'");
$stats['total_students'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'staff' AND status = 'active'");
$stats['total_staff'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM admission_applications WHERE status = 'pending'");
$stats['pending_admissions'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM attendance a JOIN users u ON a.user_id = u.id WHERE a.date = CURDATE() AND u.role = 'student'");
$stats['today_student_attendance'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM attendance a JOIN users u ON a.user_id = u.id WHERE a.date = CURDATE() AND u.role = 'staff'");
$stats['today_staff_attendance'] = $stmt->fetchColumn();

$recentApplications = $pdo->query("SELECT a.*, u.username FROM admission_applications a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$recentComplaints = $pdo->query("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id = u.id WHERE c.status = 'pending' ORDER BY c.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$page = 'dashboard';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Warden Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <h3>Total Students</h3>
        <div class="value"><?php echo $stats['total_students']; ?></div>
    </div>
    <div class="stat-card success">
        <h3>Total Staff</h3>
        <div class="value"><?php echo $stats['total_staff']; ?></div>
    </div>
    <div class="stat-card warning">
        <h3>Pending Admissions</h3>
        <div class="value"><?php echo $stats['pending_admissions']; ?></div>
    </div>
    <div class="stat-card info">
        <h3>Today Student Attendance</h3>
        <div class="value"><?php echo $stats['today_student_attendance']; ?></div>
    </div>
    <div class="stat-card success">
        <h3>Today Staff Attendance</h3>
        <div class="value"><?php echo $stats['today_staff_attendance']; ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h2>Recent Admission Applications</h2>
            <a href="admissions.php" class="btn btn-view">View All</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentApplications as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                        <td><span class="status-badge <?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentApplications)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No applications found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Pending Complaints</h2>
            <a href="complaints.php" class="btn btn-view">View All</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>By</th>
                        <th>Subject</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentComplaints as $complaint): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($complaint['username']); ?></td>
                        <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 30)); ?>...</td>
                        <td><span class="status-badge <?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentComplaints)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No complaints found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
