---
Description: A simple PHP application that accepts a visitor’s email address and emails them random XKCD comics every five minutes.

### Folder Structure

    src/
        crontab
        view/
            index.html
            css/
                main.css
        server/
            sample_config.php
            server.php
            email.php
            activation.php
            deactivation.php



**Cronjob executed using crontab command in src folder in Ubuntu on Live Demo**


Description:
- User visits website and enters name and email and submits then server.php is executed and name, email is stored in sublist table of database
- There four Columns in Database {sno: INT PRIMARY KEY and Auto Increment, name: VARCHAR, email: VARCHAR UNIQUE, sub: (0 or 1) Default-0} 
- They are stored in mysql database using mysqli() commands
- And an email is sent for email verifcation
- Link in email when run on browser executes activation.php which sets sub value of that email to 1.
- And then according to crontab timer email.php is executed every 5 minutes which sends random xkcd comic extracted from the random url to all users whose sub value in database is 1 and in email the image is embedded as well as attached also deactivation is included.
- When User runs the deactivation link in the browser the deactivation.php script is executed and the sub value of that email is set to 0.
