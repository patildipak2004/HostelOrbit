<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHostel Database Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .status {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .status-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            font-size: 16px;
        }
        .status-icon.success {
            color: #28a745;
        }
        .status-icon.error {
            color: #dc3545;
        }
        .status-icon.info {
            color: #17a2b8;
        }
        .status-icon.pending {
            color: #ffc107;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 13px;
        }
        .details h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .details p {
            margin: 5px 0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏠 SmartHostel</h1>
        <p class="subtitle">Database Setup Wizard</p>
        
        <?php
        $errors = [];
        $successes = [];
        
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'smarthostel';
        $sqlFile = __DIR__ . '/sql/smarthostel.sql';
        
        function testConnection($host, $username, $password) {
            try {
                $conn = new mysqli($host, $username, $password);
                return $conn;
            } catch (Exception $e) {
                return null;
            }
        }
        
        function createDatabase($conn, $database) {
            $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if ($conn->query($sql)) {
                return true;
            }
            return false;
        }
        
        function importSQL($conn, $database, $sqlFile) {
            if (!file_exists($sqlFile)) {
                return ['success' => false, 'message' => "SQL file not found: $sqlFile"];
            }
            
            $conn->select_db($database);
            
            $sql = file_get_contents($sqlFile);
            if ($sql === false) {
                return ['success' => false, 'message' => "Failed to read SQL file"];
            }
            
            $conn->multi_query($sql);
            
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
            
            if ($conn->errno) {
                return ['success' => false, 'message' => $conn->error];
            }
            
            return ['success' => true, 'message' => 'SQL imported successfully'];
        }
        
        if (isset($_POST['setup'])) {
            echo '<div class="message info">Starting database setup...</div>';
            
            $conn = testConnection($host, $username, $password);
            if (!$conn) {
                $errors[] = "Failed to connect to MySQL server. Please check if XAMPP MySQL is running.";
            } else {
                $successes[] = "Successfully connected to MySQL server";
                
                if (createDatabase($conn, $database)) {
                    $successes[] = "Database '$database' created successfully";
                    
                    $importResult = importSQL($conn, $database, $sqlFile);
                    if ($importResult['success']) {
                        $successes[] = "SQL file imported successfully";
                    } else {
                        $errors[] = $importResult['message'];
                    }
                } else {
                    $errors[] = "Failed to create database '$database'";
                }
                
                $conn->close();
            }
        }
        
        if (!empty($errors)) {
            echo '<div class="message error">';
            foreach ($errors as $error) {
                echo '<p>❌ ' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }
        
        if (!empty($successes)) {
            echo '<div class="message success">';
            foreach ($successes as $success) {
                echo '<p>✅ ' . htmlspecialchars($success) . '</p>';
            }
            echo '</div>';
        }
        
        if (empty($successes) || count($successes) < 3) {
        ?>
            <form method="POST">
                <button type="submit" name="setup" class="btn" id="setupBtn">
                    🚀 Setup Database
                </button>
            </form>
            
            <div class="details">
                <h3>📋 Setup Details</h3>
                <p><strong>Database:</strong> smarthostel</p>
                <p><strong>SQL File:</strong> sql/smarthostel.sql</p>
                <p><strong>MySQL Host:</strong> localhost</p>
                <p><strong>Username:</strong> root</p>
                <p><strong>Password:</strong> (empty)</p>
            </div>
            
            <div class="details">
                <h3>⚠️ Prerequisites</h3>
                <p>• XAMPP must be installed and running</p>
                <p>• MySQL service should be started</p>
                <p>• sql/smarthostel.sql file must exist</p>
            </div>
        <?php
        } else {
        ?>
            <div class="details">
                <h3>✨ Setup Complete!</h3>
                <p><strong>Database:</strong> smarthostel</p>
                <p><strong>Status:</strong> Ready to use</p>
                <p>You can now start using the SmartHostel application.</p>
            </div>
            
            <a href="index.php" class="btn" style="text-decoration: none; display: block; text-align: center;">
                🏠 Go to Homepage
            </a>
        <?php
        }
        ?>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>