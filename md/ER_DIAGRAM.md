# HOSTELORBIT - Entity-Relationship (ER) Diagram

## Database Schema Overview

The HOSTELORBIT system uses a MySQL relational database with the following main entities and relationships. This diagram shows the relationships between different entities in a visual format.

## Visual ER Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           HOSTELORBIT DATABASE ER DIAGRAM                       │
└─────────────────────────────────────────────────────────────────────────────────┘

                                    ┌─────────────────┐
                                    │     USERS      │
                                    │                 │
                                    │ • id (PK)       │
                                    │ • username      │
                                    │ • email         │
                                    │ • password      │
                                    │ • role          │
                                    │ • status        │
                                    │ • created_at    │
                                    └─────────────────┘
                                           │
                                           │ 1:N
                                           │
                    ┌──────────────────────┼──────────────────────┐
                    │                      │                      │
                    ▼                      ▼                      ▼
          ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
          │ ADMISSION_APPS  │    │ STUDENT_DETAILS │    │ STAFF_DETAILS   │
          │                 │    │                 │    │                 │
          │ • id (PK)       │    │ • id (PK)       │    │ • id (PK)       │
          │ • user_id (FK)  │    │ • user_id (FK)  │    │ • user_id (FK)  │
          │ • full_name     │    │ • application_id│    │ • name          │
          │ • dob           │    │ • room_number   │    │ • mobile        │
          │ • mobile        │    │ • admission_date│    │ • status        │
          │ • address       │    │ • parent_contact│    │ • created_at    │
          │ • status        │    │ • status        │    └─────────────────┘
          │ • created_at    │    └─────────────────┘
          └─────────────────┘             │
                    │                     │
                    │ 1:N                 │ 1:N
                    │                     │
                    ▼                     ▼
          ┌─────────────────┐    ┌─────────────────┐
          │   DOCUMENTS     │    │   ATTENDANCE    │
          │                 │    │                 │
          │ • id (PK)       │    │ • id (PK)       │
          │ • application_id│    │ • user_id (FK)  │
          │ • document_type │    │ • date          │
          │ • file_path     │    │ • check_in_time │
          │ • uploaded_at   │    │ • check_out_time│
          └─────────────────┘    │ • ip_address    │
                                 │ • created_at    │
                                 └─────────────────┘

                                           │
                                           │ 1:N
                                           │
                    ┌──────────────────────┼──────────────────────┐
                    │                      │                      │
                    ▼                      ▼                      ▼
          ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
          │   COMPLAINTS    │    │ LEAVE_REQUESTS  │    │ CLEANING_CHECK  │
          │                 │    │                 │    │                 │
          │ • id (PK)       │    │ • id (PK)       │    │ • id (PK)       │
          │ • user_id (FK)  │    │ • user_id (FK)  │    │ • staff_id (FK) │
          │ • complaint_type│    │ • leave_type    │    │ • date          │
          │ • subject       │    │ • start_date    │    │ • bathroom_clean│
          │ • description   │    │ • end_date      │    │ • toilet_clean  │
          │ • status        │    │ • reason        │    │ • remarks       │
          │ • created_at    │    │ • status        │    │ • created_at    │
          │ • resolved_at   │    │ • created_at    │    └─────────────────┘
          └─────────────────┘    └─────────────────┘
                    ▲                      ▲
                    │                      │
                    │ 1:N                 │ 1:N
                    │                      │
          ┌─────────────────┐    ┌─────────────────┐
          │ MAINTENANCE_RPT │    │    NOTICES      │
          │                 │    │                 │
          │ • id (PK)       │    │ • id (PK)       │
          │ • staff_id (FK) │    │ • title         │
          │ • report_type   │    │ • content       │
          │ • description   │    │ • created_by(FK)│
          │ • status        │    │ • created_at    │
          │ • created_at    │    └─────────────────┘
          └─────────────────┘

                                    ┌─────────────────┐
                                    │   SETTINGS      │
                                    │                 │
                                    │ • id (PK)       │
                                    │ • setting_key   │
                                    │ • setting_value │
                                    │ • created_at    │
                                    │ • updated_at    │
                                    └─────────────────┘
```

## Relationship Legend

```
┌─────────────────────────────────────────────────────────────────┐
│                    RELATIONSHIP TYPES                           │
├─────────────────────────────────────────────────────────────────┤
│ • ───────►  : One-to-Many Relationship                         │
│ • ◄──────►  : Many-to-Many Relationship                        │
│ • ────────  : One-to-One Relationship                          │
│ • 1:N       : One entity to Many entities                      │
│ • (PK)      : Primary Key                                      │
│ • (FK)      : Foreign Key                                      │
└─────────────────────────────────────────────────────────────────┘
```

## Entity Descriptions

### 1. users

**Primary Key:** id
**Description:** Stores all system users (admin, students, staff)
**Relationships:**

- One-to-Many with admission_applications
- One-to-One with student_details
- One-to-One with staff_details
- One-to-Many with attendance
- One-to-Many with complaints
- One-to-Many with leave_requests

### 2. admission_applications

**Primary Key:** id
**Description:** Student admission application details
**Relationships:**

- Many-to-One with users
- One-to-Many with documents
- One-to-One with student_details

### 3. documents

**Primary Key:** id
**Description:** Uploaded document files for admissions
**Relationships:**

- Many-to-One with admission_applications

### 4. student_details

**Primary Key:** id
**Description:** Additional student information after admission approval
**Relationships:**

- One-to-One with users
- One-to-One with admission_applications

### 5. staff_details

**Primary Key:** id
**Description:** Staff member information
**Relationships:**

- One-to-One with users

### 6. attendance

**Primary Key:** id
**Unique Constraint:** (user_id, date)
**Description:** Daily attendance records with IP tracking
**Relationships:**

- Many-to-One with users

### 7. complaints

**Primary Key:** id
**Description:** User complaints and their resolution status
**Relationships:**

- Many-to-One with users

### 8. leave_requests

**Primary Key:** id
**Description:** Student leave applications
**Relationships:**

- Many-to-One with users

### 9. cleaning_checklist

**Primary Key:** id
**Unique Constraint:** (staff_id, date)
**Description:** Daily cleaning tasks completed by staff
**Relationships:**

- Many-to-One with users (staff)

### 10. maintenance_reports

**Primary Key:** id
**Description:** Maintenance issues reported by staff
**Relationships:**

- Many-to-One with users (staff)

### 11. notices

**Primary Key:** id
**Description:** Administrative announcements
**Relationships:**

- Many-to-One with users (admin who created it)

### 12. events

**Primary Key:** id
**Description:** Hostel events and activities
**Relationships:**

- Many-to-One with users (admin who created it)

### 13. settings

**Primary Key:** id
**Unique Constraint:** setting_key
**Description:** System-wide configuration settings
**Relationships:** None (standalone configuration table)

## Key Relationships Summary

1. **Users Hierarchy:**

   - Users can be admin, student, or staff (role-based)
   - Students have admission_applications and student_details
   - Staff have staff_details

2. **Document Management:**

   - Each admission_application can have multiple documents
   - Documents are stored with file paths and metadata

3. **Attendance System:**

   - Each user can have multiple attendance records
   - Unique constraint prevents duplicate daily entries
   - IP addresses are logged for validation

4. **Complaint & Leave System:**

   - Users can submit multiple complaints and leave requests
   - Status tracking for resolution/approval workflow

5. **Staff Operations:**

   - Staff submit daily cleaning checklists
   - Staff report maintenance issues
   - Unique daily constraints for checklists

6. **Administrative Functions:**
   - Admins create notices and events
   - System settings stored in settings table

## Database Constraints

- **Primary Keys:** All entities have auto-incrementing integer primary keys
- **Foreign Keys:** Properly defined with CASCADE/SET NULL actions
- **Unique Constraints:** Username, email, attendance (user+date), cleaning (staff+date)
- **Data Types:** Appropriate VARCHAR lengths, DATE/TIME for temporal data
- **Enums:** Status fields use predefined values for data integrity

## Data Flow in Relationships

1. **Admission Process:** User → Admission Application → Documents → Student Details
2. **Daily Operations:** User → Attendance/Complaints/Leave Requests
3. **Staff Tasks:** User → Cleaning Checklists/Maintenance Reports
4. **Admin Tasks:** User → Notices/Events/Settings

This ER diagram provides a comprehensive view of the HOSTELORBIT database structure, showing how all entities interconnect to support the hostel management system's functionality.
