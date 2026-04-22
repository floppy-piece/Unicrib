Install Dependencies

Run 
 _______________________
|    composer install   |
|_______________________|
 to install PHP dependencies from composer.json.

If the project uses frontend assets, also run 
 _______________________________________________
|   npm install && npm run build or npm run dev |
|_______________________________________________|
Environment Setup

Copy the example environment file: cp .env.example .env (Linux/Mac) or copy .env.example .env (Windows).
Edit .env to set your database credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
Generate the application key with php artisan key:generate.
Database Configuration

Run php artisan migrate to execute database migrations.
Optionally seed the database with 
 ___________________________________________________________________
|php artisan db:seed or php artisan migrate --seed for initial data.|
|___________________________________________________________________|

Permissions and Links
Set proper permissions: chmod -R 775 storage bootstrap/cache.

Create symbolic link for storage: php artisan storage:link.

Run Server
Start the development server:
 ___________________
| php artisan serve.|
|___________________|
Access the app at http://localhost:8000.
