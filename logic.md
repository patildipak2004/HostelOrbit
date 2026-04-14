# Smart Hostel - Implementation Logic Documentation

This document contains the actual code implementations for the core business logic of the Smart Hostel project.

## 1. Database Connection Logic
The project uses PDO for secure and efficient database interactions.

**File:** `config/db.php`
```php
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

---

## 2. User Authentication Logic
Validates user credentials and account status based on the selected role.

**File:** `login.php`
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
$stmt->execute([$username, $role]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] == $password) {
    if ($user['status'] != 'active') {
        $error = 'Your account is not active.';
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    }
}
```

---

## 3. Attendance IP Validation Logic
Ensures attendance is only marked from the hostel network.

**File:** `student/attendance.php`
```php
function isSameSubnet($ip1, $ip2) {
    $subnet1 = explode('.', $ip1)[0] . '.' . explode('.', $ip1)[1];
    $subnet2 = explode('.', $ip2)[0] . '.' . explode('.', $ip2)[1];
    return $subnet1 === $subnet2;
}
```

---

## 4. Leave Request Processing Logic
Handles the approval/rejection of leave requests by administrators.

**File:** `admin/leave.php`
```php
$stmt = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
$stmt->execute([$status, $leaveId]);
```

---

## 5. Cleaning Checklist Logic
Tracks daily cleaning tasks submitted by staff.

**File:** `staff/cleaning.php`
```php
$stmt = $pdo->prepare("INSERT INTO cleaning_checklist (staff_id, date, ...) VALUES (?, CURDATE(), ...)");
$stmt->execute([$userId, ...]);
```

---

## 6. Complaint Management Logic
Allows students to submit complaints category-wise.

**File:** `student/complaints.php`
```php
$stmt = $pdo->prepare("INSERT INTO complaints (user_id, complaint_type, subject, description) VALUES (?, ?, ?, ?)");
$stmt->execute([$userId, $type, $subject, $description]);
```

---

## 7. File Upload & Document Verification Logic
Handles student registration documents with size and type validation.

**File:** `register.php`
```php
foreach ($documents as $fileField => $docType) {
    if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if ($_FILES[$fileField]['size'] < 1048576 && in_array($_FILES[$fileField]['type'], $allowedTypes)) {
            $fileName = time() . '_' . $_FILES[$fileField]['name'];
            move_uploaded_file($_FILES[$fileField]['tmp_name'], 'uploads/documents/' . $fileName);
            // Insert into documents table...
        }
    }
}
```

---

## 8. Role-Based Access Control (RBAC) Logic
Secures routes by checking session variables and role permissions.

**File:** `config/session.php` & Headers
```php
// session.php
session_start();
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 86400)) {
    session_destroy();
}

// admin/dashboard.php (Example Header Check)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}
```

---

## 9. Admission Application Logic
Processing student applications with room assignment and account activation.

**File:** `admin/admissions.php`
```php
$pdo->beginTransaction();
$stmt = $pdo->prepare("UPDATE admission_applications SET status = 'approved' WHERE id = ?");
$stmt->execute([$applicationId]);

$stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
$stmt->execute([$userId]);

$stmt = $pdo->prepare("INSERT INTO student_details (user_id, application_id, room_number, admission_date, status) VALUES (?, ?, ?, CURDATE(), 'active')");
$stmt->execute([$userId, $applicationId, $roomNumber]);
$pdo->commit();
```

---

## 10. Unique Attendance Per Day Logic
Prevents duplicate attendance for the same user on the same date.

**File:** `student/attendance.php`
```php
$stmt = $pdo->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = CURDATE()");
$stmt->execute([$userId]);

if ($stmt->rowCount() > 0) {
    $error = 'Attendance already marked for today!';
} else {
    $stmt = $pdo->prepare("INSERT INTO attendance (user_id, date, check_in_time, ip_address) VALUES (?, CURDATE(), CURTIME(), ?)");
    $stmt->execute([$userId, $currentIP]);
}
```

---

## 11. Leave Request Submission Logic
Submission of leave applications by students/staff.

**File:** `student/leave.php`
```php
$stmt = $pdo->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$userId, $type, $start, $end, $reason]);
```

---

## 12. Complaint Resolution Logic
Allows administrators to mark complaints as resolved with a timestamp.

**File:** `admin/complaints.php`
```php
$stmt = $pdo->prepare("UPDATE complaints SET status = 'resolved', resolved_at = NOW() WHERE id = ?");
$stmt->execute([$complaintId]);
```
