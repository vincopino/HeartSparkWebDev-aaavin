-- HeartSpark MySQL schema 

-- Users (for auth, orders, messaging)
CREATE TABLE users (
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255),
  user_address VARCHAR(255),
  email VARCHAR(255) NOT NULL UNIQUE,
  contact VARCHAR(255),
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products 
CREATE TABLE products (
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item_name VARCHAR(255) NOT NULL,
  item_desc TEXT,
  price_cents INT NOT NULL DEFAULT 0, -- store price in cents
  image_path VARCHAR(512)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Order items
CREATE TABLE cart_items (
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  product_id BIGINT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  unit_price_cents INT NOT NULL, -- price of a single item
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact messages (from contact.php)
CREATE TABLE contacts (
  id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  processed BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;