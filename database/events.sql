CREATE DATABASE IF NOT EXISTS eventhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventhub;

CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS events (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 category VARCHAR(80) NOT NULL,
 description TEXT NOT NULL,
 event_date DATE NOT NULL,
 event_time TIME NOT NULL,
 venue VARCHAR(150) NOT NULL,
 capacity INT UNSIGNED NOT NULL DEFAULT 100,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_events_schedule(event_date,event_time)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS registrations (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 event_id INT UNSIGNED NOT NULL,
 status ENUM('confirmed','cancelled') NOT NULL DEFAULT 'confirmed',
 registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_user_event(user_id,event_id),
 INDEX idx_registrations_event_status(event_id,status),
 CONSTRAINT fk_registration_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_registration_event FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO events(title,category,description,event_date,event_time,venue,capacity)
SELECT 'TechSprint Hackathon','Technical','A campus coding challenge where student teams solve practical technology problems.',DATE_ADD(CURDATE(),INTERVAL 7 DAY),'10:00:00','Innovation Lab',80
WHERE NOT EXISTS(SELECT 1 FROM events WHERE title='TechSprint Hackathon');
INSERT INTO events(title,category,description,event_date,event_time,venue,capacity)
SELECT 'Campus Beats','Cultural','A college music and performance evening featuring student artists and teams.',DATE_ADD(CURDATE(),INTERVAL 12 DAY),'17:30:00','Main Auditorium',200
WHERE NOT EXISTS(SELECT 1 FROM events WHERE title='Campus Beats');
INSERT INTO events(title,category,description,event_date,event_time,venue,capacity)
SELECT 'Inter-College Cricket','Sports','A competitive cricket fixture bringing participating college teams together.',DATE_ADD(CURDATE(),INTERVAL 18 DAY),'09:00:00','University Ground',120
WHERE NOT EXISTS(SELECT 1 FROM events WHERE title='Inter-College Cricket');
INSERT INTO events(title,category,description,event_date,event_time,venue,capacity)
SELECT 'Design Arena','Creative','A creative design challenge for students interested in visual communication and digital design.',DATE_ADD(CURDATE(),INTERVAL 22 DAY),'11:00:00','Design Studio',60
WHERE NOT EXISTS(SELECT 1 FROM events WHERE title='Design Arena');
