-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 26. kvě 2026, 16:48
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `wa_projekt_peceni`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Dorty a koláče'),
(2, 'Kynuté pečivo'),
(3, 'Sušenky a cukroví'),
(4, 'Slané pečení'),
(5, 'Chléb a houstičky'),
(6, 'Nepečené dezerty'),
(7, 'Zdravé a fit'),
(8, 'Bezlepkové'),
(9, 'Veganské');

-- --------------------------------------------------------

--
-- Struktura tabulky `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `comments`
--

INSERT INTO `comments` (`id`, `recipe_id`, `user_id`, `text`, `created_at`) VALUES
(1, 1, 1, 'Mňam!!!', '2026-05-07 11:00:25');

-- --------------------------------------------------------

--
-- Struktura tabulky `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `likes`
--

CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `likes`
--

INSERT INTO `likes` (`user_id`, `recipe_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `description`, `ingredients`, `instructions`, `category_id`, `prep_time`, `images`, `created_by`, `created_at`) VALUES
(1, 'Ultimate Muffin Recipe', 'Use this base muffin recipe to make any flavor of muffin you can dream up! They’re easy, delicious, and freezer friendly. See Notes section below the recipe for my 5 favorite flavor variations, plus baking instructions for jumbo and mini muffins.', '1 and 3/4 cups (219g) all-purpose flour (spooned &amp;amp; leveled)\r\n1 teaspoon baking powder\r\n1 teaspoon baking soda\r\n1/2 teaspoon salt\r\n1/2 teaspoon ground cinnamon (optional, depending on your add-ins)*\r\n1/2 cup (8 Tbsp; 113g) unsalted butter, softened to room temperature\r\n1/2 cup (100g) granulated sugar\r\n1/4 cup (50g) packed light brown sugar \r\n1/2 cup (120g) sour cream or plain yogurt, at room temperature\r\n2 large eggs, at room temperature\r\n1 and 1/2 teaspoons pure vanilla extract\r\n1/4 cup (60ml) milk (any kind), at room temperature\r\nadd-ins of your choice (see Notes)*\r\noptional if not using crumb topping: 2 Tablespoons coarse sugar, for sprinkling', 'Preheat oven to 425°F (218°C). Spray a 12-count muffin pan with nonstick spray or line with cupcake liners. Set aside.\r\nMake the crumb topping, if using: In a medium bowl, mix the brown sugar, granulated sugar, and cinnamon. Stir in the melted butter. Using a fork, gently work in the flour just enough to combine. Do not overmix; the mixture should have large crumbles. Set topping aside or refrigerate until ready to use.\r\nMake the muffins: In a large bowl, whisk the flour, baking powder, baking soda, salt, and cinnamon (if using) together. Set aside.\r\nIn a large bowl using a handheld or stand mixer fitted with a paddle attachment, beat the butter, granulated sugar, and brown sugar on high speed until smooth and creamy, about 3 minutes. Scrape down the sides and bottom of the bowl as needed. (Here’s a helpful tutorial if you need guidance on how to cream butter and sugar.) Add the sour cream, eggs, and vanilla. Beat on medium speed for 1 minute, then turn up to high speed until the mixture is combined. Scrape down the sides and bottom of the bowl as needed.\r\nPour the dry ingredients into the wet ingredients and beat on low speed until just about combined. Add the milk and continue to beat on low speed until combined. Fold in your chosen add-ins. (See flavors/details in the Notes below.) The batter should be thick.\r\nSpoon the batter into the prepared muffin cups, filling them all the way to the top. Sprinkle the tops with coarse sugar or spoon on the crumb topping and lightly press the crumbles so they stick to the batter.\r\nBake the muffins for 5 minutes at 425°F (218°C); then, keeping the muffins in the oven, reduce the oven temperature to 350°F (177°C). Bake for an additional 15–18 minutes or until a toothpick inserted in the center comes out clean. The total time these muffins take in the oven is about 22–23 minutes, give or take. Allow the muffins to cool for 10 minutes in the muffin pan set on a cooling rack, then transfer the muffins from the pan to the rack to cool completely.\r\nStore muffins covered tightly at room temperature for up to 5 days, or in the refrigerator for up to 1 week. ', 3, 80, '[\"recipe_69fc701907e61_db5c.webp\"]', 1, '2026-05-07 10:57:29');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `nickname`, `bio`, `role`, `created_at`) VALUES
(1, 'cristiano', 'cristiano@cristiano-test.com', '$2y$10$6jG9D9vQ95RZLDJeNIik5u2pkmWNyU3EtoeSaQwfTi0VVtr/7j/8y', 'cris', 'anoanaonaona', 'gingiebingie', 'jsem cristiano ronaldo', 'user', '2026-05-07 10:39:18');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pro tabulku `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexy pro tabulku `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`user_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexy pro tabulku `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pro tabulku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
