<?php
require_once "../config/db.php";
require_once "../config/session.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Check if checklist already submitted for today
        $stmt = $pdo->prepare("SELECT * FROM cleaning_checklist WHERE staff_id = ? AND date = CURDATE()");
        $stmt->execute([$userId]);
        if ($stmt->rowCount() > 0) {
            $error = 'Cleaning checklist already submitted for today!';
        } else {
            // Insert new checklist
            $stmt = $pdo->prepare("INSERT INTO cleaning_checklist (staff_id, date, bathroom_clean, toilet_clean, washbasin_clean, porch_clean, office_clean, garbage_clean, corridor_staircase_clean, remarks) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $userId,
                isset($_POST['bathroom_clean']),
                isset($_POST['toilet_clean']),
                isset($_POST['washbasin_clean']),
                isset($_POST['porch_clean']),
                isset($_POST['office_clean']),
                isset($_POST['garbage_clean']),
                isset($_POST['corridor_staircase_clean']),
                $_POST['remarks'] ?? ''
            ]);
            $success = 'Cleaning checklist submitted successfully!';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM cleaning_checklist WHERE staff_id = ? ORDER BY date DESC LIMIT 30");
$stmt->execute([$userId]);
$cleaningHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = 'cleaning';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Cleaning Checklist</h1>
    <p>Submit daily cleaning checklist</p>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Daily Cleaning Checklist</h2></div>
    <form method="POST">
        <div class="checklist-container">
            <div class="checklist-item"><label><input type="checkbox" name="bathroom_clean" value="1"> Bathroom Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="toilet_clean" value="1"> Toilet Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="washbasin_clean" value="1"> Wash Basin Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="porch_clean" value="1"> Porch Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="office_clean" value="1"> Office Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="garbage_clean" value="1"> Garbage Clean</label></div>
            <div class="checklist-item"><label><input type="checkbox" name="corridor_staircase_clean" value="1"> Corridor & Staircase Clean</label></div>
        </div>
        <div class="form-group"><label>Remarks</label><textarea name="remarks" rows="3"></textarea></div>
        <button type="submit" class="btn btn-primary">Submit Checklist</button>
    </form>
</div>

<div class="card">
    <div class="card-header"><h2>Cleaning History</h2></div>
    <div class="table-container">
        <table>
            <thead><tr><th>Date</th><th>Bathroom</th><th>Toilet</th><th>Washbasin</th><th>Porch</th><th>Office</th><th>Garbage</th><th>Corridor</th><th>Remarks</th></tr></thead>
            <tbody>
                <?php foreach ($cleaningHistory as $record): ?>
                <tr>
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
                <?php if (empty($cleaningHistory)): ?>
                <tr><td colspan="9" style="text-align: center;">No cleaning records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>