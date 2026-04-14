<?php
require_once "../config/db.php";
require_once "../config/session.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$dateFilter  = $_GET['date']  ?? date('Y-m-d');
$roleFilter  = $_GET['role']  ?? '';
$statusFilter = $_GET['status'] ?? '';

$params = [':date' => $dateFilter];

$roleCond = $roleFilter ? "AND u.role = '$roleFilter'" : "AND (u.role = 'staff' OR u.role = 'student')";
$statusCond = '';
if ($statusFilter === 'present') $statusCond = "AND att.date IS NOT NULL";
elseif ($statusFilter === 'absent') $statusCond = "AND att.date IS NULL";

$sql = "SELECT u.username, u.role,
               CASE WHEN u.role = 'staff' THEN st.name ELSE aa.full_name END AS full_name,
               CASE WHEN u.role = 'staff' THEN CONCAT('STF', LPAD(st.id, 4, '0')) ELSE CONCAT('STU', LPAD(sd.id, 4, '0')) END AS user_id_code,
               att.date
        FROM users u
        LEFT JOIN staff_details st ON u.id = st.user_id AND u.role = 'staff'
        LEFT JOIN admission_applications aa ON u.id = aa.user_id
        LEFT JOIN student_details sd ON u.id = sd.user_id AND u.role = 'student'
        LEFT JOIN attendance att ON u.id = att.user_id AND DATE(att.date) = :date
        WHERE 1=1 $roleCond $statusCond
        ORDER BY u.role, u.username";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $records = [];
    $error = "Error: " . $e->getMessage();
}

$page = 'attendance';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Attendance Reports</h1>
    <p>View attendance records</p>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
</div>

<div class="card">
    <div class="card-header"><h2>Attendance Records</h2></div>

    <div class="filter-section">
        <div class="form-group">
            <label>Date:</label>
            <input type="date" value="<?php echo htmlspecialchars($dateFilter); ?>" onchange="location.href='?date='+this.value+'&role=<?php echo $roleFilter; ?>&status=<?php echo $statusFilter; ?>'">
        </div>
        <div class="form-group">
            <label>Role:</label>
            <select onchange="location.href='?date=<?php echo $dateFilter; ?>&status=<?php echo $statusFilter; ?>&role='+this.value">
                <option value="">All</option>
                <option value="student" <?php echo $roleFilter === 'student' ? 'selected' : ''; ?>>Students</option>
                <option value="staff" <?php echo $roleFilter === 'staff' ? 'selected' : ''; ?>>Staff</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select onchange="location.href='?date=<?php echo $dateFilter; ?>&role=<?php echo $roleFilter; ?>&status='+this.value">
                <option value="">All</option>
                <option value="present" <?php echo $statusFilter === 'present' ? 'selected' : ''; ?>>Present</option>
                <option value="absent" <?php echo $statusFilter === 'absent' ? 'selected' : ''; ?>>Absent</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Username</th><th>Role</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if ($records): ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($record['user_id_code']); ?></strong></td>
                            <td><?php echo htmlspecialchars($record['full_name'] ?? $record['username']); ?></td>
                            <td><?php echo htmlspecialchars($record['username']); ?></td>
                            <td><?php echo ucfirst($record['role']); ?></td>
                            <td><?php echo $record['date'] ? date('M d, Y', strtotime($record['date'])) : date('M d, Y', strtotime($dateFilter)); ?></td>
                            <td><span class="status-badge <?php echo $record['date'] ? 'active' : 'inactive'; ?>"><?php echo $record['date'] ? 'Present' : 'Absent'; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>