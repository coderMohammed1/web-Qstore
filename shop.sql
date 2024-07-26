create database Qshop;
use Qshop;

ALTER DATABASE Qshop
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- init the tables

-- ! Roles will be ethier s for seller or c for customer
create table users(
ID INT primary key auto_increment,
First_Name varchar(40) not null,
Last_name varchar(40) not null,
roles char(1) not null, -- for knowing if user a seler or buyer
userName varchar(30) unique not null,
age date not null,
shipping_info text not null
) auto_increment = 1;

alter table users
add constraint rolescons check(roles = 's' or roles = 'c'); 

alter table users
add column isactive boolean;

alter table users 
modify column isactive int default 0; 

alter table users
add column email varchar(70) unique;

CREATE TABLE cart (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    total DECIMAL(10, 2) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(ID)
)auto_increment = 1;

alter table cart
add constraint totalcons check(total>=0.00);

create table orders(
ID int primary key auto_increment,
price decimal(10,2) not null,
odate date default(CURRENT_TIMESTAMP),
user_id int not null,
foreign key (user_id) references users(ID)
)auto_increment = 1;

alter table orders
add constraint opricecons check(price>=0.00);

create table product(
ID int primary key auto_increment,
p_name varchar(70) not null,
price decimal(10,2) not null,
Manfacturer varchar(50),
seller int not null,
foreign key(seller) references users(ID)
)auto_increment = 1;

alter table product
add constraint ppricecons check(price>=0.00);

-- many to many
create table cart_products(
ID int primary key auto_increment,
cart int,
product int,
foreign key(cart) references cart(ID),
foreign key(product) references product(ID)
)auto_increment = 1;


-- many to many
create table Order_Product(
order_id int not null,
product int not null,
foreign key (product) references product(ID),
foreign key(order_id) references orders(ID)
)auto_increment = 1;

alter table users 
add column password text not null;
-----------------------------------------------------------------------------------------
ALTER TABLE users CHANGE age birthdate DATE;
alter table users drop column shipping_info;

-------------------------------------------------------------------------------------------
create table customer(
cust_id int unique,
-- shipping info
city varchar(55),
street varchar(55),
country varchar (50)
);

alter table customer
add constraint custfk foreign key(cust_id) references users(ID);

create table seller(
seller_id int unique,
total_sales decimal(15,3)
);

alter table seller
add constraint sellfk foreign key(seller_id) references users(ID);

alter table seller
modify column total_sales decimal(15,3) default 0.000; 
--------------------------------------------------------------
alter table users drop column userName;
alter table users add column token varchar(500) unique;
------------------------------------------------------

select * from users;