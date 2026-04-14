<?php
require_once 'config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'student';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$username, $email, $password, $role]);
        $userId = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare("INSERT INTO admission_applications (user_id, full_name, dob, blood_group, mobile, address, last_passout, last_passout_percentage, college_id, course_name, percentage_12th, percentage_10th, parent_name, parent_mobile, parent_occupation, parent_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $_POST['full_name'],
            $_POST['dob'],
            $_POST['blood_group'],
            $_POST['mobile'],
            $_POST['address'],
            $_POST['last_passout'],
            $_POST['last_passout_percentage'],
            $_POST['college_id'],
            $_POST['course_name'],
            $_POST['percentage_12th'],
            $_POST['percentage_10th'],
            $_POST['parent_name'],
            $_POST['parent_mobile'],
            $_POST['parent_occupation'],
            $_POST['parent_address']
        ]);
        
        $applicationId = $pdo->lastInsertId();
        
        $documents = [
            'marksheet' => 'last_passout_marksheet',
            'marksheet_12th' => '12th_marksheet',
            'marksheet_10th' => '10th_marksheet',
            'admission_receipt' => 'college_admission_receipt',
            'aadhaar' => 'aadhaar_card'
        ];
        
        foreach ($documents as $fileField => $docType) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == 0) {
                $maxSize = 1 * 1024 * 1024;
                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
                
                if ($_FILES[$fileField]['size'] > $maxSize) {
                    $error = 'File size must be less than 1 MB';
                    break;
                }
                
                if (!in_array($_FILES[$fileField]['type'], $allowedTypes)) {
                    $error = 'Only JPG, PNG, and PDF files are allowed';
                    break;
                }
                
                $fileName = time() . '_' . $_FILES[$fileField]['name'];
                $uploadDir = 'uploads/documents/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $targetPath)) {
                    $stmt = $pdo->prepare("INSERT INTO documents (application_id, document_type, file_path) VALUES (?, ?, ?)");
                    $stmt->execute([$applicationId, $docType, $targetPath]);
                }
            }
        }
        
        $success = 'Application submitted successfully! Your application is pending approval. You will receive your login credentials after approval.';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HOSTELORBIT</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">HOSTELORBIT</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php" class="active">New Registration</a></li>
            </ul>
        </div>
    </nav>

    <section class="login-section">
        <div class="multistep-form" style="max-width: 800px;">
            <div class="section-title">
                <h2>New Student Registration</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p><a href="index.php" class="btn btn-primary">Back to Home</a></p>
            <?php else: ?>

            <form id="registrationForm" method="POST" enctype="multipart/form-data">
                <div class="progress-bar">
                    <div class="progress-step active">
                        <div class="step-number">1</div>
                        <p>Login Details</p>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">2</div>
                        <p>Personal</p>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">3</div>
                        <p>Education</p>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">4</div>
                        <p>Documents</p>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">5</div>
                        <p>Family</p>
                    </div>
                </div>

                <div class="form-step active" data-step="1">
                    <h3>Login Details</h3>
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
                    <div class="form-actions">
                        <button type="submit" class="btn btn-next">Next Step</button>
                    </div>
                </div>

                <div class="form-step" data-step="2">
                    <h3>Personal Details</h3>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label>Blood Group *</label>
                        <select name="blood_group" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="tel" name="mobile" required placeholder="10 digits">
                    </div>
                    <div class="form-group">
                        <label>Address *</label>
                        <textarea name="address" required rows="3"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev">Previous</button>
                        <button type="submit" class="btn btn-next">Next Step</button>
                    </div>
                </div>

                <div class="form-step" data-step="3">
                    <h3>Education Details</h3>
                    <div class="form-group">
                        <label>Last Passout *</label>
                        <select name="last_passout" required>
                            <option value="">Select</option>
                            <option value="10th">10th</option>
                            <option value="12th">12th</option>
                            <option value="UG">UG</option>
                            <option value="PG">PG</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Last Passout Percentage * (%)</label>
                        <input type="number" name="last_passout_percentage" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>College Name *</label>
                        <input type="text" name="college_id" required>
                    </div>
                    <div class="form-group">
                        <label>Course Name *</label>
                        <input type="text" name="course_name" required>
                    </div>
                    <div class="form-group">
                        <label>12th Percentage * (%)</label>
                        <input type="number" name="percentage_12th" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>10th Percentage * (%)</label>
                        <input type="number" name="percentage_10th" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev">Previous</button>
                        <button type="submit" class="btn btn-next">Next Step</button>
                    </div>
                </div>

                <div class="form-step" data-step="4">
                    <h3>Document Upload</h3>
                    <p style="margin-bottom: 15px; color: #6b7280;">Max file size: 1 MB | Allowed: JPG, PNG, PDF</p>
                    
                    <div class="form-group">
                        <label>Last Passout Marksheet *</label>
                        <input type="file" name="marksheet" required accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="form-group">
                        <label>12th Marksheet *</label>
                        <input type="file" name="marksheet_12th" required accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="form-group">
                        <label>10th Marksheet *</label>
                        <input type="file" name="marksheet_10th" required accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="form-group">
                        <label>College Admission Receipt *</label>
                        <input type="file" name="admission_receipt" required accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="form-group">
                        <label>Aadhaar Card *</label>
                        <input type="file" name="aadhaar" required accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev">Previous</button>
                        <button type="submit" class="btn btn-next">Next Step</button>
                    </div>
                </div>

                <div class="form-step" data-step="5">
                    <h3>Family Details</h3>
                    <div class="form-group">
                        <label>Parent/Guardian Name *</label>
                        <input type="text" name="parent_name" required>
                    </div>
                    <div class="form-group">
                        <label>Parent Mobile Number *</label>
                        <input type="tel" name="parent_mobile" required placeholder="10 digits">
                    </div>
                    <div class="form-group">
                        <label>Parent Occupation *</label>
                        <input type="text" name="parent_occupation" required>
                    </div>
                    <div class="form-group">
                        <label>Parent Address *</label>
                        <textarea name="parent_address" required rows="3"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-prev">Previous</button>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </div>
            </form>

            <?php endif; ?>
        </div>
    </section>

    <script src="assets/js/validation.js"></script>
</body>
</html>
