# Vyvasync


Dear All Please see how to use GitHub
https://guides.github.com/activities/hello-world/

What is required on the deadlines

VyvaSync v0.0.1 Alpha Features to do list:

1.  Mobile web only layout    - Ali Darab - 11/8/15
2.  Fixed  number of roles    
3.  Day of shoot calendar
4.  OK take by director
5.  Approval feature for producer

VyvaSync v0.0.2 Beta

6.	Full responsive Layout
7.	Multiple roles for each person and ad-roles features
8.  Client login with notes approval
9.  Actors Call Sheet
10. Printable PPM Booklet

VyvaSync v0.0.3

11. Client login with notes approval with summary
12. Make the code on JS, Angular.js and Node.js
13. DB change to MondoDB / NoSQL

VyvaSync v0.0.4 

14. Hardware Interaction - OK Button and also video recording feature (rasberry PI 2)




Developer notes:

- The application makes use of the MVC design pattern and henceforth uses the application is
divided into two folders: "application" and "public".

- "Public" folder hosts files such as CSS and the necessary JS functions file.

- "Application" folder consists of the "Model", "Controller", and "Views".

- "Views" will contain all HTML files followed by the .PHP extension.

- "Controller" files are used to pass data from the user onto the relevant Model file.

- "Model" consists of all functions that relay information onto the database.

- At the moment, the "Model" consists of the following files:
	1) connect.php
	2) schema.php
	3) users.php
	4) projects.php
	5) scenes.php

- The following is a description of the functions that constitute each of the files on the "Models" folder.

- Connect: 
	1) Server configuration (PHP variables define)
	2) connectdb() - Used to connect to the database
	3) execQuery() - Used to execute queries
	4) insertQuery() - Used to execute queries of form "INSERT" and returns insert id
	5) escapeString() - Used to escape characters that can lead to SQL injection

- Schema:
	1) db_schema() - This function is a packaged schema that can be used to re-build the database
			if something goes wrong.

- Users:
	1) userExits() - Takes email as the input and returns whether or not an account exists under 
			that email
	2) login() - Logs the user into the application
	3) registerUser() - Registers the user onto the application
	4) encryptPassword() - Encrypts the password to be stored into the database
	5) sessionActive() - Checks if the user using the application is on a valid session
	6) logout() - Logs the user out of the application

- Projects:
	1) addProject() - Takes multiple parameters and creates a new project into the database for the user
	2) deleteProject() - Deletes the project that is passed in as the argument
	3) updateProject() - Updates project details as per user input
	4) projectCount() - Returns total number of projects of which the user is a member (all roles)
	5) acceptProject() - Upon addition as a member, this function is used to "Accept" the invite
	6) addMember() - Used to add a member to a project
	7) removeMember() - Used to remove a member from the project

- Scenes:
	1) addScene() - Used to add a scene as part of the storyboard for any given project
	2) deleteScene() - Used to delete any given scene from the project
	3) toggleSceneStatus() - Used to "OK" any given scene by the director
	4) addComment() - Used to add comments by members of the project


- Please be reminded that these functions are likely to undergo moderations as the application is developed.
- It is likely that more functions will be added as we progress.
- The idea is to develop the application such that it facilitates further development in time.

- Currently working on a script to parse images into the database and view them in real-time. More to be added tomorrow.
