 <h3>First step: Install dependencies via Composer with the command :</h3>
  
    composer install
 
 <h3>Second step: Install dependencies with npm :</h3>

    npm install

 <h3>Third step: compile sass and JavaScript files:</h3>
 
    npm run build
    
 <h3>Next step: create a file called .env.local into the project directory, copy the following line and add your own database configuration :</h3>
 
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
    # you should change the db_user, db_password, db_name

 <h3>Final step: Execute the following command :</h3>
 
    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    
 <h3>Download (url: https://wkhtmltopdf.org/downloads.html ) and install wkhtmltopdf if you want to generate a pdf with knp_snappy, then set the installation path in your .env.local :</h3>
 
    WKHTMLTOPDF_PATH=/usr/local/bin/wkhtmltopdf
    # it should be different on windows

<h4>NB: Don't forget to remove all the file in the migrations directory before the execution of the final step !</h4>

<h3>Then run your local server with : </h3>
    
    //if you use symfony cli
    symfony serve -d
    
    // if you want to run it with php command
    php -S localhost:8000 -t public/            
