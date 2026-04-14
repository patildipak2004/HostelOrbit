# HOSTELORBIT - Project ER Diagram

## Visual Entity-Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           HOSTELORBIT DATABASE ER DIAGRAM                       │
│                              Visual Representation                              │
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

## Relationship Lines Legend

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

### Core Entities

1. **USERS** - Central entity storing all system users (Admin/Warden, Students, Staff)

   - Primary Key: id
   - Contains: username, email, password, role (admin/student/staff), status
   - **Admin users** are identified by role='admin' and can create notices and manage the system

2. **ADMISSION_APPLICATIONS** - Student admission requests

   - Primary Key: id
   - Foreign Key: user_id → USERS
   - Contains: personal details, application status

3. **STUDENT_DETAILS** - Approved student information

   - Primary Key: id
   - Foreign Keys: user_id → USERS, application_id → ADMISSION_APPLICATIONS
   - Contains: room assignment, admission date

4. **STAFF_DETAILS** - Staff member information
   - Primary Key: id
   - Foreign Key: user_id → USERS
   - Contains: staff contact details

### Operational Entities

5. **DOCUMENTS** - Uploaded files for admissions

   - Primary Key: id
   - Foreign Key: application_id → ADMISSION_APPLICATIONS
   - Contains: file paths, document types

6. **ATTENDANCE** - Daily attendance records

   - Primary Key: id
   - Foreign Key: user_id → USERS
   - Contains: timestamps, IP addresses

7. **COMPLAINTS** - User complaints and issues

   - Primary Key: id
   - Foreign Key: user_id → USERS
   - Contains: complaint details, resolution status

8. **LEAVE_REQUESTS** - Student leave applications

   - Primary Key: id
   - Foreign Key: user_id → USERS
   - Contains: leave dates, approval status

9. **CLEANING_CHECKLIST** - Daily staff cleaning tasks

   - Primary Key: id
   - Foreign Key: staff_id → USERS
   - Contains: cleaning area statuses

10. **MAINTENANCE_REPORTS** - Facility maintenance issues

    - Primary Key: id
    - Foreign Key: staff_id → USERS
    - Contains: maintenance details

11. **NOTICES** - Administrative announcements

    - Primary Key: id
    - Foreign Key: created_by → USERS
    - Contains: notice content, creation date

12. **SETTINGS** - System configuration
    - Primary Key: id
    - Contains: key-value configuration pairs

## Key Relationships

- **USERS** connects to all other entities as the central user management table
- **ADMISSION_APPLICATIONS** links to **DOCUMENTS** for file uploads
- **USERS** links to **STUDENT_DETAILS** and **STAFF_DETAILS** for role-specific information
- **USERS** generates multiple **ATTENDANCE**, **COMPLAINTS**, and **LEAVE_REQUESTS** records
- **STAFF** users create **CLEANING_CHECKLIST** and **MAINTENANCE_REPORTS**
- **ADMIN** users create **NOTICES**

## Cardinality

- 1:1 - USERS to STUDENT_DETAILS/STAFF_DETAILS
- 1:N - USERS to ATTENDANCE/COMPLAINTS/LEAVE_REQUESTS
- 1:N - ADMISSION_APPLICATIONS to DOCUMENTS
- 1:N - STAFF to CLEANING_CHECKLIST/MAINTENANCE_REPORTS
- 1:N - ADMIN to NOTICES

This ER diagram visually represents the complete database structure of the HOSTELORBIT Smart Hostel Management System, showing how all entities interconnect to support the system's functionality.
