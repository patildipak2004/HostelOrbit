<?php
require_once "../config/db.php";
require_once "../config/session.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$statusFilter = $_GET['status'] ?? '';
$conditions = ['params' => [], 'sql' => ''];
if ($statusFilter) {
    $conditions['sql'] = 'WHERE a.status = ?';
    $conditions['params'][] = $statusFilter;
}

$sql = "SELECT a.*, u.username, u.email, sd.room_number as assigned_room FROM admission_applications a LEFT JOIN users u ON a.user_id = u.id LEFT JOIN student_details sd ON a.user_id = sd.user_id {$conditions['sql']} ORDER BY a.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($conditions['params']);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $applicationId = $_POST['application_id'];
    
    if ($action == 'approve') {
        $roomNumber = $_POST['room_number'];
        $userId = $_POST['user_id'];

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE admission_applications SET status = 'approved' WHERE id = ?");
            $stmt->execute([$applicationId]);

            $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO student_details (user_id, application_id, room_number, admission_date, status) VALUES (?, ?, ?, CURDATE(), 'active')");
            $stmt->execute([$userId, $applicationId, $roomNumber]);

            $pdo->commit();
            $success = 'Application approved successfully!';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Error approving application: ' . $e->getMessage();
        }
    } elseif ($action == 'reject') {
        try {
            $stmt = $pdo->prepare("UPDATE admission_applications SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$applicationId]);
            
            $stmt = $pdo->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$_POST['user_id']]);
            
            $success = 'Application rejected successfully!';
        } catch (PDOException $e) {
            $error = 'Error rejecting application: ' . $e->getMessage();
        }
    }
}

$page = 'admissions';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Admission Management</h1>
    <p>Manage student admission applications</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="filter-section">
    <div class="form-group">
        <select onchange="location.href='?status='+this.value">
            <option value="">All Status</option>
            <option value="pending" <?php echo $statusFilter == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="approved" <?php echo $statusFilter == 'approved' ? 'selected' : ''; ?>>Approved</option>
            <option value="rejected" <?php echo $statusFilter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
        </select>
    </div>
</div>

<div class="applications-grid">
    <?php foreach ($applications as $app): ?>
    <div class="application-card">
        <div class="card-header">
            <h3>Application #<?php echo $app['id']; ?></h3>
            <span class="status-badge <?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
        </div>

        <div class="application-content">
            <div class="info-row">
                <div class="info-section">
                    <h4>Personal Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Full Name:</strong>
                            <p><?php echo htmlspecialchars($app['full_name']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Username:</strong>
                            <p><?php echo htmlspecialchars($app['username']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <p><?php echo htmlspecialchars($app['email']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Mobile:</strong>
                            <p><?php echo htmlspecialchars($app['mobile']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Date of Birth:</strong>
                            <p><?php echo date('M d, Y', strtotime($app['dob'])); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Blood Group:</strong>
                            <p><?php echo htmlspecialchars($app['blood_group']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-section">
                    <h4>Academic Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>College:</strong>
                            <p><?php echo htmlspecialchars($app['college_id']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Course:</strong>
                            <p><?php echo htmlspecialchars($app['course_name']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>10th %:</strong>
                            <p><?php echo $app['percentage_10th']; ?>%</p>
                        </div>
                        <div class="info-item">
                            <strong>12th %:</strong>
                            <p><?php echo $app['percentage_12th']; ?>%</p>
                        </div>
                        <div class="info-item">
                            <strong>Last Passout:</strong>
                            <p><?php echo htmlspecialchars($app['last_passout']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Passout %:</strong>
                            <p><?php echo $app['last_passout_percentage']; ?>%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-section">
                    <h4>Parent Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Parent Name:</strong>
                            <p><?php echo htmlspecialchars($app['parent_name']); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Parent Mobile:</strong>
                            <p><?php echo htmlspecialchars($app['parent_mobile']); ?></p>
                        </div>
                        <div class="info-item full-width">
                            <strong>Parent Occupation:</strong>
                            <p><?php echo htmlspecialchars($app['parent_occupation']); ?></p>
                        </div>
                        <div class="info-item full-width">
                            <strong>Parent Address:</strong>
                            <p><?php echo htmlspecialchars($app['parent_address']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-section">
                    <h4>Application Status</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Applied On:</strong>
                            <p><?php echo date('M d, Y', strtotime($app['created_at'])); ?></p>
                        </div>
                        <div class="info-item">
                            <strong>Current Status:</strong>
                            <span class="status-badge <?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Room Assigned:</strong>
                            <p><?php echo $app['assigned_room'] ? htmlspecialchars($app['assigned_room']) : '-'; ?></p>
                        </div>
                        <div class="info-item full-width">
                            <strong>Student Address:</strong>
                            <p><?php echo htmlspecialchars($app['address']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-actions">
            <button onclick="viewDocuments(<?php echo $app['id']; ?>)" class="btn btn-view">📄 View Documents</button>
            <?php if ($app['status'] == 'pending'): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="approve">
                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $app['user_id']; ?>">
                    <select name="room_number" class="room-select" required>
                        <option value="">Select Room</option>
                        <?php for ($i = 1; $i <= 70; $i++): ?>
                            <option value="R-<?php echo $i; ?>">Room <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" onclick="return confirm('Approve this application?')" class="btn btn-approve">✓ Approve</button>
                </form>
                <form method="POST">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $app['user_id']; ?>">
                    <button type="submit" onclick="return confirm('Reject this application?')" class="btn btn-reject">✗ Reject</button>
                </form>
            <?php else: ?>
                <span class="no-action">This application has been processed</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($applications)): ?>
    <div class="no-applications">
        <h3>No applications found</h3>
        <p>There are no admission applications at the moment.</p>
    </div>
    <?php endif; ?>
</div>

<div id="documentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>View Documents</h2>
            <button class="btn-close" onclick="closeModal('documentModal')">&times;</button>
        </div>
        <div id="documentList"></div>
    </div>
</div>

<style>
.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(900px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.application-card {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card-header {
    background: var(--light-bg);
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
}

.application-content {
    padding: 1.5rem;
}

.info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-section {
    background: var(--light-bg);
    padding: 1.5rem;
    border-radius: 8px;
}

.info-section h4 {
    margin: 0 0 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary-color);
    border-bottom: 2px solid var(--accent-color);
    padding-bottom: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-item {
    margin-bottom: 0.75rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item strong {
    display: block;
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.info-item p {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 500;
}

.card-actions {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
}

.room-select {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 0.95rem;
    min-width: 200px;
}

.no-action {
    color: var(--text-secondary);
    font-style: italic;
    font-size: 0.9rem;
}

.no-applications {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-secondary);
}

.no-applications h3 {
    margin: 0 0 1rem 0;
    color: var(--primary-color);
}
</style>

<div id="documentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>View Documents</h2>
            <button class="btn-close" onclick="closeModal('documentModal')">&times;</button>
        </div>
        <div id="documentList"></div>
    </div>
</div>

<script>
function viewDocuments(applicationId) {
    fetch('get_documents.php?application_id=' + applicationId)
        .then(response => response.json())
        .then(data => {
            let html = '<ul class="document-list">';
            data.forEach(doc => {
                html += '<li><strong>' + doc.document_type + '</strong> <a href="../' + doc.file_path + '" target="_blank" class="btn btn-download">Download</a></li>';
            });
            html += '</ul>';
            document.getElementById('documentList').innerHTML = html;
            document.getElementById('documentModal').classList.add('active');
        })
        .catch(error => console.error('Error:', error));
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}
</script>

<?php include 'includes/footer.php'; ?>
