# Test V-Jet Blog

## Requirements
* Windows/Linux/macOS
* PHP 7.x
* Apache 2.x
* Mysql 5.6+
* Any web browser


## Installation
1. Clone the project to your local machine to desired folder.
If you work under Linux you should clone project to **/var/www/<desired_folder_name>**.
<br>
e.g.
```
$ git clone https://github.com/IVoyt/test_v-jet_blog.git /var/www/test_blog
```

1. Create Virtualhost in your Apache Webserver, e.g. "test-blog.local".
Specify "<path_to_project>/public/index.php" as entry point (DocumentRoot).

1. Create database in your Mysql Server.

1. Import database's structure from **test_vjet_blog__blank.sql** (only structure)
or **test_vjet_blog__2019-11-09.sql** (structure and data)
to your Mysql Server using PHPMyAdmin or any another Mysql Client.

1. Edit **<path_to_project>/config/db.php** according to your Mysql settings.

1. Open your Web browser and type in address bar your sites url. Press **Enter**.