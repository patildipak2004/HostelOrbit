<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($page); ?> - HOSTELORBIT Student</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>HOSTELORBIT</h2>
                <p>Student Panel</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="<?php echo $page == 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a></li>
                <li><a href="attendance.php" class="<?php echo $page == 'attendance' ? 'active' : ''; ?>">📅 My Attendance</a></li>
                <li><a href="complaints.php" class="<?php echo $page == 'complaints' ? 'active' : ''; ?>">⚠️ Complaints</a></li>
                <li><a href="leave.php" class="<?php echo $page == 'leave' ? 'active' : ''; ?>">📝 Leave Request</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <span class="status-badge active">Student</span>
                </div>
                <a href="../logout.php" class="btn-logout">Logout</a>
            </div>
