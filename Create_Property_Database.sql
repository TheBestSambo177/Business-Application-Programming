/* Table creation */

drop table if exists discounts;
drop table if exists amenities;
drop table if exists payments;
drop table if exists owners;
drop table if exists reviews;
drop table if exists bookings;
drop table if exists properties;
drop table if exists users;

create table users (
    userID int PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    phoneNumber VARCHAR(50) UNIQUE,
    email VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    address VARCHAR(50) UNIQUE,
    licenceNumber VARCHAR(50),
    photoIdentification VARCHAR(50)
);

create table properties (
    propertyID int PRIMARY KEY AUTO_INCREMENT,
    address VARCHAR(100),
    price DECIMAL(13,2),
    city VARCHAR(50),
    specifications VARCHAR(200),
    images VARCHAR(50),
    booked BOOLEAN DEFAULT FALSE
);

create table bookings (
    bookingID int PRIMARY KEY AUTO_INCREMENT,
    propertyID int,
    userID int,
    arrivalDate DATE,
    departureDate DATE,
    cost DECIMAL(13,2),
    FOREIGN KEY (propertyID) REFERENCES properties(propertyID),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

create table reviews (
    reviewID int PRIMARY KEY AUTO_INCREMENT,
    userID int,
    propertyID int,
    title VARCHAR(50),
    rating int,
    text VARCHAR(200),
    date DATE,
    FOREIGN KEY (propertyID) REFERENCES properties(propertyID),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

create table owners (
    ownerID int PRIMARY KEY AUTO_INCREMENT,
    userID int,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

create table payments (
    paymentID int PRIMARY KEY AUTO_INCREMENT,
    userID int,
    bookingID int, 
    method VARCHAR(50),
    cost DECIMAL(13,2),
    status VARCHAR(50),
    description VARCHAR(200),
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (bookingID) REFERENCES bookings(bookingID)
);

create table amenities (
    amenitiesID int PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    type VARCHAR(50),
    information VARCHAR(200),
    propertyID int, 
    FOREIGN KEY (propertyID) REFERENCES properties(propertyID)
);

create table discounts (
    discountsID int PRIMARY KEY AUTO_INCREMENT,
    propertyID int, 
    type VARCHAR(50),
    price DECIMAL(13,2), 
    expiry DATE,
    conditions VARCHAR(100),
    FOREIGN KEY (propertyID) REFERENCES properties(propertyID)
);


/* Records for testing */
Insert into users (userID, firstName, lastName, phoneNumber, email, password, address, licenceNumber, photoIdentification)
Values (1, 'Jimmy', 'Novak', '0215722745', 'continental@gmail.com', 'password', '132 Pine Street', 'WO3425627H234', 'images/licences'),
(2, 'Bobby', 'Singer', '0214279134', 'singerauto@gmail.com', 'betterpassword', '2194 Kripke Lane', 'FE4729F81S383', 'images/licences');

Insert into properties (propertyID, address, price, city, specifications, images, booked)
Values (1, '308 Negra Arroyo Lane', '129.99', 'Auckland', 'Nice location', 'example1.png', 0),
    (2, '557 Kripke Lane', '132.99', 'Taupo', 'Nice location', 'example2.png', 0),
    (3, '2 Magnolia Crescent', '145.99', 'Napier', 'Nice location', 'example3.png', 1);

Insert into bookings (bookingID, propertyID, userID, arrivalDate, departureDate, cost)
Values (1, 3, 1, "2023-06-12", "2023-07-09", 1299),
        (2, 2, 2, "2023-07-12", "2023-08-09", 1929);


