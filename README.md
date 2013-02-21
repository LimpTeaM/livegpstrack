Hello!
===============
This is a personal gps tracking cms for android devices with OSMAnd application.
And for GPS trackers (TODO).

Live Demo
---------------
[Demo][1] (Russian)

You must to register and add your device. 

How to setup.
---------------
1.You  need a webserver with php5 and mysql.

2 Download files: 

  git clone https://github.com/LimpTeaM/livegpstrack.git

3.setup mysql (create user and database)

4.edit db.example.php for database connection.

  mv db.example.php db.php

5.import track.sql to mysql database:

  mysql -u username -p -h localhost DATA-BASE-NAME < track.sql

Now you get site working. Register some user.
For tracking working you must download OSMand and activate plugins Monitoring, background monitoring. (how to do it see site after register and login)
Edit address of live monitoring server to your server.



Many todo. security issues too :D


p.s. i'm not programmer :).

[1]: http://map.limpteam.ru "Demo"
