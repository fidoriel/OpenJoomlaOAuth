ALTER TABLE `#__openjoomla_oauth_config` ADD COLUMN  `login_link_check` boolean DEFAULT false;

CREATE TABLE IF NOT EXISTS `#__openjoomla_role_mapping` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `usergroup_id` int(10) unsigned NOT NULL,
    `role_string` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_usergroup_role` (`usergroup_id`, `role_string`(191)),
    CONSTRAINT `fk_oauth_usergroup` FOREIGN KEY (`usergroup_id`) 
        REFERENCES `#__usergroups` (`id`) ON DELETE CASCADE
) DEFAULT COLLATE=utf8_general_ci;
