USE user_crud;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mobile_number` varchar(32) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` varchar(128) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `timezone` varchar(32) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Uncomment to add a test user
/*
insert into users (`first_name`, `last_name`, `email`, `mobile_number`, `address`, `city`, `state`, `zip`, `country`, `timezone`, `created`) values
(
    'John',
    'Doe',
    'jdoe@test.com',
    '1234567890',
    '123 Main St',
    'San Francisco',
    'CA',
    '94105',
    'US',
    'America/Los_Angeles',
    '2023-01-01 00:00:00'
);
*/