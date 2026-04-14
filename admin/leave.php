<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php?role=admin');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'approve' || $action == 'reject') {
        try {
            $leaveId = $_POST['leave_id'];
            $status = $action == 'approve' ? 'approved' : 'rejected';
            
            $stmt = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
            $stmt->execute([$status, $leaveId]);
            
            $actionText = ucfirst($status);
            $success = "Leave request $actionText successfully!";
        } catch (PDOException $e) {
            $error = 'Error updating leave request: ' . $e->getMessage();
        }
    }
}

$statusFilter = $_GET['status'] ?? 'all';
$sql = "SELECT lr.*, u.username, u.role, CASE WHEN u.role = 'student' THEN CONCAT('STU', LPAD(sd.id, 4, '0')) ELSE CONCAT('STF', LPAD(st.id, 4, '0')) END as user_id_code
        FROM leave_requests lr 
        JOIN users u ON lr.user_id = u.id 
        LEFT JOIN student_details sd ON u.id = sd.user_id AND u.role = 'student'
        LEFT JOIN staff_details st ON u.id = st.user_id AND u.role = 'staff'
        " . ($statusFilter != 'all' ? "WHERE lr.status = '$statusFilter'" : '') . "
        ORDER BY lr.created_at DESC";

$stmt = $pdo->query($sql);
$leaveRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'leave';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Leave Management</h1>
    <p>Approve or reject leave requests from students and staff</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Filter Leave Requests</h2>
    </div>
    
    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
        <a href="leave.php" class="btn <?php echo $statusFilter == 'all' ? 'btn-primary' : ''; ?>">All</a>
        <a href="leave.php?status=pending" class="btn <?php echo $statusFilter == 'pending' ? 'btn-primary' : ''; ?>">Pending</a>
        <a href="leave.php?status=approved" class="btn <?php echo $statusFilter == 'approved' ? 'btn-primary' : ''; ?>">Approved</a>
        <a href="leave.php?status=rejected" class="btn <?php echo $statusFilter == 'rejected' ? 'btn-primary' : ''; ?>">Rejected</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Leave Requests</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaveRequests as $request): ?>
                <?php 
                    $start = new DateTime($request['start_date']);
                    $end = new DateTime($request['end_date']);
                    $days = $start->diff($end)->days + 1;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['user_id_code']); ?></td>
                    <td><?php echo htmlspecialchars($request['username']); ?></td>
                    <td><span style="text-transform: capitalize;"><?php echo $request['role']; ?></span></td>
                    <td><?php echo htmlspecialchars($request['leave_type']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($request['start_date'])); ?></td>
                    <td><?php echo date('M d, Y', strtotime($request['end_date'])); ?></td>
                    <td><strong><?php echo $days; ?></strong></td>
                    <td><?php echo htmlspecialchars(substr($request['reason'], 0, 40)) . '...'; ?></td>
                    <td><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                    <td><span class="status-badge <?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($request['status'] == 'pending'): ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this leave request?');">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="leave_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" class="btn btn-approve">Approve</button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this leave request?');">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="leave_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" class="btn btn-delete">Reject</button>
                                </form>
                            <?php elseif ($request['status'] == 'approved'): ?>
                                <span style="color: #10b981;">Approved</span>
                            <?php elseif ($request['status'] == 'rejected'): ?>
                                <span style="color: #000000;">Rejected</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leaveRequests)): ?>
                <tr>
                    <td colspan="12" style="text-align: center;">No leave requests found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Leave Statistics</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 8px;">
            <h3 style="color: #f59e0b;"><?php 
                echo array_filter($leaveRequests, fn($r) => $r['status'] == 'pending') ? count(array_filter($leaveRequests, fn($r) => $r['status'] == 'pending')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Pending</p>
        </div>
        <div style="text-align: center; padding: 20px; background: #d1fae5; border-radius: 8px;">
            <h3 style="color: #10b981;"><?php 
                echo array_filter($leaveRequests, fn($r) => $r['status'] == 'approved') ? count(array_filter($leaveRequests, fn($r) => $r['status'] == 'approved')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Approved</p>
        </div>
        <div style="text-align: center; padding: 20px; background: #fee2e2; border-radius: 8px;">
            <h3 style="color: #ef4444;"><?php 
                echo array_filter($leaveRequests, fn($r) => $r['status'] == 'rejected') ? count(array_filter($leaveRequests, fn($r) => $r['status'] == 'rejected')) : 0; 
            ?></h3>
            <p style="color: #6b7280;">Rejected</p>
        </div>
        <div style="text-align: center; padding: 20px; background: #e0f2fe; border-radius: 8px;">
            <h3 style="color: #3b82f6;"><?php echo count($leaveRequests); ?></h3>
            <p style="color: #6b7280;">Total Requests</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
