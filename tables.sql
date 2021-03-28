create table if not exists Guests(
    g_id int not null,
    first_name varchar(255) not null,
    last_name varchar(255) not null,
    username varchar(45) not null unique,
    password varchar(255) not null,
    primary key(g_id)
);

create table if not exists Employee(
    emp_id int not null,
    first_name varchar(255) not null,
    last_name varchar(255) not null,
    username varchar(45) not null unique,
    password varchar(255) not null,
    primary key(emp_id)
);

create table if not exists Emp_contact(
    emp_id int not null,
    contact_no varchar(12) not null,
    primary key(emp_id, contact_no),
    foreign key(emp_id) references Employee(emp_id)
);

create table if not exists Emp_email(
    emp_id int not null,
    email_id varchar(45) not null,
    primary key(emp_id, email_id),
    foreign key(emp_id) references Employee(emp_id)
);

create table if not exists Department(
    dept_id int not null,
    dept_name varchar(255) not null,
    description varchar(255) default null,
    primary key(dept_id)
);

create table if not exists Emp_dept(
    emp_id int not null,
    dept_id int not null,
    primary key(emp_id, dept_id),
    foreign key(emp_id) references Employee(emp_id),
    foreign key(dept_id) references Department(dept_id)
);

create table if not exists Address(
    address_id int not null,
    city varchar(45) not null,
    state varchar(45) not null,
    country varchar(45) not null,
    zipcode varchar(6) not null,
    primary key(address_id)
);

create table if not exists Guest_address(
    g_id int not null,
    address_id int not null,
    primary key(g_id, address_id),
    foreign key(g_id) references Guests(g_id),
    foreign key(address_id) references Address(address_id)
);

create table if not exists Emp_address(
    emp_id int not null,
    address_id int not null,
    primary key(emp_id, address_id),
    foreign key(emp_id) references Employee(emp_id),
    foreign key(address_id) references Address(address_id)
);

create table if not exists Services(
    serv_id int not null,
    emp_id int not null,
    description varchar(255) not null,
    cost decimal(10, 2) not null,
    primary key(serv_id, emp_id),
    foreign key(emp_id) references Employee(emp_id)
);

create table if not exists Room(
    room_id int not null,
    room_no int not null,
    floor_no int not null,
    primary key(room_id)
);

create table if not exists Room_type(
    room_type_id int not null,
    room_type_name varchar(45) not null,
    description varchar(255) default null,
    cost decimal(10,2) not null,
    primary key(room_type_id)
);

create table if not exists room_type_rel(
    room_id int not null,
    room_type_id int not null,
    primary key(room_id, room_type_id),
    foreign key(room_id) references Room(room_id),
    foreign key(room_type_id) references Room_type(room_type_id)
);

create table if not exists Room_booked(
    g_id int not null,
    room_id int not null,
    b_id int not null,
    ratings int check(ratings >=1 and ratings <=5),
    primary key(g_id, room_id, b_id),
    foreign key(g_id) references Guests(g_id),
    foreign key(room_id) references Room(room_id),
    foreign key(b_id) references Bookings(b_id)
);

create table if not exists Guests_contact(
    g_id int not null,
    contact_no varchar(12) not null,
    primary key(g_id, contact_no),
    foreign key(g_id) references Guests(g_id)
);

create table if not exists Guests_email(
    g_id int not null,
    email_id varchar(45) not null,
    primary key(g_id, email_id),
    foreign key(g_id) references Guests(g_id)
);

create table if not exists Service_used(
    g_id int not null,
    serv_id int not null,
    ratings int check(ratings >=1 and ratings <=5),
    primary key(g_id, serv_id),
    foreign key(g_id) references Guests(g_id),
    foreign key(serv_id) references Services(serv_id)
);

create table if not exists Bookings(
    b_id int not null,
    b_datetime datetime DEFAULT CURRENT_TIMESTAMP,
    check_in date not null,
    check_out date not null,
    payment_type varchar(45) not null,
    total_amount decimal(10, 2) not null,
    total_rooms int not null,
    primary key(b_id)
);