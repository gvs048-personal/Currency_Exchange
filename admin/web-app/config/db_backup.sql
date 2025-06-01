CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE currencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    currency_name VARCHAR(255) NOT NULL,
    sell_price DECIMAL(10, 2) NOT NULL,
    buy_price DECIMAL(10, 2) NOT NULL,
    currency_logo VARCHAR(255) NOT NULL
);

-- Table for storing admin user credentials
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin user with a hashed password
INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$eImiTXuWVxfM37uY4JANjQ=='); -- Replace with a securely hashed password

