<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h2>
    Basic Description of the Helpdesk Microservice
</h2>
<p>
The Helpdesk microservice is an essential component of the system that manages support interfaces, both for users and internal teams. It offers features to create, view, update and delete support tickets, ensuring that issues and features are handled in an organized and efficient manner.
</p>

<h2>Key Features</h2>

<h3>Create a Ticket</h3>
<p>
End point: /api/tickets (POST method)
Description: This endpoint allows users to create a new support ticket. To do this, users must provide information such as the requester ID, assigned user ID, ticket category, a detailed description of the issue, and priority (low, medium, or high)
</p>

<h3>View a Ticket</h3>
<p>
End point: /api/tickets/{id} (GET method)
Description: This endpoint allows users to view details of a specific ticket by providing the ticket ID. Details include the requester and assigned user ID, ticket category, description, status, and priority.
</p>

<h3>Update a Ticket</h3>
<p>
Endpoint: /api/tickets (PUT method)
Description: This endpoint allows users to update information for an existing ticket. To do this, users must provide their ticket ID to be updated and reviewed information such as requester ID, assigned user ID, category, description, status, and priority.
</p>

<h3>Delete a Ticket</h3>
<p>
End point: /api/tickets/{id} (DELETE method)
Description: This endpoint allows users to delete a specific support ticket based on the ID provided. After deletion, the ticket will no longer be available in the system. 
</p>

<h3>List All Tickets</h3>
<p>
Endpoint: /api/tickets (GET method)
Description: This endpoint allows you to list all support tickets available in the system. It provides an overview of all created tickets.
</p>

<h3>Validation Resources</h3>
<p>
Helpdesk has validation features to ensure that the data provided when creating or updating a ticket is correct and meets established criteria, such as required fields, valid formats, and acceptable values. Unit tests were implemented.
</p>

<p>
Creating a ticket with the "user_requester_id" and "user_assigned_id" fields often indicates an undesirable or problematic scenario. Therefore, a business rule was broken to deal with and prevent this situation.
</p>

<p>
    Documentation of endpoints in a visual format was also implemented. To view these resources described above, access the <strong>/api/documentation</strong> route.
</p>

<h2>Setup</h3>

<h3>Step 1:</h3>
<p>
Laravel 9
Composer
PostgreSQL
</p>

<h3>
Step 2: Clone the project  
</h3>

<h3>
Step 3: Install dependencies
</h3>
<p>
composer install
</p>

<h3>
Step 4: Configure the database
</h3>
<p>
Create a database
Create a.env file

Add to file:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
</p>

<h3>
Step 5: Run the migrations
</h3>
```shell
php artisan migrate
```

<h3>
Step 6: Start the server
</h3>
```shell
php artisan serve
```

<h3>
Unit tests:
</h3>
`php artisan test`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
