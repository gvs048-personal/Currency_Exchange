# Web Application Project

## Overview
This web application allows users to manage items in a database. Users can add new items, edit existing ones, and delete items as needed. The application also supports image uploads, allowing users to associate images with each item.

## Project Structure
```
web-app
├── assets
│   ├── css
│   │   └── styles.css
│   ├── images
│   └── js
│       └── scripts.js
├── src
│   ├── config
│   │   └── database.php
│   ├── controllers
│   │   ├── add_item.php
│   │   ├── delete_item.php
│   │   ├── edit_item.php
│   │   └── fetch_items.php
│   ├── uploads
│   └── views
│       ├── index.php
│       ├── add_item_form.php
│       └── edit_item_form.php
├── .htaccess
├── README.md
└── db.sql
```

## Features
- **Add Items**: Users can add new items with details and images.
- **Edit Items**: Users can edit existing items and update their details and images.
- **Delete Items**: Users can delete items from the database.
- **Image Uploads**: Users can upload images associated with each item, which are stored in the server.

## Setup Instructions
1. **Clone the Repository**: Clone this repository to your local machine.
2. **Database Configuration**: Update the `src/config/database.php` file with your database credentials.
3. **Create Database**: Run the SQL commands in `db.sql` to create the necessary database schema.
4. **Start the Server**: Use a local server environment (like XAMPP or MAMP) to run the application.
5. **Access the Application**: Open your web browser and navigate to `http://localhost/web-app/src/views/index.php`.

## Usage Guidelines
- Navigate to the main page to view the list of items.
- Use the "Add Item" button to open the form for adding new items.
- Each item in the list has options to edit or delete.
- Ensure that images uploaded are in PNG format for best compatibility.

## Technologies Used
- **HTML**: For structuring the web pages.
- **CSS**: For styling the application.
- **JavaScript**: For client-side functionality.
- **PHP**: For server-side logic and database interactions.
- **MySQL**: For data storage.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any changes or improvements.