<?php
require_once "../config/db.php";
require_once "../config/session.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $complaintId = $_POST['complaint_id'];
    
    if ($action == 'resolve') {
        try {
            $stmt = $pdo->prepare("UPDATE complaints SET status = 'resolved', resolved_at = NOW() WHERE id = ?");
            $stmt->execute([$complaintId]);
            $success = 'Complaint resolved successfully!';
        } catch (PDOException $e) {
            $error = 'Error resolving complaint: ' . $e->getMessage();
        }
    }
}

$statusFilter = $_GET['status'] ?? '';
$conditions = ['params' => [], 'sql' => ''];
if ($statusFilter) {
    $conditions['sql'] = 'WHERE c.status = ?';
    $conditions['params'][] = $statusFilter;
}

$sql = "SELECT c.*, u.username, aa.full_name FROM complaints c JOIN users u ON c.user_id = u.id LEFT JOIN admission_applications aa ON c.user_id = aa.user_id {$conditions['sql']} ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($conditions['params']);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'complaints';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Complaint Management</h1>
    <p>View and resolve complaints</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>All Complaints</h2>
    </div>

    <div class="filter-section">
        <div class="form-group">
            <select onchange="location.href='?status='+this.value">
                <option value="">All Status</option>
                <option value="pending" <?php echo $statusFilter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="resolved" <?php echo $statusFilter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?php echo htmlspecialchars($complaint['full_name'] ?? $complaint['username']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['username']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaint_type']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
                    <td><?php echo htmlspecialchars(substr($complaint['description'], 0, 50)) . '...'; ?></td>
                    <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                    <td><span class="status-badge <?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></span></td>
                    <td>
                        <?php if ($complaint['status'] == 'pending'): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="resolve">
                            <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                            <button type="submit" onclick="return confirm('Mark this complaint as resolved?')" class="btn btn-approve">Resolve</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($complaints)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No complaints found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
