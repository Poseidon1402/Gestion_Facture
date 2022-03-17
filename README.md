 <h2>First step: Install dependencies via Composer with the command :</h2>
  
    composer install
 
 <h2>Second step: Install dependencies with npm :</h2>

    npm install

 <h2>Third step: compile sass and JavaScript files:</h2>
 
    npm run build
    
 <h2>Next step: create a file called .env.local into the project directory and add your own database configuration :</h2>
 
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
    #you should change the db_user, db_password, db_name

 <h2>Final step: Execute the following command :</h2>
 
    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    
 <h4>Don't forget to remove all the file in the migrations directory before the last step</h4>
    
