Phase 3: University Course Management System Team Members:

	1. Ryan Hermanet
	2. Mark Joseph
	3. Rohan Goyal
	
	An Android mobile app that accesses a MySQL database containing a university's course
	management data to provide some additional user-friendly functionality.
Features:

	1. A student can create an account.
	2. A student can browse all the courses offered in the current semester and can register for
	a specific section of a course if there is available space in the section. (Assume each
	section is limited to 15 students).
	3. A student can view a list of all courses they have taken and are currently taking.
	4. Instructors have access to records of all course sections they have taught, including
	names of current semester's enrolled students and the names and grades of students
	from past semesters.
	5. Creates a new table called rating. A student can rate a teacher from a course that they are 
	currently enrolled in from 0 to 10 once a week (the date value for the rating will be given 
	as the date for the Sunday of the current week). Any further attempts to rate the same professor 
	in the current week will only update the score value. Additionally, the admin can view the 
	ratings of the professors for a specific course, section, semester, and year and it will list 
	all the weekly ratings of all the students for that semester and section.


Installation & Setup:
Note: this project was tested using Android Studio Meerkat | 2024.3.1 Patch 1 

    1. Install MySQL and Apache (using XAMPP, WAMP, or standalone).
    2. Import the database schema:
		-Open PhpMyAdmin.
		-Run the script: DB2-tables.sql.
    3. Place all PHP & SQL files in the web server directory 
		(e.g., in XAMPP's htdocs folder, htdocs/Phase3).
    4. Update database credentials in config.php (if needed).
	5. Put all other downloaded files in a folder named db2, and open Android Studio.
		Press the open button, and select the db2 folder.
	6. Start the Apache & MySQL servers (e.g., from XAMPP).
	7. In the strings.xml file, enter the IP address found within XAMPP's 
		netstat window after http:// found in the string named "url". 
		E.g., if you named the folder in htdocs Phase3:
		<string name="url">http://XX.XXX.XX.XXX/Phase3/</string>
	8. Click File>Sync Project With Gradle Files. 
	* (Possible error)
	9. Click the green Run button at the top of the screen, and wait for the emulated phone
		and the mobile app to turn on.
	
	*
	If you get an error along the lines of:
		"The project is using an incompatible version (AGP 8.11.0-alpha06) of the Android 
		Gradle plugin. Latest supported version is AGP 8.9.1", 
		then navigate to build.gradle (Project: db2) in the hierarchy, hover over the .application
		part of line 3, and press CTRL/CMD + click. This will bring you to a file named 
		libs.version.toml. Change the value in agp = "..." to whatever the latest supported version
		listed in the error is, and go back to step 8.
