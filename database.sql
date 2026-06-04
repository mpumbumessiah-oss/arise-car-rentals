-- database.sql
CREATE DATABASE IF NOT EXISTS arise_rentals;
USE arise_rentals;

-- Cars table
CREATE TABLE cars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    color VARCHAR(30),
    transmission ENUM('automatic', 'manual') DEFAULT 'automatic',
    fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid') DEFAULT 'petrol',
    seats INT DEFAULT 5,
    doors INT DEFAULT 4,
    price_per_day DECIMAL(10,2) NOT NULL,
    price_per_week DECIMAL(10,2),
    price_per_month DECIMAL(10,2),
    security_deposit DECIMAL(10,2) DEFAULT 1000,
    description TEXT,
    features TEXT,
    image_main VARCHAR(255),
    images TEXT,
    license_plate VARCHAR(20) UNIQUE,
    mileage INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_brand (brand),
    INDEX idx_price (price_per_day),
    INDEX idx_featured (is_featured)
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_reference VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    car_id INT NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    pickup_time TIME NOT NULL,
    return_time TIME NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    return_location VARCHAR(255),
    total_amount DECIMAL(12,2) NOT NULL,
    deposit_paid DECIMAL(10,2) DEFAULT 0,
    status ENUM('pending', 'confirmed', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    driver_license VARCHAR(50),
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    INDEX idx_email (customer_email),
    INDEX idx_dates (pickup_date, return_date),
    INDEX idx_status (status)
);

-- Insert sample cars
INSERT INTO cars (name, brand, model, year, transmission, fuel_type, seats, price_per_day, description, image_main, is_featured, is_available) VALUES
('Lamborghini Huracan', 'Lamborghini', 'Huracan EVO', 2023, 'automatic', 'petrol', 2, 1500, 'Experience the thrill of Italian engineering with this stunning Lamborghini Huracan. Features a powerful V10 engine and luxurious interior.', 'https://images.unsplash.com/photo-1580273916550-e323be2ae537', 1, 1),
('Mercedes-Benz S-Class', 'Mercedes', 'S-Class', 2024, 'automatic', 'petrol', 5, 800, 'The epitome of luxury and comfort. Perfect for business trips and special occasions.', 'https://images.unsplash.com/photo-1616422285623-13ff0162193c', 1, 1),
('BMW 7 Series', 'BMW', '740i', 2023, 'automatic', 'petrol', 5, 650, 'Executive luxury sedan with advanced technology and superior comfort.', 'https://images.unsplash.com/photo-1583121274602-3e2820c69888', 1, 1),
('Range Rover Sport', 'Land Rover', 'Range Rover Sport', 2023, 'automatic', 'petrol', 5, 900, 'Luxury SUV perfect for city driving and desert adventures.', 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6', 1, 1),
('Porsche 911', 'Porsche', '911 Carrera', 2023, 'automatic', 'petrol', 4, 1100, 'Iconic sports car with exceptional handling and performance.', 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e', 0, 1),
('Tesla Model S', 'Tesla', 'Model S Plaid', 2024, 'automatic', 'electric', 5, 700, 'Electric luxury sedan with incredible acceleration and range.', 'https://images.unsplash.com/photo-1617788138017-80ad40651399', 0, 1);

-- Insert admin user (password: admin123)
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@ariserenals.ae');