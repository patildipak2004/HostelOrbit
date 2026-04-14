<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostelIP = trim($_POST['hostel_ip']);
    $hostelName = trim($_POST['hostel_name']);
    $checkinRadius = trim($_POST['checkin_radius']);
    
    if (empty($hostelIP)) {
        $error = 'Hostel IP is required';
    } else {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute(['hostel_ip', $hostelIP, $hostelIP]);
            $stmt->execute(['hostel_name', $hostelName, $hostelName]);
            $stmt->execute(['checkin_radius', $checkinRadius, $checkinRadius]);
            
            $pdo->commit();
            $_SESSION['success'] = 'Settings updated successfully!';
            header('Location: settings.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Error updating settings: ' . $e->getMessage();
        }
    }
}

$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$hostelIP = $settings['hostel_ip'] ?? '10.198.135.236';
$hostelName = $settings['hostel_name'] ?? 'Smart Hostel';
$checkinRadius = $settings['checkin_radius'] ?? '500';

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$page = 'settings';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Settings</h1>
    <p>Configure hostel settings and network preferences</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Network Settings</h2>
    </div>
    
    <form method="POST">
        <!-- <div class="form-group">
            <label>Hostel Name *</label>
            <input type="text" name="hostel_name" value="<?php echo htmlspecialchars($hostelName); ?>" required>
        </div> -->
        
        <div class="form-group">
            <label>Hostel Network IP Address *</label>
            <input type="text" name="hostel_ip" value="<?php echo htmlspecialchars($hostelIP); ?>" required placeholder="e.g., 192.168.43.1 or 10.198.135.236">
            <small style="color: #6b7280;">
                Enter the gateway/router IP of hostel network. Students connecting to same subnet will be allowed to mark attendance.
            </small>
        </div>
        
        <!-- <div class="form-group">
            <label>Check-in Radius (meters) *</label>
            <input type="number" name="checkin_radius" value="<?php echo htmlspecialchars($checkinRadius); ?>" required min="50" max="5000" step="10">
            <small style="color: #6b7280;">
                Students must be within this distance from hostel (for GPS-based verification). Minimum: 50m, Maximum: 5000m
            </small>
        </div> -->
        
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Current Network Information</h2>
    </div>
    
    <div style="padding: 1rem; background: #f3f4f6; border-radius: 4px; font-size: 0.9rem;">
        <p><strong>Your Detected IP:</strong> <span style="color: #2563eb; font-weight: bold;"><?php echo getRealIP(); ?></span></p>
        <p><strong>Server IP:</strong> <?php echo $_SERVER['SERVER_ADDR']; ?></p>
        <p><strong>REMOTE_ADDR:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
        <p><strong>HTTP_CLIENT_IP:</strong> <?php echo $_SERVER['HTTP_CLIENT_IP'] ?? 'N/A'; ?></p>
        <p><strong>HTTP_X_FORWARDED_FOR:</strong> <?php echo $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'N/A'; ?></p>
        <p><strong>HTTP_X_REAL_IP:</strong> <?php echo $_SERVER['HTTP_X_REAL_IP'] ?? 'N/A'; ?></p>
        <?php if (isset($_SESSION['ip_debug'])): ?>
        <p><strong>Debug Info:</strong> <?php echo htmlspecialchars($_SESSION['ip_debug']); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>How to Find Hostel IP</h2>
    </div>
    
    <div style="padding: 1rem;">
        <h3>Method 1: Using Command Prompt (Windows)</h3>
        <ol style="margin-left: 20px; line-height: 1.8;">
            <li>Open Command Prompt (cmd)</li>
            <li>Type: <code>ipconfig</code></li>
            <li>Look for "Default Gateway" under your WiFi/Ethernet adapter</li>
            <li>That's your hostel network IP (usually ends with .1 or .254)</li>
        </ol>
        
        <h3 style="margin-top: 1.5rem;">Method 2: Using Router Admin Panel</h3>
        <ol style="margin-left: 20px; line-height: 1.8;">
            <li>Connect to hostel WiFi</li>
            <li>Open browser and go to: <code>192.168.1.1</code> or <code>192.168.0.1</code></li>
            <li>Login with admin credentials</li>
            <li>Look for "LAN IP Address" or "Router IP"</li>
        </ol>
        
        <h3 style="margin-top: 1.5rem;">Example IP Addresses:</h3>
        <ul style="margin-left: 20px; line-height: 1.8;">
            <li>192.168.1.1 (common for home routers)</li>
            <li>192.168.0.1 (alternative common IP)</li>
            <li>10.0.0.1 (enterprise networks)</li>
            <li>10.198.135.236 (your current network)</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
