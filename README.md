
## About Novalager
step by step configuration laravel  and vue.js  novaleger

# Migration 

We need doctrine dbal

composer require  doctrine/dbal

# Execute migration

php artisan migrate

# Seeders

php artisan db:seed


copy content of seeder_files in root to storage/app/public 

content of storage are not copy to git repository .gitignore

# Add a link between storage and public

php artisan storage:link

# Add  vuejs sass moment-timezone vue-router vitejs/plugin-vue

# Add vite.config  for vue

npm i

# Run a dev  build

npm run dev

# Start laravel web server 

php artisan serve 
