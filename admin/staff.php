<?php
require_once "../config/db.php";
require_once "../config/session.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add_staff') {
        try {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $name = trim($_POST['name']);
            $mobile = trim($_POST['mobile']);
            
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, 'staff', 'active')");
            $stmt->execute([$username, $email, $password]);
            $userId = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO staff_details (user_id, name, mobile, status) VALUES (?, ?, ?, 'active')");
            $stmt->execute([$userId, $name, $mobile]);
            
            $pdo->commit();
            $success = 'Staff member added successfully!';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Error adding staff: ' . $e->getMessage();
        }
    } elseif ($action == 'toggle_status') {
        try {
            $userId = $_POST['user_id'];
            $stmt = $pdo->prepare("UPDATE staff_details SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE user_id = ?");
            $stmt->execute([$userId]);
            $success = 'Staff status updated successfully!';
        } catch (PDOException $e) {
            $error = 'Error updating status: ' . $e->getMessage();
        }
    } elseif ($action == 'delete_staff') {
        try {
            $userId = $_POST['user_id'];
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("DELETE FROM staff_details WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            
            $pdo->commit();
            $success = 'Staff member deleted successfully!';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Error deleting staff: ' . $e->getMessage();
        }
    }
}

$sql = "SELECT sd.*, u.username, u.email, CONCAT('STF', LPAD(sd.id, 4, '0')) as staff_id FROM staff_details sd JOIN users u ON sd.user_id = u.id ORDER BY sd.created_at DESC";
$stmt = $pdo->query($sql);
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'staff';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Staff Management</h1>
    <p>Manage hostel staff members</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Add New Staff</h2>
    </div>
    <form method="POST">
        <input type="hidden" name="action" value="add_staff">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Mobile *</label>
                <input type="tel" name="mobile" required pattern="[0-9]{10}" title="10 digits">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Staff</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Staff</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $member): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($member['staff_id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                    <td><?php echo htmlspecialchars($member['username']); ?></td>
                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                    <td><?php echo htmlspecialchars($member['mobile']); ?></td>
                    <td><span class="status-badge <?php echo $member['status']; ?>"><?php echo ucfirst($member['status']); ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="user_id" value="<?php echo $member['user_id']; ?>">
                                <button type="submit" class="btn <?php echo $member['status'] == 'active' ? 'btn-delete' : 'btn-approve'; ?>">
                                    <?php echo $member['status'] == 'active' ? 'Disable' : 'Enable'; ?>
                                </button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.');">
                                <input type="hidden" name="action" value="delete_staff">
                                <input type="hidden" name="user_id" value="<?php echo $member['user_id']; ?>">
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($staff)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No staff found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
