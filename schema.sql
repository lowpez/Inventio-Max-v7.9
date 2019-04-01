/*
* Modelo de base de datos del sistema Inventio Max version 8
* @author evilnapsis
* @url http://evilnapsis.com/product/inventio-max
*/
create database inventiomax;
use inventiomax;



create table user(
	id int not null auto_increment primary key,
	name varchar(50) not null,
	lastname varchar(50) not null,
	username varchar(50),
	email varchar(255) not null,
	password varchar(60) not null,
	image varchar(255),
	comision float,
	status int not null default 1,/*0. inactive, 1. active*/
	kind int not null default 1,/*1. admin 2. almacenista 3. vendedor 4. gerente*/
	stock_id int,/* almacen, solo para almacenista y vendedor */
	created_at datetime not null
);

insert into user(name,lastname,email,password,status,kind,created_at) value ("Administrador", "","admin","90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad",1,1,NOW());


create table category(
	id int not null auto_increment primary key,
	image varchar(255),
	name varchar(50) not null,
	description text,
	created_at datetime not null
);

create table brand(
	id int not null auto_increment primary key,
	image varchar(255),
	name varchar(50) not null,
	description text,
	created_at datetime not null
);

create table p(
	id int not null auto_increment primary key,
	name varchar(50) not null
);
create table d(
	id int not null auto_increment primary key,
	name varchar(50) not null
);

create table f(
	id int not null auto_increment primary key,
	name varchar(50) not null
);
insert into p(name) values ("Pagado"),("Pendiente"),("Cancelado"),("Credito");
insert into d(name) values ("Entregado"),("Pendiente"),("Cancelado");
insert into f(name) values ("Efectivo"),("Deposito"),("Cheque");


/*
table: product
kind: 1. product, 2. service
*/
create table product(
	id int not null auto_increment primary key,
	image varchar(255),
	code varchar(50) not null,
	barcode varchar(50) not null,
	name varchar(50) not null,
	description text not null,
	inventary_min int not null default 10,
	price_in float not null,
	price_out float,
	unit varchar(255) not null,
	presentation varchar(255) not null,
	user_id int not null,
	category_id int,
	brand_id int,
	width float,
	height float,
	weight float,
	expire_at date,
	created_at datetime not null,
	kind int not null default 1,
	is_active boolean not null default 1,
	foreign key (category_id) references category(id),
	foreign key (user_id) references user(id)
);

create table price(
	id int not null auto_increment primary key,
	price_out double default 0,
	product_id int not null,
	stock_id int not null,
	foreign key (product_id) references product(id),
	foreign key (stock_id) references stock(id)
);

/*
person kind
1.- Client
2.- Provider
3.- Contact
*/
create table person(
	id int not null auto_increment primary key,
	image varchar(255) ,
	no varchar(255) ,
	name varchar(255) not null,
	lastname varchar(50) not null,
	company varchar(50),
	address1 varchar(50),
	address2 varchar(50),
	phone1 varchar(50),
	phone2 varchar(50),
	email1 varchar(50),
	email2 varchar(50),
	is_active_access boolean not null default 0,
	has_credit boolean not null default 0,
	credit_limit double, /* 0 para credito ilimitado */
	password varchar(60),
	kind int,
	created_at datetime not null
);

create table stock(
	id int not null auto_increment primary key,
	name varchar(50) not null,
	address varchar(255),
	phone varchar(255),
	email varchar(255),
	is_principal boolean not null
);

insert into stock(name,is_principal) values ("Principal",1),("Almacen 1",0);

create table operation_type(
	id int not null auto_increment primary key,
	name varchar(50) not null
);

insert into operation_type (name) value ("entrada");
insert into operation_type (name) value ("salida");
insert into operation_type (name) value ("entrada-pendiente"); 
insert into operation_type (name) value ("salida-pendiente"); 
insert into operation_type (name) value ("devolucion");
insert into operation_type (name) value ("traspaso");

create table box(
	id int not null auto_increment primary key,
	stock_id int not null,
	created_at datetime not null
);

create table xx (	id int not null auto_increment primary key ); /* ids autoincrementales para ventas */
create table yy (	id int not null auto_increment primary key ); /* ids autoincrementales para compras*/

/* Tabla sell: Ventas, compras, cotizaciones */
create table sell(
	id int not null auto_increment primary key,
	invoice_code varchar(255),
	invoice_file varchar(255),
	comment text,
	ref_id int,
	sell_from_id int,
	person_id int ,
	user_id int ,
	operation_type_id int default 2,
	box_id int,
	p_id int,
	d_id int,
	f_id int,
	total double,
	cash double,
	iva double, /* impuesto actual del producto */
	discount double,
	is_draft boolean not null default 0,
	stock_to_id int,
	stock_from_id int,
	status int default 1,
	foreign key (p_id) references p(id),
	foreign key (d_id) references d(id),
	foreign key (box_id) references box(id),
	foreign key (operation_type_id) references operation_type(id),
	foreign key (user_id) references user(id),
	foreign key (person_id) references person(id),
	created_at datetime not null
);

create table operation(
	id int not null auto_increment primary key,
	product_id int not null,
	stock_id int not null,
	stock_destination_id int,
	operation_from_id int,
	q float not null,
	price_in double, /* precio actual del producto */
	price_out double, /* precio actual del producto */
	operation_type_id int not null,
	sell_id int,
	status int default 1,
	is_draft boolean not null default 0,
	is_traspase boolean not null default 0,
	created_at datetime not null,
	foreign key (stock_id) references stock(id),
	foreign key (stock_destination_id) references stock(id),
	foreign key (product_id) references product(id),
	foreign key (operation_type_id) references operation_type(id),
	foreign key (sell_id) references sell(id)
);

create table spend(
	id int not null auto_increment primary key,
	name varchar(50) not null,
	price double,
	box_id int,
	created_at datetime,
	foreign key(box_id) references box(id)
);


/*
configuration kind
1.- Boolean
2.- Text
3.- Number
*/
create table configuration(
	id int not null auto_increment primary key,
	short varchar(255) not null unique,
	name varchar(255) not null unique,
	kind int not null,
	val varchar(255) not null
);
insert into configuration(short,name,kind,val) value("company_name","Nombre de la empresa",2,"Powered by Inventio Max 7.0");
insert into configuration(short,name,kind,val) value("title","Titulo del Sistema",2,"Inventio Max 7.0");
insert into configuration(short,name,kind,val) value("ticket_title","Titulo en el Ticket",2,"INVENTIO MAX v7.0");
insert into configuration(short,name,kind,val) value("admin_email","Email Administracion",2,"");
insert into configuration(short,name,kind,val) value("report_image","Imagen en Reportes",4,"");
insert into configuration(short,name,kind,val) value("imp-name","Nombre Impuesto",2,"IVA");
insert into configuration(short,name,kind,val) value("imp-val","Valor Impuesto (%)",2,"16");
insert into configuration(short,name,kind,val) value("currency","Simbolo de Moneda",2,"$");


/* Nuevas tablas apartir de la version 4 */

create table payment_type(
	id int not null auto_increment not null primary key,
	name varchar(50)
);

insert into payment_type (id,name) value(1,"Cargo"),(2,"Abono");

create table payment(
	id int not null auto_increment not null primary key,
	payment_type_id int not null,
	sell_id int,
	person_id int not null,
	val double,
	created_at datetime not null,
	foreign key (person_id) references person(id),
	foreign key (sell_id) references sell(id),
	foreign key (payment_type_id) references payment_type(id)
);

/* small box */

create table saving (
	id int not null auto_increment primary key,
	concept varchar(255),
	description text,
	amount float,
	date_at date,
	kind int,/*1. in, 2. out*/
	created_at datetime
);

/* v6.1 */

create table message(
	id int not null auto_increment primary key,
	code varchar(255),
	message varchar(255),
	user_from int not null,
	user_to int not null,
	is_read boolean not null default 0,
	created_at datetime
);




