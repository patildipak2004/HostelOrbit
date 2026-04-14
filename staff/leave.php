<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header('Location: ../login.php?role=staff');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $_POST['leave_type'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['reason']
        ]);
        $success = 'Leave request submitted successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$sql = "SELECT lr.*, CONCAT('STF', LPAD(st.id, 4, '0')) as staff_id
        FROM leave_requests lr 
        LEFT JOIN staff_details st ON lr.user_id = st.user_id 
        WHERE lr.user_id = ? 
        ORDER BY lr.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'leave';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>My Leave Requests</h1>
    <p>Apply and track your leave requests</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Apply for Leave</h2>
    </div>
    
    <form id="leaveForm" method="POST">
        <div class="form-group">
            <label>Leave Type *</label>
            <select name="leave_type" id="leave_type" required>
                <option value="">Select Type</option>
                <option value="Home">Home</option>
                <option value="Medical">Medical</option>
                <option value="Personal">Personal</option>
                <option value="Emergency">Emergency</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label>Start Date *</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>
            <div class="form-group">
                <label>End Date *</label>
                <input type="date" name="end_date" id="end_date" required>
            </div>
        </div>
        <div class="form-group">
            <label>Reason *</label>
            <textarea name="reason" id="reason" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>My Leave Requests</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Applied On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaves as $leave): ?>
                <?php 
                    $start = new DateTime($leave['start_date']);
                    $end = new DateTime($leave['end_date']);
                    $days = $start->diff($end)->days + 1;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
                    <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
                    <td><strong><?php echo $days; ?></strong></td>
                    <td><?php echo htmlspecialchars(substr($leave['reason'], 0, 40)) . '...'; ?></td>
                    <td><?php echo date('M d, Y', strtotime($leave['created_at'])); ?></td>
                    <td><span class="status-badge <?php echo $leave['status']; ?>"><?php echo ucfirst($leave['status']); ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leaves)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No leave requests found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Leave Status</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
        <div style="text-align: center; padding: 15px; background: #fef3c7; border-radius: 8px;">
            <h3 style="color: #f59e0b;"><?php 
                echo array_filter($leaves, fn($l) => $l['status'] == 'pending') ? count(array_filter($leaves, fn($l) => $l['status'] == 'pending')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Pending</p>
        </div>
        <div style="text-align: center; padding: 15px; background: #d1fae5; border-radius: 8px;">
            <h3 style="color: #10b981;"><?php 
                echo array_filter($leaves, fn($l) => $l['status'] == 'approved') ? count(array_filter($leaves, fn($l) => $l['status'] == 'approved')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Approved</p>
        </div>
        <div style="text-align: center; padding: 15px; background: #fee2e2; border-radius: 8px;">
            <h3 style="color: #ef4444;"><?php 
                echo array_filter($leaves, fn($l) => $l['status'] == 'rejected') ? count(array_filter($leaves, fn($l) => $l['status'] == 'rejected')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Rejected</p>
        </div>
        <div style="text-align: center; padding: 15px; background: #e0f2fe; border-radius: 8px;">
            <h3 style="color: #3b82f6;"><?php echo count($leaves); ?></h3>
            <p style="color: #6b7280;">Total</p>
        </div>
    </div>
</div>

<script src="../assets/js/validation.js"></script>
<?php include 'includes/footer.php'; ?>
