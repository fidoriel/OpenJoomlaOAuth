CREATE TABLE IF NOT EXISTS `#__openjoomla_oauth_config` ( 
`id` int(11) UNSIGNED NOT NULL ,
`appname` VARCHAR(255)  NOT NULL ,
`custom_app` VARCHAR(255) NOT NULL ,
`client_id` VARCHAR(255)  NOT NULL ,
`client_secret` VARCHAR(255)  NOT NULL ,
`app_scope` VARCHAR(255)  NOT NULL ,
`authorize_endpoint` VARCHAR(255) NOT NULL,
`access_token_endpoint` VARCHAR(255)  NOT NULL,
`user_info_endpoint` VARCHAR(255) NOT NULL,
`redirecturi` VARCHAR(255) NOT NULL, 
`email_attr` VARCHAR(255) NOT NULL,
`full_name_attr` VARCHAR(255) NOT NULL,
`user_name_attr` VARCHAR(255) NOT NULL,
`httpreferer` VARCHAR(255) NOT NULL,
`in_header_or_body` VARCHAR(255) NOT NULL default 'both',
`login_link_check` boolean DEFAULT FALSE,
`test_attribute_name` TEXT NOT NULL,
`proxy_server_url` VARCHAR(255),
`proxy_server_port` int(5) DEFAULT 0,
`proxy_username` VARCHAR(255),
`proxy_password` VARCHAR(255),
`proxy_set` VARCHAR(20),
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__openjoomla_role_mapping` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `usergroup_id` int(10) unsigned NOT NULL,
    `role_string` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_usergroup_role` (`usergroup_id`, `role_string`(191)),
    CONSTRAINT `fk_oauth_usergroup` FOREIGN KEY (`usergroup_id`) 
        REFERENCES `#__usergroups` (`id`) ON DELETE CASCADE
) DEFAULT COLLATE=utf8_general_ci;

INSERT IGNORE INTO `#__openjoomla_oauth_config`(`id`) values (1);