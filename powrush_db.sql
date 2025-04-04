-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 04 2025 г., 12:13
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `powrush_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_panel`
--

CREATE TABLE `admin_panel` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(83, 5, 1, 4),
(84, 5, 3, 3),
(85, 6, 1, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Куртки', '2025-04-03 12:42:05'),
(2, 'Штаны', '2025-04-03 12:42:05'),
(3, 'Перчатки', '2025-04-03 12:42:05');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` text NOT NULL,
  `payment_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `address`, `payment_method`) VALUES
(3, 5, 0.00, 'pending', '2025-04-03 13:27:07', 'Moscow', 'paypal'),
(4, 5, 0.00, 'pending', '2025-04-03 13:30:59', 'й', 'credit_card'),
(5, 5, 0.00, 'pending', '2025-04-03 13:35:19', 'Moscow\r\n', 'credit_card'),
(6, 5, 0.00, 'pending', '2025-04-04 07:30:42', 'Moscow', 'cash_on_delivery'),
(7, 5, 0.00, 'pending', '2025-04-04 09:11:49', 'Moscow', 'paypal'),
(8, 5, 0.00, 'pending', '2025-04-04 09:27:33', 'Moscow', 'cash_on_delivery'),
(9, 5, 0.00, 'pending', '2025-04-04 09:30:37', 'Kras', 'cash_on_delivery'),
(10, 5, 0.00, 'pending', '2025-04-04 09:36:29', 'ййй', 'payment_method'),
(11, 5, 0.00, 'pending', '2025-04-04 09:42:53', 'maykop', 'cash_on_delivery'),
(12, 5, 0.00, 'pending', '2025-04-04 09:46:26', 'уцйуцй', 'paypal'),
(13, 5, 0.00, 'pending', '2025-04-04 09:52:50', 'USA', 'cash_on_delivery'),
(14, 5, 0.00, 'pending', '2025-04-04 09:53:25', 'куда то', 'credit_card'),
(15, 5, 0.00, 'pending', '2025-04-04 09:54:19', 'йцуйуй', 'paypal'),
(16, 5, 0.00, 'pending', '2025-04-04 09:55:10', 'eqweq', 'cash_on_delivery'),
(17, 5, 0.00, 'pending', '2025-04-04 09:57:22', 'qwerty', 'credit_card'),
(18, 5, 0.00, 'pending', '2025-04-04 10:01:49', 'МАГАДАН', 'cash_on_delivery');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 3, 2, 1, 0.00),
(6, 4, 3, 22, 0.00),
(7, 5, 1, 8, 0.00),
(8, 6, 1, 5, 0.00),
(9, 6, 2, 3, 0.00),
(10, 6, 3, 2, 0.00),
(11, 7, 1, 3, 0.00),
(12, 8, 1, 1, 0.00),
(13, 9, 1, 5, 0.00),
(14, 10, 1, 3, 0.00),
(15, 11, 1, 3, 0.00),
(16, 12, 1, 3, 0.00),
(17, 13, 1, 9, 0.00),
(18, 14, 1, 2, 0.00),
(19, 14, 2, 1, 0.00),
(20, 14, 3, 1, 0.00),
(21, 15, 1, 1, 0.00),
(22, 16, 1, 4, 0.00),
(23, 17, 3, 2, 0.00),
(24, 18, 1, 6, 0.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `image_url`, `created_at`) VALUES
(1, 'Сноубордическая куртка X', 'Теплая и водонепроницаемая', 120.00, 10, 1, 'images/jacket_x.jpg', '2025-04-03 12:42:16'),
(2, 'Сноубордические штаны Y', 'Легкие и удобные', 90.00, 15, 2, 'images/pants_y.jpg', '2025-04-03 12:42:16'),
(3, 'Перчатки Z', 'С защитой от холода', 30.00, 20, 3, 'images/gloves_z.jpg', '2025-04-03 12:42:16');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Иван Иванов', 'ivan@example.com', 'hashed_password_1', 'customer', '2025-04-03 12:41:46'),
(2, 'Анна Смирнова', 'anna@example.com', 'hashed_password_2', 'customer', '2025-04-03 12:41:46'),
(3, 'Админ', 'admin@example.com', 'hashed_password_3', 'admin', '2025-04-03 12:41:46'),
(4, 'йй', 'admin2@mail.ru', '$2y$10$lH7qtuBaihnhJsk/2Ys/neunGLTCgJF2ib6.0cOJQS37rC2Dnw8ge', 'customer', '2025-04-03 12:57:49'),
(5, 'kiota', 'admin@mail.ru', '$2y$10$Bdsn5RDljsHsRmZu0jM14e3ewfXpRq1K2vyEmTqrFVNslZwkS/C/e', 'customer', '2025-04-03 13:02:05'),
(6, 'kiota', 'adminn@mail.ru', '$2y$10$tbZplW9kZzt26f9FDaBTfedR.OD/nOPRnOFGT7g066y2mZQhnd/oO', 'customer', '2025-04-04 09:12:30');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_panel`
--
ALTER TABLE `admin_panel`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_panel`
--
ALTER TABLE `admin_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
