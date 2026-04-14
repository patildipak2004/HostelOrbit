<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $action = $_POST['action'];
     $userId = $_POST['user_id'];
     
     if ($action == 'toggle_status') {
         try {
             $stmt = $pdo->prepare("UPDATE student_details SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE user_id = ?");
             $stmt->execute([$userId]);
             $success = 'Student status updated successfully!';
         } catch (PDOException $e) {
             $error = 'Error updating status: ' . $e->getMessage();
         }
     } elseif ($action == 'assign_room') {
         try {
             $roomNumber = $_POST['room_number'];
             $stmt = $pdo->prepare("UPDATE student_details SET room_number = ? WHERE user_id = ?");
             $stmt->execute([$roomNumber, $userId]);
             $success = 'Room assigned successfully!';
         } catch (PDOException $e) {
             $error = 'Error assigning room: ' . $e->getMessage();
         }
     } elseif ($action == 'delete_student') {
         try {
             $pdo->beginTransaction();
             
             $stmt = $pdo->prepare("DELETE FROM attendance WHERE user_id = ?");
             $stmt->execute([$userId]);
             
             $stmt = $pdo->prepare("DELETE FROM complaints WHERE user_id = ?");
             $stmt->execute([$userId]);
             
             $stmt = $pdo->prepare("DELETE FROM leave_requests WHERE user_id = ?");
             $stmt->execute([$userId]);
             
             $stmt = $pdo->prepare("DELETE FROM student_details WHERE user_id = ?");
             $stmt->execute([$userId]);
             
             $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
             $stmt->execute([$userId]);
             
             $pdo->commit();
             $success = 'Student deleted successfully!';
         } catch (PDOException $e) {
             $pdo->rollBack();
             $error = 'Error deleting student: ' . $e->getMessage();
         }
     }
 }

$sql = "SELECT sd.*, u.username, u.email, aa.full_name, aa.mobile, aa.parent_name, CONCAT('STU', LPAD(sd.id, 4, '0')) as student_id
        FROM student_details sd 
        JOIN users u ON sd.user_id = u.id 
        JOIN admission_applications aa ON sd.application_id = aa.id 
        ORDER BY aa.created_at DESC";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'students';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Student Management</h1>
    <p>View and manage all students</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>All Students</h2>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Parent</th>
                    <th>Room</th>
                    <th>Admission Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($student['student_id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['username']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($student['parent_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['room_number'] ?? 'N/A'); ?></td>
                    <td><?php echo date('M d, Y', strtotime($student['admission_date'])); ?></td>
                    <td><span class="status-badge <?php echo $student['status']; ?>"><?php echo ucfirst($student['status']); ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="user_id" value="<?php echo $student['user_id']; ?>">
                                <button type="submit" class="btn <?php echo $student['status'] == 'active' ? 'btn-delete' : 'btn-approve'; ?>">
                                    <?php echo $student['status'] == 'active' ? 'Disable' : 'Enable'; ?>
                                </button>
                            </form>
                            <button onclick="assignRoom(<?php echo $student['user_id']; ?>, '<?php echo htmlspecialchars($student['room_number'] ?? ''); ?>')" class="btn btn-edit">Assign Room</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                <input type="hidden" name="action" value="delete_student">
                                <input type="hidden" name="user_id" value="<?php echo $student['user_id']; ?>">
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                <tr>
                    <td colspan="10" style="text-align: center;">No students found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="roomModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Assign Room</h2>
            <button class="btn-close" onclick="closeModal('roomModal')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="assign_room">
            <input type="hidden" id="roomUserId" name="user_id">
            <div class="form-group">
                <label>Room Number *</label>
                <input type="text" id="roomNumber" name="room_number" required pattern="R-\d{3}" title="Format: R-XXX (e.g., R-101)">
            </div>
            <button type="submit" class="btn btn-primary">Assign Room</button>
        </form>
    </div>
</div>

<script>
function assignRoom(userId, currentRoom) {
    document.getElementById('roomUserId').value = userId;
    document.getElementById('roomNumber').value = currentRoom || '';
    document.getElementById('roomModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}
</script>

<?php include 'includes/footer.php'; ?>
