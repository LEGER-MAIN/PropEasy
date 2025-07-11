CREATE TABLE favorites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  property_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (property_id) REFERENCES properties(id)
);