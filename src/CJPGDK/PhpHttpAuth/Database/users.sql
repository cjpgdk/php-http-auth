CREATE TABLE IF NOT EXISTS `users` (
  `id_users` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `users` ADD PRIMARY KEY (`id_users`);

ALTER TABLE `users` MODIFY `id_users` int(10) NOT NULL AUTO_INCREMENT;
