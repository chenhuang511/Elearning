CREATE TABLE `anchor_instructors` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(70) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(140) NOT NULL,
  `birthday` date NOT NULL,
  `subject` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `anchor_instructor_contract` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `instructor_id` int(6) NOT NULL,
  `type` enum('personal','organization') NOT NULL,
  `name_partner` varchar(200) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `salary` varchar(15) NOT NULL,
  `rules` varchar(1000) NOT NULL,
  `state` enum('unpaid','paid') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;