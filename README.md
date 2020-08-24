# Events Calendar

How to install

1. Make sure to have nodeJS preferrably version v14.5.0 on your local machine. You may download it here: https://nodejs.org/en/download/current/

2. Download the project via git or as .zip file.
  
  i. GIT: Open terminal and type:

  `git clone https://github.com/marvinville/events-calendar.git`

  `git pull origin master`

3. Once downloaded, go to project directory. On terminal, you may type something like:

`cd events-calendar`

4. Once you get inside the project directory, download the dependencies by typing:

`npm install`
`composer install`

5. Setup app. key

    i. Copy .env.example and name it to .env
    ii. In your terminal, type: `php artisan key:generate`

6. Setup database connection

    i. Go to MySQL database
    ii. Create database with your desired name. E.g: events_calendar
    iii. Go to .env file and change the following:

    `DB_DATABASE=<your database name>`
    `DB_USERNAME=<your MySQL username>`
    `DB_PASSWORD=<your MySQL password>`

7. Start migrating database tables

    In your terminal, type: `php artisan migrate`

How to run the application

`php artisan serve`
