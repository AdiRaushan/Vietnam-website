CREATE DATABASE travel_dashboard;
USE	travel_dashboard;

-- Main packages table
CREATE TABLE packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  city VARCHAR(100),
  days INT,
  style VARCHAR(100),
  price INT,
  rating FLOAT
);

-- Images
CREATE TABLE images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  url TEXT,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Highlights
CREATE TABLE highlights (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  text VARCHAR(255),
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Inclusions
CREATE TABLE inclusions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  label VARCHAR(100),
  icon VARCHAR(100),
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Exclusions
CREATE TABLE exclusions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  text VARCHAR(255),
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Where to Visit
CREATE TABLE where_to_visit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  place VARCHAR(255),
  image TEXT,
  description TEXT,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Activities
CREATE TABLE activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  name VARCHAR(100),
  image TEXT,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Itinerary
CREATE TABLE itinerary (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT,
  day VARCHAR(100),
  title VARCHAR(255),
  description TEXT,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);
/* travel stories */
CREATE TABLE IF NOT EXISTS travel_stories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  author VARCHAR(100),
  content TEXT,
  image TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SHOW TABLES;