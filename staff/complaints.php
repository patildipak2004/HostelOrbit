<?php
require_once "../config/db.php";
require_once "../config/session.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO complaints (user_id, complaint_type, subject, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['complaint_type'],
            $_POST['subject'],
            $_POST['description']
        ]);
        $success = 'Complaint submitted successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$sql = "SELECT c.*, u.username, aa.full_name FROM complaints c JOIN users u ON c.user_id = u.id LEFT JOIN admission_applications aa ON c.user_id = aa.user_id ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'complaints';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Complaints</h1>
    <p>View and submit complaints</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Submit New Complaint</h2>
    </div>
    
    <form id="complaintForm" method="POST">
        <div class="form-group">
            <label>Complaint Type *</label>
            <select name="complaint_type" id="complaint_type" required>
                <option value="">Select Type</option>
                <option value="Cleaning">Cleaning</option>
                <option value="Water">Water</option>
                <option value="Electricity">Electricity</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Subject *</label>
            <input type="text" name="subject" id="subject" required>
        </div>
        <div class="form-group">
            <label>Description *</label>
            <textarea name="description" id="description" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Complaint</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Complaints</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>By</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?php echo htmlspecialchars($complaint['full_name'] ?? $complaint['username']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaint_type']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
                    <td><?php echo htmlspecialchars(substr($complaint['description'], 0, 50)) . '...'; ?></td>
                    <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                    <td><span class="status-badge <?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($complaints)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No complaints found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../assets/js/validation.js"></script>
<?php include 'includes/footer.php'; ?>
