CREATE DATABASE taskforce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE `users` (
                         `id` int PRIMARY KEY AUTO_INCREMENT,
                         `role` varchar(10),
                         `name` varchar(128) NOT NULL,
                         `email` varchar(64) NOT NULL,
                         `password` varchar(128) NOT NULL,
                         `city_id` int NOT NULL,
                         `full_address` varchar(256),
                         `birthday` date,
                         `avatar_src` varchar(128),
                         `phone` varchar(20),
                         `skype` varchar(128),
                         `over_messenger` varchar(128),
                         `rating` float,
                         `created_at` datetime,
                         `updated_at` datetime
);

CREATE TABLE `users_specialty` (
                                   `id` int PRIMARY KEY AUTO_INCREMENT,
                                   `user_id` int NOT NULL,
                                   `specialty_id` int NOT NULL
);

CREATE TABLE `specialties` (
                               `id` int PRIMARY KEY AUTO_INCREMENT,
                               `title_specialty` varchar(64) NOT NULL
);

CREATE TABLE `portfolio` (
                             `id` int PRIMARY KEY AUTO_INCREMENT,
                             `user_id` int NOT NULL,
                             `img_src` varchar(256) NOT NULL
);

CREATE TABLE `user_settings` (
                                 `id` int PRIMARY KEY AUTO_INCREMENT,
                                 `user_id` int UNIQUE NOT NULL,
                                 `is_message_notification_allowed` bool NOT NULL,
                                 `action_notification` bool NOT NULL,
                                 `new_review_notification` bool NOT NULL,
                                 `show_contacts_only_customer` bool NOT NULL,
                                 `is_active` bool NOT NULL
);

CREATE TABLE `cities` (
                          `id` int PRIMARY KEY AUTO_INCREMENT,
                          `title` varchar(64) NOT NULL,
                          `location` point NOT NULL
);

CREATE TABLE `tasks` (
                         `id` int PRIMARY KEY AUTO_INCREMENT,
                         `customer_id` int UNIQUE NOT NULL,
                         `executor_id` int UNIQUE,
                         `title` varchar(64) NOT NULL,
                         `category_id` varchar(64) NOT NULL,
                         `state` varchar(10) NOT NULL,
                         `price` int NOT NULL,
                         `deadline` date,
                         `attachment_src` varchar(256),
                         `city_id` int,
                         `address` varchar(256),
                         `address_comment` varchar(256),
                         `created_at` datetime,
                         `updated_at` datetime
);

CREATE TABLE `task_attachments` (
                                    `id` int PRIMARY KEY AUTO_INCREMENT,
                                    `task_id` int NOT NULL,
                                    `file_type` varchar(32) NOT NULL,
                                    `file_name` varchar(64) NOT NULL,
                                    `file_src` varchar(256) NOT NULL
);

CREATE TABLE `messages` (
                            `id` int PRIMARY KEY AUTO_INCREMENT,
                            `sender_id` int NOT NULL,
                            `addressee_id` int NOT NULL,
                            `message_content` text NOT NULL,
                            `created_at` datetime
);

CREATE TABLE `reviews` (
                           `id` int PRIMARY KEY AUTO_INCREMENT,
                           `sender_id` int NOT NULL,
                           `addressee_id` int NOT NULL,
                           `task_id` int UNIQUE NOT NULL,
                           `review_rating` int(1) NOT NULL,
                           `review_content` text NOT NULL,
                           `created_at` datetime
);

CREATE TABLE `responses` (
                             `id` int PRIMARY KEY AUTO_INCREMENT,
                             `task_id` int UNIQUE NOT NULL,
                             `user_id` int NOT NULL,
                             `created_at` datetime,
                             `updated_at` datetime
);

ALTER TABLE `users_specialty` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `users_specialty` ADD FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`);

ALTER TABLE `portfolio` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `user_settings` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `tasks` (`customer_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `tasks` (`executor_id`);

ALTER TABLE `task_attachments` ADD FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`addressee_id`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`addressee_id`) REFERENCES `users` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`id`) REFERENCES `reviews` (`task_id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`id`) REFERENCES `responses` (`task_id`);

ALTER TABLE `responses` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
