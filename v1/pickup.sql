CREATE TABLE requestor(
 name varchar(255) NOT NULL,
 email varchar(255),
 password varchar(255),
 salt varchar(255),
 number varchar(255),
 id varchar(255),
 created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY ('id')
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1

CREATE TABLE driver(
 name varchar(255) NOT NULL,
 email varchar(255),
 password varchar(255),
 salt varchar(255),
 phone_num varchar(255),
 driver_id varchar(255),
 car_reg varchar(255) NOT NULL,
 model_type text NOT NULL,
 rating int(11),
 created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (id),
 UNIQUE KEY email(email)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1

CREATE TABLE engagements (
 eng_id int(11) NOT NULL AUTO_INCREMENT,
 drop_id text NOT NULL,
 drop_num text NOT NULL,
 drop_coords text NOT NULL,
 pick_id text NOT NULL,
 pick_num text NOT NULL,
 pick_coords text NOT NULL,
 load_desc text,
 driver_id varchar(255) NOT NULL,
 est_distance int(11),
 est_cost int(11),
 status int(11),
 pick_time varchar(255),
 drop_time varchar(255),
 closure_doc int(11) NOT NULL,
 created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (eng_id),
 CONSTRAINT eng_ibfk_3 FOREIGN KEY (driver_id) REFERENCES driver(driver_id)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1

CREATE TABLE tracking(
 eng_id int(11) NOT NULL AUTO_INCREMENT,
 lat_long_now text NOT NULL,
 lat_long_pick text NOT NULL,
 lat_long_drop text NOT NULL,
 pick_time text NOT NULL,
 time_now timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (eng_id),
  CONSTRAINT fk_engid FOREIGN KEY (eng_id) REFERENCES engagements (eng_id)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1