ALTER TABLE school_term ADD COLUMN `order` TINYINT(11) UNIQUE;
ALTER TABLE school_term ADD COLUMN `active` TINYINT(11) DEFAULT 1;