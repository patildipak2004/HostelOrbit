<?php
// Include database and session configuration files
require_once '../config/db.php';
require_once '../config/session.php';

// Check if user is logged in and has 'staff' role; redirect if not
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header('Location: ../login.php');
    exit;
}

// Get current user ID from session
$userId = $_SESSION['user_id'];

// Function to extract subnet from IP (first two octets)
function getSubnet($ip) {
    $parts = explode('.', $ip);
    return count($parts) >= 2 ? $parts[0] . '.' . $parts[1] : $ip;
}

// Function to check if two IPs are in the same subnet
function isSameSubnet($ip1, $ip2) {
    return getSubnet($ip1) === getSubnet($ip2);
}

// Get real IP address of the user
$currentIP = getRealIP();

// Fetch hostel IP from settings table
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'hostel_ip'");
$stmt->execute();
$hostelIP = $stmt->fetchColumn() ?? '10.198.135.236';

// Check if current IP is in the same subnet as hostel IP
$isSameNetwork = isSameSubnet($currentIP, $hostelIP);

// Handle POST request for marking attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If not on same network, set error and redirect
    if (!$isSameNetwork) {
        $_SESSION['error'] = 'Attendance can only be marked from hostel network! Please connect to hostel WiFi.';
        header('Location: attendance.php');
        exit;
    }
    
    // Try to mark attendance
    try {
        // Check if attendance already marked for today
        $stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = CURDATE()");
        $stmt->execute([$userId]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'Attendance already marked for today!';
        } else {
            // Insert new attendance record
            $stmt = $pdo->prepare("INSERT INTO attendance (user_id, date, check_in_time, ip_address) VALUES (?, CURDATE(), CURTIME(), ?)");
            $stmt->execute([$userId, $currentIP]);
            $_SESSION['success'] = 'Attendance marked successfully!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
    // Redirect to avoid form resubmission
    header('Location: attendance.php');
    exit;
}

// Get success/error messages from session and unset them
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Fetch attendance history for the user (last 30 records)
$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT 30");
$stmt->execute([$userId]);
$attendanceHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set page variable for header inclusion
$page = 'attendance';
include 'includes/header.php';
?>

<!-- Dashboard header section -->
<div class="dashboard-header">
    <h1>My Attendance</h1>
    <p>Mark and view your attendance</p>
</div>

<!-- Display success message if set -->
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<!-- Display error message if set -->
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Card for marking attendance -->
<div class="card">
    <div class="card-header"><h2>Mark Attendance</h2></div>
    <!-- Display IP and network status -->
    <p style="margin-bottom: 1rem; color: #6b7280;">
        Your IP: <strong><?php echo $currentIP; ?></strong> | 
        Hostel IP: <strong><?php echo $hostelIP; ?></strong> | 
        Network Status: <strong style="color: <?php echo $isSameNetwork ? '#10b981' : '#ef4444'; ?>">
        <?php echo $isSameNetwork ? '✓ Same Network' : '✗ Different Network'; ?>
        </strong>
    </p>
    <!-- Form to submit attendance -->
    <form method="POST">
        <input type="hidden" name="action" value="mark_attendance">
        <button type="submit" class="btn btn-primary" style="width: 100%;">Mark Today's Attendance</button>
    </form>
</div>

<!-- Card for attendance history -->
<div class="card">
    <div class="card-header"><h2>Attendance History</h2></div>
    <div class="table-container">
        <table>
            <thead>
                <tr><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                <!-- Loop through attendance history -->
                <?php foreach ($attendanceHistory as $record): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                    <td><span class="status-badge active">Present</span></td>
                </tr>
                <?php endforeach; ?>
                <!-- If no records, show message -->
                <?php if (!$attendanceHistory): ?>
                <tr><td colspan="2" style="text-align: center;">No attendance records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>