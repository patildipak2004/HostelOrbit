<?php
require_once 'config/db.php';
require_once 'config/session.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'student';
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['password'] == $password) {
            if ($user['status'] != 'active') {
                $error = 'Your account is not active. Please contact admin.';
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($role == 'admin') {
                    header('Location: admin/dashboard.php');
                } elseif ($role == 'staff') {
                    header('Location: staff/dashboard.php');
                } else {
                    header('Location: student/dashboard.php');
                }
                exit;
            }
        } else {
            $error = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        $error = 'Login error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HOSTELORBIT</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <section class="login-section">
        <div class="login-card">
            <h2>HOSTELORBIT Login</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>User Type</label>
                    <select name="role" id="role" onchange="updatePageTitle()">
                        <option value="student" <?php echo (($_GET['role'] ?? '') == 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="staff" <?php echo (($_GET['role'] ?? '') == 'staff') ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo (($_GET['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <p style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php">Back to Home</a>
            </p>
            
            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: #6b7280;">
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>
    </section>
    
    <script>
        function updatePageTitle() {
            const role = document.getElementById('role');
            const title = document.querySelector('.login-card h2');
            const roleText = role.options[role.selectedIndex].text;
            title.textContent = roleText + ' Login';
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updatePageTitle();
        });
    </script>
</body>
</html>
