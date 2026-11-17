-- À exécuter sur une base KOSMETIK existante (créée avec l'ancien kosmetik.sql).
-- Les mots de passe en clair existants sont automatiquement convertis en hash
-- bcrypt lors de la prochaine connexion de chaque utilisateur.
ALTER TABLE `users` MODIFY `password` varchar(255) NOT NULL;
ALTER TABLE `orders` ENGINE=InnoDB;
ALTER TABLE `orders` ADD INDEX `email` (`email`);
ALTER DATABASE `kosmetik` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `users`    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `products` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `orders`   CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
