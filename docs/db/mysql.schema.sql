DROP TABLE IF EXISTS `event_member`;

CREATE TABLE `event_member` (
  `event_id` INT UNSIGNED NOT NULL,
  `member_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `thumb_link` VARCHAR(255) NULL,
  `response` VARCHAR(3) NOT NULL DEFAULT 'yes',
  `rsvp_id` INT UNSIGNED NOT NULL,
  `mtime` BIGINT UNSIGNED NOT NULL,
  `winner` TINYINT(1) UNSIGNED NULL,
  `notHere` TINYINT(1) UNSIGNED NULL,
  PRIMARY KEY (`event_id`,`member_id`),
  INDEX `event_rsvp_idx` (`rsvp_id`),
  INDEX `event_mtime_idx` (`mtime`)
);