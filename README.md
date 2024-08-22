
## how to run  
run these after copying the project
```bash
composer update  
npm install  
cp .env.example .env  
php artisan key:generate  
php artisan migrate --seed
```
then
```bash
php artisan serve & npm run dev & php artisan queue:work
```

database schema: 
```bash
https://dbdiagram.io/d/661fa65f03593b6b613897fa
```
## before starting the project fill this variables with ur own credentials, or else it will not work frr    
its located in .env or in .env.example

```bash
https://pusher.com/docs/channels/getting_started/javascript/
```
PUSHER_APP_ID=   
PUSHER_APP_KEY=   
PUSHER_APP_SECRET=  

```bash
https://www.paymongo.com/
```
PAYMONGO_PUBLIC_KEY=  
PAYMONGO_SECRET_KEY=  

```bash
basta e search google maps api sa google
```
GOOGLE_MAPS_API_KEY=  

```bash
https://mailtrap.io/
```
MAIL_MAILER=  
MAIL_HOST=  
MAIL_PORT=  
MAIL_USERNAME=  
MAIL_PASSWORD=  
MAIL_ENCRYPTION=  

enable gd extension in php.ini  
xampp/php/php.ini  
search for ;extension=gd    
and remove the semicolon ; and then save  

