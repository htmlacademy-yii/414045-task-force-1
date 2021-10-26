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
    about          text,
    avatar_src     varchar(128),
    phone          varchar(20),
    skype          varchar(128),
    over_messenger varchar(128),
    rating         int,
    created_at     datetime default current_timestamp,
    updated_at     datetime default current_timestamp on update current_timestamp,
    FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE categories
(
    id    int PRIMARY KEY AUTO_INCREMENT,
    title varchar(64) NOT NULL
);

CREATE TABLE users_specialty
(
    id          int PRIMARY KEY AUTO_INCREMENT,
    user_id     int NOT NULL,
    category_id int NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
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
    customer_id     int         NOT NULL,
    executor_id     int,
    title           varchar(64) NOT NULL,
    description     text,
    category_id     int         NOT NULL,
    state           varchar(10),
    price           int,
    deadline        date,
    city_id         int,
    address         varchar(256),
    address_comment varchar(256),
    created_at      datetime default current_timestamp,
    updated_at      datetime default current_timestamp on update current_timestamp,
    FOREIGN KEY (customer_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id),
    FOREIGN KEY (city_id) REFERENCES cities (id)
);

CREATE TABLE task_attachments
(
    id             int PRIMARY KEY AUTO_INCREMENT,
    task_id        int          NOT NULL,
    file_base_name varchar(256) NOT NULL,
    file_name      varchar(64)  NOT NULL,
    file_src       varchar(256) NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE messages
(
    id           int PRIMARY KEY AUTO_INCREMENT,
    sender_id    int  NOT NULL,
    addressee_id int  NOT NULL,
    content      text NOT NULL,
    created_at   datetime default current_timestamp,
    FOREIGN KEY (sender_id) REFERENCES users (id),
    FOREIGN KEY (addressee_id) REFERENCES users (id)
);

CREATE TABLE reviews
(
    id           int PRIMARY KEY AUTO_INCREMENT,
    sender_id    int        NOT NULL,
    addressee_id int        NOT NULL,
    task_id      int UNIQUE NOT NULL,
    rating       int,
    comment      text,
    created_at   datetime default current_timestamp,
    FOREIGN KEY (sender_id) REFERENCES users (id),
    FOREIGN KEY (addressee_id) REFERENCES users (id),
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE responses
(
    id         int PRIMARY KEY AUTO_INCREMENT,
    task_id    int NOT NULL,
    user_id    int NOT NULL,
    content    text,
    price      int,
    state      varchar(10),
    created_at datetime default current_timestamp,
    updated_at datetime default current_timestamp on update current_timestamp,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);
