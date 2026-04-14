<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

function getSubnet($ip) {
    $parts = explode('.', $ip);
    if (count($parts) >= 2) {
        return $parts[0] . '.' . $parts[1];
    }
    return $ip;
}

function isSameSubnet($ip1, $ip2) {
    $subnet1 = getSubnet($ip1);
    $subnet2 = getSubnet($ip2);
    return $subnet1 === $subnet2;
}

$currentIP = getRealIP();

$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'hostel_ip'");
$stmt->execute();
$hostelIP = $stmt->fetchColumn() ?? '10.198.135.236';
$hostelAdminIP = $hostelIP;
$isSameNetwork = isSameSubnet($currentIP, $hostelAdminIP);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if (!$isSameNetwork) {
        $_SESSION['error'] = 'Attendance can only be marked from hostel network! Please connect to hostel WiFi.';
    } else {
        try {
            if ($action == 'mark_attendance') {
                $stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = CURDATE()");
                $stmt->execute([$userId]);
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['error'] = 'Attendance already marked for today!';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO attendance (user_id, date, check_in_time, ip_address) VALUES (?, CURDATE(), CURTIME(), ?)");
                    $stmt->execute([$userId, $currentIP]);
                    $_SESSION['success'] = 'Attendance marked successfully!';
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
    }
    
    header('Location: attendance.php');
    exit;
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT 30");
$stmt->execute([$userId]);
$attendanceHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'attendance';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>My Attendance</h1>
    <p>Mark and view your attendance</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Mark Attendance</h2>
    </div>
    
    <p style="margin-bottom: 1rem; color: #6b7280;">
        Your IP: <strong><?php echo $currentIP; ?></strong> | 
        Hostel IP: <strong><?php echo $hostelAdminIP; ?></strong> | 
        Network Status: <strong style="color: <?php echo $isSameNetwork ? '#10b981' : '#ef4444'; ?>;">
        <?php echo $isSameNetwork ? '✓ Same Network' : '✗ Different Network'; ?>
        </strong>
    </p>
    
    <form method="POST">
        <input type="hidden" name="action" value="mark_attendance">
        <button type="submit" class="btn btn-primary" style="width: 100%;">Mark Today's Attendance</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Attendance History</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceHistory as $record): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                    <td><span class="status-badge active">Present</span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($attendanceHistory)): ?>
                <tr>
                    <td colspan="2" style="text-align: center;">No attendance records found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
