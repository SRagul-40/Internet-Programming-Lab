DROP DATABASE IF EXISTS hotel_db;
CREATE DATABASE hotel_db CHARACTER SET utf8mb4;
USE hotel_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest','admin') DEFAULT 'guest',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_no VARCHAR(10) NOT NULL UNIQUE,
  type VARCHAR(40) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  capacity INT NOT NULL,
  ac TINYINT(1) NOT NULL DEFAULT 1,
  tv TINYINT(1) NOT NULL DEFAULT 1,
  balcony TINYINT(1) NOT NULL DEFAULT 0,
  description VARCHAR(255)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_ref VARCHAR(20) NOT NULL,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  guests INT NOT NULL,
  food TINYINT(1) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('Confirmed','Cancelled') DEFAULT 'Confirmed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  name VARCHAR(80) NOT NULL,
  rating TINYINT NOT NULL,
  comment VARCHAR(500) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO rooms (room_no,type,price,capacity,ac,tv,balcony,description) VALUES
('101','Standard',1800,2,1,1,0,'Queen bed, work desk and garden-side window.'),
('102','Standard',1800,2,1,1,0,'Twin beds, work desk and quiet corner location.'),
('103','Standard',1200,2,0,1,0,'Non-AC room with fan, double bed and TV.'),
('104','Standard',1000,2,0,0,0,'Budget non-AC room with fan and double bed.'),
('201','Deluxe',3200,3,1,1,1,'King bed, sofa corner and private balcony.'),
('202','Deluxe',3200,3,1,1,1,'King bed, bathtub and balcony with open view.'),
('203','Deluxe',2400,3,0,1,1,'Non-AC deluxe room with balcony and TV.'),
('301','Suite',5500,4,1,1,1,'Separate living room, two bathrooms, balcony view.'),
('302','Family Suite',6800,5,1,1,1,'Two bedrooms, kitchenette and large balcony.');

INSERT INTO users (name,email,phone,password,role) VALUES
('Administrator','admin@hotel.com','9999999999','$2y$12$OgxqZuj44No3ui2g2.Lqau64Ue2Fl/Ftqi4aoM8R2X8Mx3fqyCTAS','admin');

INSERT INTO reviews (user_id,name,rating,comment) VALUES
(NULL,'Karthik R',5,'Very clean rooms and friendly staff. Check-in was quick and the balcony view was lovely.'),
(NULL,'Priya S',5,'Booked 2 rooms for our family of 4. Staff guided us well and the food was fresh and tasty.'),
(NULL,'Mohammed A',4,'Good value for money. AC worked perfectly and the bathroom was spotless.'),
(NULL,'Divya L',5,'Well maintained hotel, calm atmosphere and prompt room service.'),
(NULL,'Suresh K',4,'Comfortable beds and good location. Would stay again.');
