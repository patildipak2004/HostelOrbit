<?php
require_once "../config/db.php";
require_once "../config/session.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$dateFilter = $_GET['date'] ?? date('Y-m-d');


$sql = "SELECT cc.*, sd.name as staff_name, u.username FROM cleaning_checklist cc 
        JOIN staff_details sd ON cc.staff_id = sd.user_id 
        JOIN users u ON cc.staff_id = u.id 
        WHERE cc.date = ? ORDER BY cc.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$dateFilter]);
$cleaningRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dbTotal = $pdo->query("SELECT COUNT(*) FROM cleaning_checklist")->fetchColumn();

$page = 'cleaning';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Cleaning Reports</h1>
    <p>View daily cleaning checklist reports</p>
    
    <?php if (empty($cleaningRecords)): ?>
        <div class="alert alert-info">
            <strong>No cleaning records found.</strong> Staff members need to submit their daily cleaning checklists.
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h2>Cleaning Records</h2>
    </div>
    
    <div class="filter-section">
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" value="<?php echo $dateFilter; ?>" onchange="location.href='?date='+this.value">
        </div>
        <div class="alert alert-info">
            <strong>Info:</strong> Records Found: <?php echo count($cleaningRecords); ?> | Database Total: <?php echo $dbTotal; ?>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Bathroom</th>
                    <th>Toilet</th>
                    <th>Washbasin</th>
                    <th>Porch</th>
                    <th>Office</th>
                    <th>Garbage</th>
                    <th>Corridor</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cleaningRecords as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['staff_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['username']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                    <td><?php echo $record['bathroom_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['toilet_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['washbasin_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['porch_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['office_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['garbage_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo $record['corridor_staircase_clean'] ? '✓' : '✗'; ?></td>
                    <td><?php echo htmlspecialchars($record['remarks'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($cleaningRecords)): ?>
                <tr>
                    <td colspan="12" style="text-align: center;">No cleaning records found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
