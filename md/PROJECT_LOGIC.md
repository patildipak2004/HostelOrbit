# Smart Hostel Management System - Project Logic

## 1. System Architecture

### 1.1 Technology Stack

- **Backend**: PHP with PDO for database operations
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Session Management**: Custom PHP session handling with security configurations

### 1.2 User Roles

| Role    | Access Level       | Dashboard             |
| ------- | ------------------ | --------------------- |
| Admin   | Full System Access | admin/dashboard.php   |
| Staff   | Limited Management | staff/dashboard.php   |
| Student | Personal Features  | student/dashboard.php |

---

## 2. Core Modules & Logic

### 2.1 Authentication System

**Login Logic (login.php)**

```
1. User submits credentials (username, password, role)
2. Query users table with username and role
3. Verify password (plain text comparison)
4. Check account status (must be 'active')
5. Set session variables:
   - $_SESSION['user_id']
   - $_SESSION['username']
   - $_SESSION['email']
   - $_SESSION['role']
6. Redirect based on role:
   - admin → admin/dashboard.php
   - staff → staff/dashboard.php
   - student → student/dashboard.php
```

**Session Security (config/session.php)**

- Session lifetime: 24 hours (86400 seconds)
- HttpOnly cookies enabled
- SameSite: Lax
- Session regeneration on each request
- Auto-destroy on inactivity timeout
- IP detection function for audit trails

**Registration Logic (register.php)**

```
1. Multi-step form submission:
   - Step 1: Login Details (username, email, password)
   - Step 2: Personal Details (name, DOB, blood group, mobile, address)
   - Step 3: Education Details (college, course, percentages)
   - Step 4: Document Uploads (marksheets, Aadhaar)
   - Step 5: Family Details (parent info)

2. Database Operations:
   - INSERT into users table → status = 'pending'
   - INSERT into admission_applications table
   - INSERT documents into documents table (file upload)

3. Default role: 'student'
4. Default status: 'pending' (requires admin approval)
```

### 2.2 Admission Management (Admin)

**Application Processing (admin/admissions.php)**

```
1. View all applications with status filter
2. For pending applications:
   - Review personal, academic, and family details
   - View uploaded documents
   - Approve with room assignment OR Reject

3. Approval Process (Transaction):
   a. UPDATE admission_applications SET status = 'approved'
   b. UPDATE users SET status = 'active'
   c. INSERT into student_details with room_number

4. Rejection Process:
   a. UPDATE admission_applications SET status = 'rejected'
   b. UPDATE users SET status = 'inactive'
```

### 2.3 Attendance System

**Student Attendance (student/attendance.php)**

```
1. IP-Based Geofencing Logic:
   - Get user's current IP address
   - Get configured hostel IP from settings table
   - Compare first two octets (subnet)
   - Only allow attendance if on same network

2. Mark Attendance:
   - Check if already marked for today
   - INSERT into attendance table with:
     - user_id
     - date (CURDATE())
     - check_in_time (CURTIME())
     - ip_address

3. View History:
   - SELECT from attendance table
   - Display last 30 records
```

**Admin Attendance View (admin/attendance.php)**

```
- View all student attendances
- Filter by date
- Track attendance patterns
```

### 2.4 Leave Management

**Student Leave Request (student/leave.php)**

```
1. Submit leave request with:
   - leave_type (sick/emergency/other)
   - start_date
   - end_date
   - reason

2. INSERT into leave_requests table
3. Default status: 'pending'

3. Admin approval process:
   - Review request details
   - Approve/Reject
   - UPDATE leave_requests SET status
```

### 2.5 Complaint System

**Student Complaints (student/complaints.php)**

```
1. Submit complaint with:
   - complaint_type
   - subject
   - description

2. INSERT into complaints table
3. Default status: 'pending'

4. Admin resolution (admin/complaints.php):
   - View all complaints
   - Mark as resolved
   - UPDATE complaints SET status = 'resolved'
```

### 2.6 Cleaning Management (Staff)

**Daily Checklist (staff/cleaning.php)**

```
1. Staff daily cleaning tasks:
   - Bathroom cleaning
   - Toilet cleaning
   - Washbasin cleaning
   - Porch cleaning
   - Office cleaning
   - Garbage disposal
   - Corridor/staircase cleaning

2. INSERT into cleaning_checklist table
3. One entry per staff per day (UNIQUE constraint)
```

---

## 3. Database Schema & Relationships

### 3.1 Core Tables

```
users (id, username, email, password, role, status, created_at)
    ↓
    ├── admission_applications (user_id → users.id)
    ├── student_details (user_id → users.id)
    ├── staff_details (user_id → users.id)
    ├── attendance (user_id → users.id)
    ├── complaints (user_id → users.id)
    ├── leave_requests (user_id → users.id)
    └── notices (created_by → users.id)

admission_applications (id, user_id, personal_info, academic_info, status)
    ↓
    ├── documents (application_id → admission_applications.id)
    └── student_details (application_id → admission_applications.id)

student_details (id, user_id, room_number, admission_date, status)
staff_details (id, user_id, name, mobile, status)
attendance (id, user_id, date, check_in_time, check_out_time, ip_address)
complaints (id, user_id, complaint_type, subject, description, status)
leave_requests (id, user_id, leave_type, start_date, end_date, reason, status)
cleaning_checklist (id, staff_id, date, cleaning_tasks..., remarks)
notices (id, title, content, created_by)
maintenance_reports (id, staff_id, report_type, description, status)
settings (id, setting_key, setting_value)
```

### 3.2 Key Constraints

- UNIQUE(username) in users
- UNIQUE(email) in users
- UNIQUE(user_id, date) in attendance
- UNIQUE(staff_id, date) in cleaning_checklist

---

## 4. Security Features

### 4.1 Access Control

```
1. Role-based access checks on every page:
   if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
       header('Location: ../login.php');
       exit;
   }
```

### 4.2 Input Validation

- SQL injection prevention (PDO prepared statements)
- XSS prevention (htmlspecialchars for output)
- File upload validation (type, size)
- Form validation (required fields)

### 4.3 Session Security

- Secure session configuration
- Session timeout handling
- IP address tracking

---

## 5. Feature Workflows

### 5.1 New Student Admission Flow

```
1. Student visits index.php → clicks "Apply Now"
2. Fills multi-step registration form
3. Uploads required documents
4. Submit → Creates pending user account
5. Admin reviews application in admin/admissions.php
6. Admin approves with room assignment OR rejects
7. If approved: user status → 'active', student_details created
8. Student can now login and access dashboard
```

### 5.2 Daily Attendance Flow

```
1. Student connects to hostel WiFi
2. Visits student/attendance.php
3. System checks IP against hostel network
4. If same subnet: Allows attendance marking
5. If different network: Blocked with error message
6. Attendance recorded with timestamp and IP
```

### 5.3 Complaint Resolution Flow

```
1. Student submits complaint in student/complaints.php
2. Complaint status = 'pending'
3. Admin views in admin/complaints.php
4. Admin resolves the issue
5. Admin marks complaint as 'resolved'
6. Student sees updated status
```

---

## 6. Configuration & Settings

### 6.1 Key Settings (settings table)

| Setting Key    | Description                | Default Value  |
| -------------- | -------------------------- | -------------- |
| hostel_ip      | Allowed IP for attendance  | 10.198.135.236 |
| hostel_name    | Hostel name                | Smart Hostel   |
| checkin_radius | Attendance radius (meters) | 500            |

### 6.2 Default Admin Account

- Username: admin
- Email: admin@smarthostel.com
- Password: password
- Role: admin

---

## 7. File Structure Overview

```
Root Directory:
├── index.php              - Landing page with hostel info
├── login.php             - User authentication
├── register.php          - Student registration
├── logout.php            - Session destruction
├── setup.php             - Database setup (if needed)
│
├── config/
│   ├── db.php            - Database connection (PDO)
│   ├── session.php       - Session management
│   └── session_simple.php
│
├── admin/                - Admin dashboard & management
│   ├── dashboard.php     - Overview & stats
│   ├── admissions.php    - Application management
│   ├── attendance.php    - View all attendance
│   ├── complaints.php   - Handle complaints
│   ├── students.php      - Student management
│   ├── staff.php         - Staff management
│   ├── leave.php         - Leave approvals
│   ├── cleaning.php      - Cleaning schedules
│   ├── settings.php      - System settings
│   └── includes/         - Header, footer
│
├── student/              - Student features
│   ├── dashboard.php     - Student overview
│   ├── attendance.php   - Mark attendance
│   ├── leave.php        - Request leave
│   ├── complaints.php   - Submit complaints
│   └── includes/
│
├── staff/                - Staff features
│   ├── dashboard.php
│   ├── attendance.php
│   ├── cleaning.php      - Daily checklist
│   ├── leave.php
│   ├── complaints.php
│   └── includes/
│
├── assets/
│   ├── css/              - Stylesheets
│   └── js/               - JavaScript files
│
├── images/               - Gallery images
├── uploads/documents/    - Uploaded files
├── sql/
│   └── smarthostel.sql   - Database schema
└── md/                   - Documentation
```

---

## 8. Summary

This Smart Hostel Management System provides:

1. **Role-based access control** for admin, staff, and students
2. **Complete admission workflow** from application to approval
3. **IP-based attendance marking** with geofencing
4. **Leave and complaint management** for students
5. **Daily cleaning checklists** for staff
6. **Notice board** for announcements
7. **Settings management** for system configuration
8. **Document upload system** for admissions

The system follows a modular architecture with clear separation between frontend (HTML/CSS/JS) and backend (PHP), using PDO for secure database operations and session-based authentication for security.
