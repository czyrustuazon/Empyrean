# Empyrean 
- PHP Version 8.0.12
- MariaDB 10.4.21
- Apache 2.4.51 
But it should run fine on any LAMP stack. Untested on any PHP version lower than 8.0

## .example Files
Don't forget to remove the .example extension before beginning to work on this project
Those files should be located in:

- \public_html\api\ .htaccess.example
- \public_html\api\image\ .htaccess.example
- \includes\ constants.php.example

For the two .htaccess file, update the Rewritebase to match where the root of folder is.
For the constants file, just fill up your credentials accordingly

## Table Structure
```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);
```