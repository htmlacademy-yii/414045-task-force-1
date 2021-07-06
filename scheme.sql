CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE cities
(
    id       int PRIMARY KEY AUTO_INCREMENT,
    title    varchar(64) NOT NULL,
    location point       NOT NULL
);

CREATE TABLE users
(
    id             int PRIMARY KEY AUTO_INCREMENT,
    role           int(1),
    name           varchar(128) NOT NULL,
    email          varchar(64)  NOT NULL,
    password       varchar(128) NOT NULL,
    city_id        int          NOT NULL,
    full_address   varchar(256),
    birthday       date,
    avatar_src     varchar(128),
    phone          varchar(20),
    skype          varchar(128),
    over_messenger varchar(128),
    rating         int,
    created_at     datetime,
    updated_at     datetime,
    FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE specialties
(
    id    int PRIMARY KEY AUTO_INCREMENT,
    title varchar(64) NOT NULL
);

CREATE TABLE users_specialty
(
    id           int PRIMARY KEY AUTO_INCREMENT,
    user_id      int NOT NULL,
    specialty_id int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (specialty_id) REFERENCES specialties (id)
);

CREATE TABLE portfolio
(
    id      int PRIMARY KEY AUTO_INCREMENT,
    user_id int          NOT NULL,
    img_src varchar(256) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE user_settings
(
    id                        int PRIMARY KEY AUTO_INCREMENT,
    user_id                   int UNIQUE NOT NULL,
    is_message_ntf_enabled    bool       NOT NULL,
    is_action_ntf_enabled     bool       NOT NULL,
    is_new_review_ntf_enabled bool       NOT NULL,
    is_hidden                 bool       NOT NULL,
    is_active                 bool       NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE tasks
(
    id              int PRIMARY KEY AUTO_INCREMENT,
    customer_id     int UNIQUE  NOT NULL,
    executor_id     int UNIQUE,
    title           varchar(64) NOT NULL,
    speciality_id   int         NOT NULL,
    state           varchar(10) NOT NULL,
    price           int         NOT NULL,
    deadline        date,
    attachment_src  varchar(256),
    city_id         int,
    address         varchar(256),
    address_comment varchar(256),
    created_at      datetime,
    updated_at      datetime,
    FOREIGN KEY (customer_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (speciality_id) REFERENCES specialties (id),
    FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE task_attachments
(
    id        int PRIMARY KEY AUTO_INCREMENT,
    task_id   int          NOT NULL,
    file_type varchar(32)  NOT NULL,
    file_name varchar(64)  NOT NULL,
    file_src  varchar(256) NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE messages
(
    id           int PRIMARY KEY AUTO_INCREMENT,
    sender_id    int  NOT NULL,
    addressee_id int  NOT NULL,
    content      text NOT NULL,
    created_at   datetime,
    FOREIGN KEY (sender_id) REFERENCES users (id),
    FOREIGN KEY (addressee_id) REFERENCES users (id)
);

CREATE TABLE reviews
(
    id           int PRIMARY KEY AUTO_INCREMENT,
    sender_id    int        NOT NULL,
    addressee_id int        NOT NULL,
    task_id      int UNIQUE NOT NULL,
    rating       int    NOT NULL,
    content      text       NOT NULL,
    created_at   datetime,
    FOREIGN KEY (sender_id) REFERENCES users (id),
    FOREIGN KEY (addressee_id) REFERENCES users (id),
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE responses
(
    id         int PRIMARY KEY AUTO_INCREMENT,
    task_id    int UNIQUE NOT NULL,
    user_id    int        NOT NULL,
    created_at datetime,
    updated_at datetime,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);
