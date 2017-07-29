ALTER TABLE school_term ADD COLUMN `order` TINYINT(11) UNIQUE;
ALTER TABLE school_term ADD COLUMN `active` TINYINT(11) DEFAULT 1;


ALTER TABLE `result_pins`
	DROP INDEX `unique_pin`,
	ADD UNIQUE INDEX `unique_pin` (`pin`, `student_id`, `class_id`, `term_id`);

ALTER TABLE `result_pins`
	DROP INDEX `serial`,
	ADD UNIQUE INDEX `serial` (`serial`, `student_id`, `term_id`);