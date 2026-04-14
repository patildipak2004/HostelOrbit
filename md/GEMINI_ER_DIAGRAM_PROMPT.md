# Gemini AI Prompt for Generating Graphical ER Diagram

## Prompt for Gemini AI:

```
Create a detailed graphical Entity-Relationship (ER) diagram for a Smart Hostel Management System called HOSTELORBIT. Use the following database schema and requirements:

## Database Tables and Relationships:

### Core Tables:
1. **users** (Central table for all users)
   - id (Primary Key)
   - username (Unique)
   - email (Unique)
   - password
   - role (admin/student/staff)
   - status (active/inactive/pending)
   - created_at

2. **admission_applications**
   - id (Primary Key)
   - user_id (Foreign Key → users.id)
   - full_name, dob, mobile, address
   - blood_group, last_passout, last_passout_percentage
   - college_id, course_name, percentage_12th, percentage_10th
   - parent_name, parent_mobile, parent_occupation, parent_address
   - status (pending/approved/rejected)
   - created_at

3. **student_details**
   - id (Primary Key)
   - user_id (Foreign Key → users.id, Unique)
   - application_id (Foreign Key → admission_applications.id)
   - room_number
   - admission_date
   - parent_contact
   - status (active/inactive)

4. **staff_details**
   - id (Primary Key)
   - user_id (Foreign Key → users.id, Unique)
   - name
   - mobile
   - status (active/inactive)
   - created_at

### Operational Tables:
5. **documents**
   - id (Primary Key)
   - application_id (Foreign Key → admission_applications.id)
   - document_type
   - file_path
   - uploaded_at

6. **attendance**
   - id (Primary Key)
   - user_id (Foreign Key → users.id)
   - date
   - check_in_time
   - check_out_time
   - ip_address
   - created_at
   - Unique constraint: (user_id, date)

7. **complaints**
   - id (Primary Key)
   - user_id (Foreign Key → users.id)
   - complaint_type
   - subject
   - description
   - status (pending/resolved)
   - created_at
   - resolved_at

8. **leave_requests**
   - id (Primary Key)
   - user_id (Foreign Key → users.id)
   - leave_type
   - start_date
   - end_date
   - reason
   - status (pending/approved/rejected)
   - created_at

9. **cleaning_checklist**
   - id (Primary Key)
   - staff_id (Foreign Key → users.id)
   - date
   - bathroom_clean, toilet_clean, washbasin_clean
   - porch_clean, office_clean, garbage_clean
   - corridor_staircase_clean
   - remarks
   - created_at
   - Unique constraint: (staff_id, date)

10. **maintenance_reports**
    - id (Primary Key)
    - staff_id (Foreign Key → users.id)
    - report_type
    - description
    - status (pending/resolved)
    - created_at

11. **notices**
    - id (Primary Key)
    - title
    - content
    - created_by (Foreign Key → users.id)
    - created_at

12. **settings**
    - id (Primary Key)
    - setting_key (Unique)
    - setting_value
    - created_at
    - updated_at

## Key Relationships:
- users (1:1) → student_details
- users (1:1) → staff_details
- users (1:N) → admission_applications
- admission_applications (1:N) → documents
- users (1:N) → attendance
- users (1:N) → complaints
- users (1:N) → leave_requests
- users (1:N) → cleaning_checklist (for staff)
- users (1:N) → maintenance_reports (for staff)
- users (1:N) → notices (for admin)

## Requirements for the Diagram:

1. **Visual Style**: Create a professional ER diagram with clear boxes for entities, proper connecting lines, and cardinality indicators (1:1, 1:N, etc.)

2. **Entity Representation**:
   - Use rectangular boxes for each entity/table
   - Show entity name at the top
   - List attributes below with PK/FK indicators
   - Use different colors or styles for different types of entities (core vs operational)

3. **Relationship Lines**:
   - Use solid lines for relationships
   - Show cardinality (1:1, 1:N, N:M) at both ends
   - Label relationships clearly
   - Use crow's foot notation or similar standard

4. **Layout**:
   - Place the central "users" table in the middle
   - Arrange related tables around it logically
   - Group related entities together (admission-related, operational, etc.)
   - Ensure lines don't cross unnecessarily

5. **Color Coding**:
   - Blue/Green for core entities (users, student_details, staff_details)
   - Orange/Yellow for operational entities (attendance, complaints, etc.)
   - Red for administrative entities (notices, settings)

6. **Additional Elements**:
   - Include a legend explaining symbols and colors
   - Add title: "HOSTELORBIT Smart Hostel Management System - ER Diagram"
   - Show primary keys with (PK) and foreign keys with (FK)

7. **Output Format**: Generate a high-resolution image (PNG/JPG) that can be easily read and understood.

Please create a comprehensive and visually appealing ER diagram that clearly shows all the relationships and entities in this hostel management system database.
```

## How to Use This Prompt:

1. Copy the entire prompt above
2. Paste it into Gemini AI (Google Gemini/Bard)
3. Ask Gemini to generate the ER diagram based on this schema
4. The AI should produce a graphical representation of the database structure

## Expected Output:

- A visual ER diagram image showing all 12 entities
- Clear relationship lines with cardinality
- Professional layout suitable for documentation
- Color-coded entities for easy understanding

This prompt provides Gemini AI with all the necessary information to create an accurate and visually appealing ER diagram for the HOSTELORBIT system.
