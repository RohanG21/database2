
INSERT INTO department VALUES("Miner School Of Computer & Information Sciences","Dandeneau Hall,1 University Avenue,Lowell,MA 01854")
INSERT INTO department VALUES("Chemistry","Dandeneau Hall,1 University Avenue, Lowell,MA 01854");
INSERT INTO department VALUES("Mechanical Engineering & Industrial Engineering","Dandeneau Hall,1 University Avenue, Lowell,MA 01854");
INSERT INTO department VALUES("Mathematics & Statistics","Southwick Hall, 1 University Avenue, Lowell, MA 01854");
INSERT INTO department VALUES("Solomont School Of Nursing","Weed Hall,3 Solomont Way, Lowell, MA 01854");

INSERT INTO student VALUES(100,"Ben James","benjames@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(101,"Sylvia Camber", "sylviacamber@student.uml.edu","Chemistry");
INSERT INTO student VALUES(102,"Charles Garrison", "charlesgarrison@student.uml.edu","Mechanical Engineering & Industrial Engineering");
INSERT INTO student VALUES(103,"Alex Harris", "Mathematics & Statistics");
INSERT INTO student VALUES(104,"Aisha Bley","aishabley@student.uml.edu", "Solomont School Of Nursing");

INSERT INTO student VALUES(105,"Jonathan Pike","jonathanpike@student.uml.edu","Mathematics & Statistics");
INSERT INTO student VALUES(106,"Harley Karris","harleykarris@student.uml.edu","Solomont School Of Nursing");
INSERT INTO student VALUES(107,"Makeh Onije","makehonije@student.uml.edu","Mechanical Engineering & Industrial Engineering");
INSERT INTO student VALUES(108,"Vanessa Sanchez", "vanessasanchez@student.uml.edu","Chemistry");
INSERT INTO student VALUES(109,"Anish Kalia", "anishkalia@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(110,"Baylor Hinsey","baylorhinsey@student.uml.edu","Chemistry");
INSERT INTO student VALUES(111,"Jack Thomas","jackthomas@student.uml.edu","Chemistry");
INSERT INTO student VALUES(112,"Chelsea Melise","chelseamelise@student.uml.edu","Chemistry");
INSERT INTO student VALUES(113,"Kevin Navik", "kevinnavik@student.uml.edu","Chemistry");
INSERT INTO student VALUES(114,"Lauren Evij","laurenevij@student.uml.edu","Chemistry");
INSERT INTO student VALUES(115,"Warren Nicholson","warrennicholson@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(116,"Eliz Gomez","elizgomez@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(117,"Ted Baven","tedbaven@student.uml.edu","Mathematics & Statistics");
INSERT INTO student VALUES(118,"Yanike Shoto","yanikeshoto@student.uml.edu","Mechanical Engineering & Industrial Engineering");
INSERT INTO student VALUES(119,"Urevi Krevk","urevikrevk@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(120,"Ingei Tase ","ingeitase@student.uml.edu","Solomont School Of Nursing");
INSERT INTO student VALUES(121,"Obe Hano", "obehano@student.uml.edu","Chemistry");
INSERT INTO student VALUES(122,"Peter Daven","peterdaven@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(123,"Aven Menre","avenmenre@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(124,"Samuel Naten","samuelnaten@student.uml.edu","Miner School Of Computer & Information Sciences");
INSERT INTO student VALUES(125,"Feren Telal","ferentelal@student.uml.edu","Miner School Of Computer & Information Sciences");



INSERT INTO student VALUES(106,'Matt Fensu','mattfensu@student.uml.edu','Miner School Of Computer And Information Sciences');
INSERT INTO student VALUES(107,'Neai Boko','neaiboko@student.uml.edu','Mechanical & Industrial Engineering');
INSERT INTO student VALUES(108,'Chi Deng', 'chideng@student.uml.edu','Chemistry');
INSERT INTO student VALUES(109,'Asam Setan','asamsetan@student.uml.edu','Mathematics & Statistics');
INSERT INTO student VALUES(110,'Christopher Samuels','christophersamuels@student.uml.edu','Solomont School Of Nursing');

INSERT INTO instructor VALUES(6,'Ronald Brent','Teaching Professor','Mathematics & Statistics', 'Ronald_Brent@uml.edu');
INSERT INTO instructor VALUES(7,'Robert Parkin','Teaching Professor','Mechanical & Industrial Engineering','Robert_Parkin@uml.edu');
INSERT INTO instructor VALUES(8,'Suri Iyer','Teaching Professor','Chemistry','suri_iyer@uml.edu');
INSERT INTO instructor VALUES(9,'Ainat Koren','Teaching Professor','Solomont School Of Nursing', 'Ainat_Koren@uml.edu');

INSERT INTO phd VALUES('106',1,20230516,20250516);
INSERT INTO phd VALUES('107',1,20220925,20260312);
INSERT INTO phd VALUES('108',0,NULL,NULL);
INSERT INTO phd VALUES('109',1,20200208,20250406);
INSERT INTO phd VALUES('110',1,20241103,20280119);


INSERT INTO advise VALUES(1,'106',20230516,20250516);
INSERT INTO advise VALUES(6,'109',20200208,20250406);
INSERT INTO advise VALUES(7,'107',20230903,20260312);
INSERT INTO advise VALUES(8,'108',20250220, NULL);
INSERT INTO advise VALUES(9,'110',20241108,20280119);


INSERT INTO course VALUES('MATH2310','Calculus III',4);
INSERT INTO course VALUES('ENGN2050','Statics',3);
INSERT INTO course VALUES('CHEM1210','Chemistry I',3);
INSERT INTO course VALUES('PUBH2010','Community Health And Evironment', 3);
INSERT INTO course VALUES('PHYS1410','Physics I', 3);
INSERT INTO course VALUES('MATH1320','Calculus II',4);

INSERT INTO student VALUES('111','Yvonne Asein','yvonneasein@student.uml.edu','Chemistry');
INSERT INTO student VALUES('112','Isabella Matova','isabellamatova@student.uml.edu','Mechanical & Industrial Engineering');
INSERT INTO student VALUES('113','Oliver Danton','oliverdanton@student.uml.edu','Mathematics & Statistics');
INSERT INTO student VALUES('114','Priya Nagu','priyanagu@student.uml.edu','Solomont School Of Nursing');
INSERT INTO student VALUES('115','Andes Rofello','andesrofello@student.uml.edu','Miner School Of Computer And Information Sciences');

INSERT INTO section VALUES('CHEM1210','Section100','Spring','2025',8,NULL,'TS1')

INSERT INTO classroom VALUES('SOU-100','Southwick Hall,1 University Avenue, Lowell, MA 01854','100',30);
INSERT INTO classroom VALUES('DAN-305','Dandeneau Hall,1 University Avenue, Lowell, MA 01854','305',40);
INSERT INTO classroom VALUES('DAN-402','Dandeneau Hall,1 University Avenue, Lowell, MA 01854','402',30);
INSERT INTO classroom VALUES('WED-205','Weed Hall,3 Solomont Way, Lowell, MA 01854','205',50);
INSERT INTO classroom VALUES('DAN-207','Dandeneau Hall, 1 University Avenue, Lowell, MA 01854','207',120);

INSERT INTO section VALUES('CHEM1210','Section100','Spring','2025',8,NULL,'TS1');
INSERT INTO section VALUES('ENGN2050','Section100','Fall','2024',7,'DAN-305','TS2');
INSERT INTO section VALUES('COMP2010','Section100','Spring','2024',1,'DAN-402','TS3');
INSERT INTO section VALUES('PUBH2010','Section100','Spring','2025',9,'WED-205','TS4');
INSERT INTO section VALUES('MATH2310','Section100','Spring','2025',6,'SOU-100','TS5');

INSERT INTO account VALUES('aishabley@student.uml.edu','aishageisha','student');
INSERT INTO account VALUES('alexharris@student.uml.edu','alexchex','student');
INSERT INTO account VALUES('benjames@student.uml.edu','benisthebest','student');
INSERT INTO account VALUES('charlesgarrison@student.uml.edu','charliehorse','student');
INSERT INTO account VALUES('sylviacamber@student.uml.edu','syllama','student');

INSERT INTO account VALUES('mattfensu@student.uml.edu','fencingfensu','student')
INSERT INTO account VALUES('neaiboko@student.uml.edu','bokoloko','student')
INSERT INTO account VALUES('chideng@student.uml.edu','taichi','student')
INSERT INTO account VALUES('asamsetan@student.uml.edu','asamsetan123','student')
INSERT INTO account VALUES('christophersamuels@student.uml.edu','gophertopher','student')

INSERT INTO account VALUES('Ronald_Brent@uml.edu','ronaldosuii','instructor')
INSERT INTO account VALUES('Robert_Parkin@uml.edu','covertrobert','instructor')
INSERT INTO account VALUES('suri_iyer@uml.edu','suriiyer123','instructor')
INSERT INTO account VALUES('Ainat_Koren@uml.edu','ainatkoren123','instructor')

INSERT INTO take('COMP1010','A','Section101','Fall',100,2023);
INSERT INTO take('CHEM1210','Section100','Spring',101,2025);
INSERT INTO take('ENGN2050','Section100','Fall',102,2024);
INSERT INTO take('PUBH2010','Section100','Spring',103,2025);
INSERT INTO take('MATH2310','Section100','Spring',104,2025);

INSERT INTO undergraduate('Junior',100,58);
INSERT INTO undergraduate('Freshman',101,14);
INSERT INTO undergraduate('Sophomore',102,28);
INSERT INTO undergraduate('Freshman',103,28);
INSERT INTO undergraduate('Sophomore',104,28);

INSERT INTO master(111,15)
INSERT INTO master(112,15)
INSERT INTO master(113,15)
INSERT INTO master(114,15)
INSERT INTO master(115,23)

INSERT INTO undergraduategrader('COMP1020','Section101','Spring',100,2024)
INSERT INTO undergraduategrader('CHEM1210','Section100','Spring',100,2025)
INSERT INTO undergraduategrader('MATH2310','Section100','Spring',100,2025)
INSERT INTO undergraduategrader('PUBH2010','Section100','Spring',102,2025)
INSERT INTO undergraduategrader('ENGN2050','Section100','Fall',100,2024)

INSERT INTO mastergrader('CHEM1210','Section100','Spring',111,2025)
INSERT INTO mastergrader('MATH2310','Section100','Spring',113,2025)
INSERT INTO mastergrader('ENGN2050','Section100','Fall',112,2024)
INSERT INTO mastergrader('PUBH2010','Section100','Spring',114,2025)
INSERT INTO mastergrader('COMP1020','Section101','Spring',115,2024)

INSERT INTO ta('COMP1010','Section100','Spring',106,2024)
INSERT INTO ta('ENGN2050','Section100','Fall',107,2024)
INSERT INTO ta('CHEM1210','Section100','Spring',108,2025)
INSERT INTO ta('MATH2310','Section100','Spring',109,2025)
INSERT INTO ta('PUBH2010','Section100','Spring',110,2025)



INSERT INTO prereq('COMP1010','COMP1020');
INSERT INTO prereq('COMP1020','COMP2010');
INSERT INTO prereq('COMP2010','COMP2040');
INSERT INTO prereq('PHYS1410','ENGN2050');
INSERT INTO prereq('MATH1320','MATH2310');


