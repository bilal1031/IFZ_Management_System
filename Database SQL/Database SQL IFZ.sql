create table member (
	m_id int primary key auto_increment,
    name varchar(200),
    reg_date date,
    contact_number varchar(200)
);
create table fee (
	f_id int primary key auto_increment,
    month varchar(200),
    year varchar(200),
    fee int
);
create table attendence(
a_id int primary key auto_increment,
m_id int,
date date,
has_attented tinyint,
foreign key (m_id) references member(m_id) on delete cascade
);
create table payment(
p_id int primary key auto_increment,
m_id int,
f_id int,
is_paid tinyint,
foreign key (m_id) references member(m_id) on delete cascade,
foreign key (f_id) references fee(f_id) on delete cascade
);