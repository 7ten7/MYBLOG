CREATE TABLE users(
id int(10) not null auto_increment primary key,
username varchar(20) not null,
userpwd varchar(32) not null,
name varchar(12) not null,
tel varchar(15) not null,
login_num int(10) not null default 0,
last_login_time int(10),
last_login_ip char(15),
status tinyint(1) not null default 1,
addate int(10) not null
);



CREATE TABLE links(
id int(10) not null auto_increment primary key,
webname varchar(40) not null,
website varchar(100) not null,
orderby tinyint(2) not null default 50,
addtime int(10) not null
);

CREATE TABLE category(
id int(10) not null auto_increment primary key,
name varchar(30),
alias varchar(30),
pid int(10),
keywords varchar(60),
describes varchar(200)
);

CREATE TABLE articles(
`id` int(10) not null auto_increment primary key,
`c_id` int(10) not null,
`u_id` int(10) not null,
`title` varchar(50) not null,
`content` text not null,
`comment_count` int,
`label` varchar(100),
`describes` varchar(200),
`t_img` varchar(200),
`time` int(10),
`orderby` tinyint(2) not null default 50,
`read` int,
open tinyint(1) default 1
);