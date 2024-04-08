OUR SQL DATABASE CODE:

USE dbclothes;

CREATE TABLE user_id (
    userid INT(8) NOT NULL AUTO_INCREMENT,
    balance DOUBLE,
    name VARCHAR(45) NOT NULL,
    username VARCHAR(45) NOT NULL,
    user_password VARCHAR(45) NOT NULL,
    item_id INT(8),
    PRIMARY KEY(userid)
);

CREATE TABLE individual_clothes (
    id INT(8) NOT NULL AUTO_INCREMENT,
    name VARCHAR(45) NOT NULL,
    brand VARCHAR(45) NOT NULL,
    category VARCHAR(45) NOT NULL,
    color VARCHAR(45) NOT NULL,
    price DOUBLE NOT NULL,
    gender VARCHAR(45) NOT NULL,
    size VARCHAR(3) NOT NULL,
    available_quantity INT(8),
    image_URL VARCHAR(255),
    description VARCHAR(255),
    PRIMARY KEY(id)
);

CREATE TABLE carts (
    cart_id INT(8) NOT NULL AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    item_id INT(8) NOT NULL,
    quantity INT(8) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(cart_id),
    FOREIGN KEY (user_id) REFERENCES user_id(userid),
    FOREIGN KEY (item_id) REFERENCES individual_clothes(id)
);

CREATE TABLE orders (
    order_id INT(8) NOT NULL AUTO_INCREMENT,
    user_id INT(8) NOT NULL,
    total_price DOUBLE NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(order_id),
    FOREIGN KEY (user_id) REFERENCES user_id(userid)
);

CREATE TABLE order_items (
    order_item_id INT(8) NOT NULL AUTO_INCREMENT,
    order_id INT(8) NOT NULL,
    item_id INT(8) NOT NULL,
    quantity INT(8) NOT NULL,
    price_per_unit DOUBLE NOT NULL,
    subtotal DOUBLE NOT NULL,
    PRIMARY KEY(order_item_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (item_id) REFERENCES individual_clothes(id)
);


************************************************
NOTE!

orders: This table stores information about orders. Each row represents a single order made by a user and includes details such as the total price and order date.

order_items: This table stores information about the items included in each order. It contains details about the items ordered, including quantity, price per unit, and subtotal for each item in an order.

************************************************
