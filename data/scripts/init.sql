CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    property_type ENUM('apartment', 'house', 'commercial_property') NOT NULL,
    bedrooms INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    furnished BOOLEAN DEFAULT FALSE,
    serviced BOOLEAN DEFAULT FALSE,
    shared BOOLEAN DEFAULT FALSE,
    keywords VARCHAR(255),
    property_ref VARCHAR(255),
    agent_id INT,
    latitude DECIMAL(9, 6),
    longitude DECIMAL(9, 6),
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES users(id)
);

CREATE TABLE property_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

/**
 * Add age to users table
 */
ALTER TABLE users ADD COLUMN age INT;

/**
 * Add area_code, address & sale_type to properties table
 */
ALTER TABLE properties ADD COLUMN area_code VARCHAR(255);
ALTER TABLE properties ADD COLUMN address VARCHAR(255);
ALTER TABLE properties ADD COLUMN sales_type ENUM('rent', 'sale', 'short_lease') DEFAULT 'sale';

/**
 * Create purchases table
 */

CREATE TABLE purchases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    buyer_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id),
    FOREIGN KEY (buyer_id) REFERENCES users(id)
);