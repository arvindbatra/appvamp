



##create the user table
create table if not exists UserInfo  (
	id int not null auto_increment primary key,
	auth_id varchar(30)  not null,
	auth_type varchar(100) not null,
	name varchar (100) ,
	paypal_email_address varchar(100), 
	created_at DATETIME 
	)engine=InnoDB;


create table if not exists UserAppInfo (
	id int not null auto_increment primary key,
	user_id int not null,
	apple_id varchar(50),
	app_external_id varchar(50) not null,
	app_name varchar (200) not null,
	purchased_date DATETIME not null,
	present_now int not null,
	verification_status int,
	verified_date DATETIME, 
	value_received float,
	updated_at DATETIME,
	created_at DATETIME,
	FOREIGN KEY (user_id) REFERENCES UserInfo(id)
	)engine = InnoDB;


