Phase 2: University Course Management System
Team Members:
1. Ryan Hermanet 
2. Rohan Goyal
3. Mark Joseph

Installation & Setup:
1. Install MySQL and Apache (using XAMPP, WAMP, or standalone).
2. Import the database schema:
- Open MySQL or PhpMyAdmin.
- Run the script: `DB2-tables.sql`.
3. Place all PHP & HTML files in the web server directory (e.g., `htdocs` for XAMPP).
4. Update database credentials in `config.php` (if needed).
5. Start the Apache & MySQL servers.
6. Open `index.html` in a browser to use the system.

Features Outline:
- Student account creation & update.
- Course enrollment system with prerequisites & seat limits.
- Admin assigns instructors & schedules sections.
- Students view their course history & GPA.
- Instructor access to student records.
- TA and grader assignments based on eligibility.

Running the Demo:
- Open `index.html` in a web browser.
- Follow the form instructions to interact with the database.
- Video demo included (`demo_video.mp4`).


Features In-Depth:
1. A student can create an account and modify their information later. (The accounts for
admin and instructors are created in advance.)

2. The admin will be able to create a new course section and appoint instructor to teach the
section. Every course section is scheduled to meet at a specific time slot, with a limit of
two sections per time slot. Each instructor teaches one or two sections per semester.
Should an instructor be assigned two sections, the two sections must be scheduled in
consecutive time slots.

3. A student can browse all the courses offered in the current semester and can register for
a specific section of a course if they satisfy the prerequisite conditions and there is
available space in the section. (Assume each section is limited to 15 students).

4. A student can view a list of all courses they have taken and are currently taking, along
with the total number of credits earned and their cumulative GPA.

5. Instructors have access to records of all course sections they have taught, including
names of current semester's enrolled students and the names and grades of students
from past semesters.

6. Teaching Assistants (TAs), who are PhD students, will be assigned by the admin to
sections with more than 10 students. A PhD student is eligible to be a TA for only one
section.

7. Grader positions for sections with 5 to 10 students will be assigned by the admin with
either MS students or undergraduate students who have got A- or A in the course. If
there are more than one qualified candidates, the admin will choose one as the grader.
A student may serve as a grader for only one section.

8. The admin or instructor can appoint one or two instructors as advisor(s) for PhD
students, including a start date, and optional end date. The advisor will be able to view
the course history of their advisees, and update their advisees’ information.

9. Creates a new table called attendance. An instructor first selects a course and section. Then, the instructor chooses a student or multiple student from that section as well as a date that they were absent. Once they hit the submit button, it will create a new row in the attendance table with those values. For every two missed classes a student has in a course, they will go down a letter grade in that course.

10. Creates a new table called rating. A student can rate a teacher from a course that they are currently enrolled in from 0 to 10 once a week (the date value for the rating will be given as the date for the Sunday of the current week). Any further attempts to rate the same professor in the current week will only update the score value. Additionally, the admin can view the ratings of the professors for a specific course, section, semester, and year and it will list all the weekly ratings of all the students for that semester and section.