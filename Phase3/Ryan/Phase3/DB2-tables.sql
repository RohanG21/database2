SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS instructor;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS PhD;
DROP TABLE IF EXISTS master;
DROP TABLE IF EXISTS undergraduate;
DROP TABLE IF EXISTS classroom;
DROP TABLE IF EXISTS time_slot;
DROP TABLE IF EXISTS course;
DROP TABLE IF EXISTS section;
DROP TABLE IF EXISTS prereq;
DROP TABLE IF EXISTS advise;
DROP TABLE IF EXISTS TA;
DROP TABLE IF EXISTS masterGrader;
DROP TABLE IF EXISTS undergraduateGrader;
DROP TABLE IF EXISTS take;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS rating;
SET FOREIGN_KEY_CHECKS = 1;

create table account
	(email		varchar(50),
	 password	varchar(20) not null,
	 type		varchar(20),
	 primary key(email)
	);


create table department
	(dept_name	varchar(100), 
	 location	varchar(100), 
	 primary key (dept_name)
	);

create table instructor
	(instructor_id		varchar(10),
	 instructor_name	varchar(50) not null,
	 title 			varchar(30),
	 dept_name		varchar(100), 
	 email			varchar(50) not null,
	 primary key (instructor_id)
	);


create table student
	(student_id		varchar(10), 
	 name			varchar(20) not null, 
	 email			varchar(50) not null,
	 dept_name		varchar(100), 
	 primary key (student_id),
	 foreign key (dept_name) references department (dept_name)
		on delete set null
	);

create table PhD
	(student_id			varchar(10), 
	 qualifier			varchar(30), 
	 proposal_defence_date		date,
	 dissertation_defence_date	date, 
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table master
	(student_id		varchar(10), 
	 total_credits		int,	
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table undergraduate
	(student_id		varchar(10), 
	 total_credits		int,
	 class_standing		varchar(10)
		check (class_standing in ('Freshman', 'Sophomore', 'Junior', 'Senior')), 	
	 primary key (student_id),
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table classroom
	(classroom_id 		varchar(8),
	 building		varchar(15) not null,
	 room_number		varchar(7) not null,
	 capacity		numeric(4,0),
	 primary key (classroom_id)
	);

create table time_slot
	(time_slot_id		varchar(8),
	 day			varchar(10) not null,
	 start_time		time not null,
	 end_time		time not null,
	 primary key (time_slot_id)
	);

create table course
	(course_id		varchar(20), 
	 course_name		varchar(50) not null, 
	 credits		numeric(2,0) check (credits > 0),
	 primary key (course_id)
	);

create table section
	(course_id		varchar(20),
	 section_id		varchar(10), 
	 semester		varchar(6)
			check (semester in ('Fall', 'Winter', 'Spring', 'Summer')), 
	 year			numeric(4,0) check (year > 1990 and year < 2100), 
	 instructor_id		varchar(10),
	 classroom_id   	varchar(8),
	 time_slot_id		varchar(8),	
	 primary key (course_id, section_id, semester, year),
	 foreign key (course_id) references course (course_id)
		on delete cascade,
	 foreign key (instructor_id) references instructor (instructor_id)
		on delete set null,
	 foreign key (time_slot_id) references time_slot(time_slot_id)
		on delete set null
	);

create table prereq
	(course_id		varchar(20), 
	 prereq_id		varchar(20) not null,
	 primary key (course_id, prereq_id),
	 foreign key (course_id) references course (course_id)
		on delete cascade,
	 foreign key (prereq_id) references course (course_id)
	);

create table advise
	(instructor_id		varchar(8),
	 student_id		varchar(10),
	 start_date		date not null,
	 end_date		date,
	 primary key (instructor_id, student_id),
	 foreign key (instructor_id) references instructor (instructor_id)
		on delete  cascade,
	 foreign key (student_id) references PhD (student_id)
		on delete cascade
);

create table TA
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references PhD (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table masterGrader
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references master (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table undergraduateGrader
	(student_id		varchar(10),
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (student_id) references undergraduate (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade
);

create table take
	(student_id		varchar(10), 
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 grade		    	varchar(2)
		check (grade in ('A+', 'A', 'A-','B+', 'B', 'B-','C+', 'C', 'C-','D+', 'D', 'D-','F')), 
	 primary key (student_id, course_id, section_id, semester, year),
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade,
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table attendance
	(student_id		varchar(10), 
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 day			date,
	 primary key (student_id, course_id, section_id, semester, year, day),
	 foreign key (course_id, section_id, semester, year) references 
	     section (course_id, section_id, semester, year)
		on delete cascade,
	 foreign key (student_id) references student (student_id)
		on delete cascade
	);

create table rating
	(student_id		varchar(10), 
	 course_id		varchar(8),
	 section_id		varchar(10), 
	 semester		varchar(6),
	 year			numeric(4,0),
	 instructor_id	varchar(10),
	 week			date,
	 score			numeric(2,0) check (score <= 10 and score >= 0), 
	 primary key (student_id, course_id, section_id, semester, year, instructor_id, week),
	 foreign key (student_id) references student (student_id)
		on delete cascade,
	 foreign key (course_id, section_id, semester, year) references section (course_id, section_id, semester, year)
		on delete cascade,
	 foreign key (instructor_id) references instructor (instructor_id)
		on delete cascade
	);

insert into account (email, password, type) values ('admin@uml.edu', '123456', 'admin');
insert into account (email, password, type) values ('dbadams@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('slin@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Yelena_Rykalova@uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Johannes_Weis@uml.edu', '123456', 'instructor');
insert into account (email, password, type) values ('Charles_Wilkes@uml.edu', '123456', 'instructor');
INSERT INTO account (email, password, type) VALUES ('mattfensu@student.uml.edu', '123', 'student');

insert into course (course_id, course_name, credits) values ('COMP1010', 'Computing I', 3);
insert into course (course_id, course_name, credits) values ('COMP1020', 'Computing II', 3);
insert into course (course_id, course_name, credits) values ('COMP2010', 'Computing III', 3);
insert into course (course_id, course_name, credits) values ('COMP2040', 'Computing IV', 3);

INSERT INTO COURSE (course_id, course_name, credits) VALUES('MATH1320','Calculus II', 3);
INSERT INTO COURSE (course_id, course_name, credits) VALUES('MATH1310','Calculus I', 3);
INSERT INTO course (course_id, course_name, credits) VALUES('MATH2310','Calculus III',4);
INSERT INTO course (course_id, course_name, credits) VALUES('ENGN2050','Statics',3);
INSERT INTO course (course_id, course_name, credits) VALUES('CHEM1210','Chemistry I',3);
INSERT INTO course (course_id, course_name, credits) VALUES('PUBH2010','Community Health And Evironment', 3);


insert into department (dept_name, location) value ('Miner School of Computer & Information Sciences', 'Dandeneau Hall, 1 University Avenue, Lowell, MA 01854');
INSERT INTO department VALUES("Chemistry","Dandeneau Hall,1 University Avenue, Lowell,MA 01854");
INSERT INTO department VALUES("Mechanical & Industrial Engineering","Dandeneau Hall,1 University Avenue, Lowell,MA 01854");
INSERT INTO department VALUES("Mathematics & Statistics","Southwick Hall, 1 University Avenue, Lowell, MA 01854");
INSERT INTO department VALUES("Solomont School Of Nursing","Weed Hall,3 Solomont Way, Lowell, MA 01854");

insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('1', 'David Adams', 'Teaching Professor', 'Miner School of Computer & Information Sciences','dbadams@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('2', 'Sirong Lin', 'Associate Teaching Professor', 'Miner School of Computer & Information Sciences','slin@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('3', 'Yelena Rykalova', 'Associate Teaching Professor', 'Miner School of Computer & Information Sciences', 'Yelena_Rykalova@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('4', 'Johannes Weis', 'Assistant Teaching Professor', 'Miner School of Computer & Information Sciences','Johannes_Weis@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name, email) value ('5', 'Tom Wilkes', 'Assistant Teaching Professor', 'Miner School of Computer & Information Sciences','Charles_Wilkes@uml.edu');

INSERT INTO instructor VALUES(6,'Ronald Brent','Teaching Professor','Mathematics & Statistics', 'Ronald_Brent@uml.edu');
INSERT INTO instructor VALUES(7,'Robert Parkin','Teaching Professor','Mechanical & Industrial Engineering','Robert_Parkin@uml.edu');
INSERT INTO instructor VALUES(8,'Suri Iyer','Teaching Professor','Chemistry','suri_iyer@uml.edu');
INSERT INTO instructor VALUES(9,'Ainat Koren','Teaching Professor','Solomont School Of Nursing', 'Ainat_Koren@uml.edu');

insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS1', 'MoWeFr', '11:00:00', '11:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS2', 'MoWeFr', '12:00:00', '12:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS3', 'MoWeFr', '13:00:00', '13:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS4', 'TuTh', '11:00:00', '12:15:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS5', 'TuTh', '12:30:00', '13:45:00');

INSERT INTO classroom VALUES('SOU-100','Southwick Hall,1 University Avenue, Lowell, MA 01854','100',30);
INSERT INTO classroom VALUES('DAN-305','Dandeneau Hall,1 University Avenue, Lowell, MA 01854','305',40);
INSERT INTO classroom VALUES('DAN-402','Dandeneau Hall,1 University Avenue, Lowell, MA 01854','402',30);
INSERT INTO classroom VALUES('WED-205','Weed Hall,3 Solomont Way, Lowell, MA 01854','205',50);
INSERT INTO classroom VALUES('DAN-207','Dandeneau Hall, 1 University Avenue, Lowell, MA 01854','207',120);

insert into section values ('COMP1010', 'Section101', 'Fall', 2023, 3, 'DAN-207', 'TS1');
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section103', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section104', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section101', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section102', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section101', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2040', 'Section201', 'Spring', 2024);

insert into section values ('COMP1010', 'Section101', 'Spring', 2025, 1, 'DAN-305', 'TS1');
insert into section (course_id, section_id, semester, year) value ('MATH1310', 'Section101', 'Spring', 2025);
insert into section (course_id, section_id, semester, year) value ('MATH1310', 'Section101', 'Spring', 2024);

INSERT INTO student VALUES(100,"Ben James","benjames@student.uml.edu",'Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(101,"Sylvia Camber", "sylviacamber@student.uml.edu","Chemistry");
INSERT INTO student VALUES(102,"Charles Garrison", "charlesgarrison@student.uml.edu","Mechanical & Industrial Engineering");
INSERT INTO student VALUES(103,"Alex Harris", "alexharris@student.uml.edu","Mathematics & Statistics");
INSERT INTO student VALUES(104,"Aisha Bley","aishabley@student.uml.edu", "Solomont School Of Nursing"); 

INSERT INTO student VALUES(106,'Matt Fensu','mattfensu@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(107,'Neai Boko','neaiboko@student.uml.edu','Mechanical & Industrial Engineering');
INSERT INTO student VALUES(108,'Chi Deng', 'chideng@student.uml.edu','Chemistry');
INSERT INTO student VALUES(109,'Asam Setan','asamsetan@student.uml.edu','Mathematics & Statistics');
INSERT INTO student VALUES(110,'Christopher Samuels','christophersamuels@student.uml.edu','Solomont School Of Nursing');

INSERT INTO student VALUES(111,'Yvonne Asein','yvonneasein@student.uml.edu','Chemistry');
INSERT INTO student VALUES(112,'Isabella Matova','isabellamatova@student.uml.edu','Mechanical & Industrial Engineering');
INSERT INTO student VALUES(113,'Oliver Danton','oliverdanton@student.uml.edu','Mathematics & Statistics');
INSERT INTO student VALUES(114,'Priya Nagu','priyanagu@student.uml.edu','Solomont School Of Nursing');
INSERT INTO student VALUES(115,'Andes Rofello','andesrofello@student.uml.edu','Miner School of Computer & Information Sciences');

INSERT INTO student VALUES(116,'Isaac Shyam','isaacshyam@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(117,'Barnaby Alex','barnabyalex@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(118,'Manoj Huguo','manojhuguo@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(119,'Thales Heru','thalesheru@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(120,'Ruben Knight','rubenknight@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(121,'Tyrone Conrad','tyroneconrad@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(122,'Rod Shah','rodshah@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(123,'Tisha Zavala','tishazavala@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(124,'Harriet Farley','harrietfarley@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(125,'Duncan Leon','duncanleon@student.uml.edu','Miner School of Computer & Information Sciences');
INSERT INTO student VALUES(126,'Hubert Carrillo','hubertcarrillo@student.uml.edu','Miner School of Computer & Information Sciences');

INSERT INTO student VALUES(127,'Roscoe Fry','roscoefry@student.uml.edu','Miner School of Computer & Information Sciences');


INSERT INTO take values(116, 'COMP1010','Section101','Spring',2025, 'A+');
INSERT INTO take values(117, 'COMP1010','Section101','Spring',2025, 'A');
INSERT INTO take values(118, 'COMP1010','Section101','Spring',2025, 'A-');
INSERT INTO take values(119, 'COMP1010','Section101','Spring',2025, 'A-');
INSERT INTO take values(120, 'COMP1010','Section101','Spring',2025, 'B+');
INSERT INTO take values(121, 'COMP1010','Section101','Spring',2025, 'B');
INSERT INTO take values(122, 'COMP1010','Section101','Spring',2025, 'C+');
INSERT INTO take values(123, 'COMP1010','Section101','Spring',2025, 'C');
INSERT INTO take values(124, 'COMP1010','Section101','Spring',2025, 'D');
INSERT INTO take values(125, 'COMP1010','Section101','Spring',2025, 'D-');
INSERT INTO take values(126, 'COMP1010','Section101','Spring',2025, 'F');

INSERT INTO take values(101, 'COMP1010','Section101','Spring',2025, 'B+');
INSERT INTO take values(102, 'COMP1010','Section101','Spring',2025, 'B+');
INSERT INTO take values(104, 'COMP1010','Section101','Spring',2025, 'B+');
INSERT INTO take values(106, 'COMP1010','Section101','Spring',2025, 'B+');

INSERT INTO take values(116, 'MATH1310', 'Section101', 'Spring', 2025, 'A');
INSERT INTO take values(117, 'MATH1310', 'Section101', 'Spring', 2025, 'A-');
INSERT INTO take values(118, 'MATH1310', 'Section101', 'Spring', 2025, 'A');
INSERT INTO take values(119, 'MATH1310', 'Section101', 'Spring', 2025, 'B');
INSERT INTO take values(120, 'MATH1310', 'Section101', 'Spring', 2025, 'C+');
INSERT INTO take values(121, 'MATH1310', 'Section101', 'Spring', 2024, 'A');
INSERT INTO take values(122, 'MATH1310', 'Section101', 'Spring', 2024, 'A');

INSERT INTO phd VALUES(106,'Pass',20230516,20250516);
INSERT INTO phd VALUES(107,'Pass',20220925,20260312);
INSERT INTO phd VALUES(108,'Fail',NULL,NULL);
INSERT INTO phd VALUES(109,'Pass',20200208,20250406);
INSERT INTO phd VALUES(110,'Pass',20241103,20280119);

INSERT INTO phd VALUES(127,'Fail',NULL,NULL);
INSERT INTO phd VALUES(123,'Fail',NULL,NULL);


INSERT INTO advise VALUES(1,'106',20230516,20250516);
INSERT INTO advise VALUES(6,'109',20200208,20250406);
INSERT INTO advise VALUES(7,'107',20230903,20260312);
INSERT INTO advise VALUES(8,'108',20250220, NULL);
INSERT INTO advise VALUES(9,'110',20241108,20280119);

INSERT INTO section VALUES('CHEM1210','Section100','Spring','2025',8,'SOU-100','TS4');
INSERT INTO section VALUES('ENGN2050','Section100','Fall','2024',7,'DAN-305','TS2');
INSERT INTO section VALUES('COMP2010','Section100','Spring','2024',1,'DAN-402','TS3');
INSERT INTO section VALUES('PUBH2010','Section100','Spring','2025',9,'WED-205','TS4');
INSERT INTO section VALUES('MATH2310','Section100','Spring','2025',6,'SOU-100','TS5');

insert into prereq values ('COMP2040', 'COMP2010');
insert into prereq values ('COMP2010', 'COMP1020');
insert into prereq values ('COMP1020', 'COMP1010');
insert into prereq values ('MATH2310', 'MATH1320');
insert into prereq values ('MATH1320', 'MATH1310');

INSERT INTO take values(100, 'COMP1010','Section101','Fall',2023, 'A');
INSERT INTO take values(101, 'CHEM1210','Section100','Spring',2025, 'A+');
INSERT INTO take values(102, 'ENGN2050','Section100','Fall',2024, 'A-');
INSERT INTO take values(103, 'PUBH2010','Section100','Spring',2025, 'A+');
INSERT INTO take values(104, 'MATH2310','Section100','Spring',2025, 'A+');
INSERT INTO take VALUES(106, 'COMP2010', 'Section100', 'Spring', 2024, 'A');
INSERT INTO take values(103, 'ENGN2050','Section100','Fall',2024, 'A-');

INSERT INTO undergraduate values(100,58,'Junior');
INSERT INTO undergraduate values(101,14,'Freshman');
INSERT INTO undergraduate values(102,28,'Sophomore');
INSERT INTO undergraduate values(103,28,'Freshman');
INSERT INTO undergraduate values(104,28,'Sophomore');
INSERT INTO undergraduate values(121,30,'Sophomore');
INSERT INTO undergraduate values(116,0,'Freshman');

INSERT INTO master values(111,123);
INSERT INTO master values(112,150);
INSERT INTO master values(113,150);
INSERT INTO master values(114,146);
INSERT INTO master values(115,139);
INSERT INTO master values(122,150);

INSERT INTO undergraduategrader values(100, 'COMP1020','Section101','Spring',2024);
INSERT INTO undergraduategrader values(101, 'CHEM1210','Section100','Spring',2025);
INSERT INTO undergraduategrader values(102,'MATH2310','Section100','Spring',2025);
INSERT INTO undergraduategrader values(103,'PUBH2010','Section100','Spring',2025);
INSERT INTO undergraduategrader values(103,'ENGN2050','Section100','Fall',2024);

INSERT INTO mastergrader values(111,'CHEM1210','Section100','Spring',2025);
INSERT INTO mastergrader values(113,'MATH2310','Section100','Spring',2025);
INSERT INTO mastergrader values(112,'ENGN2050','Section100','Fall',2024);
INSERT INTO mastergrader values(114,'PUBH2010','Section100','Spring',2025);
INSERT INTO mastergrader values(115,'COMP1020','Section101','Spring',2024);

INSERT INTO TA values(106, 'COMP1020','Section101','Spring',2024);
INSERT INTO TA values(107, 'ENGN2050','Section100','Fall',2024);
INSERT INTO TA values(108, 'CHEM1210','Section100','Spring',2025);
INSERT INTO TA values(109, 'MATH2310','Section100','Spring',2025);
INSERT INTO TA values(110, 'PUBH2010','Section100','Spring',2025);

INSERT INTO attendance values(100, 'COMP1010','Section101','Fall',2023, '2023-11-17');
INSERT INTO attendance values(116, 'COMP1010','Section101','Spring',2025, '2025-03-17');
INSERT INTO attendance values(117, 'COMP1010','Section101','Spring',2025, '2023-03-17');
INSERT INTO attendance values(118, 'COMP1010','Section101','Spring',2025, '2023-03-17');
INSERT INTO attendance values(101, 'CHEM1210','Section100','Spring',2025, '2025-03-18');

INSERT INTO rating values(116, 'COMP1010', 'Section101', 'Spring', 2025, 1, '2025-02-02', 10);
INSERT INTO rating values(118, 'COMP1010', 'Section101', 'Spring', 2025, 1, '2025-02-02', 10);
INSERT INTO rating values(116, 'COMP1010', 'Section101', 'Spring', 2025, 1, '2025-02-16', 10);
INSERT INTO rating values(116, 'COMP1010', 'Section101', 'Spring', 2025, 1, '2025-02-23', 10);
INSERT INTO rating values(116, 'COMP1010', 'Section101', 'Spring', 2025, 1, '2025-03-02', 10);
