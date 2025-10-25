/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: moi-band
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Albums`
--

DROP TABLE IF EXISTS `Albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `coverImagePath` varchar(255) NOT NULL,
  `releaseDate` date DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Albums`
--

LOCK TABLES `Albums` WRITE;
/*!40000 ALTER TABLE `Albums` DISABLE KEYS */;
INSERT INTO `Albums` VALUES
(3,'Хроники забытых миров','«Хроники забытых миров» — это музыкальное путешествие длиной в 12 треков через\r\nлегенды, которые мир забыл. От пробуждения древних стражей до триумфального\r\nрассвета новой эры — альбом проводит слушателя через эпические битвы, мифические\r\nвозрождения, трагические падения и лирические истории о любви и потере.','/public/uploads/album_covers/68f4f7da160db.png','2025-09-25','2025-10-19 17:38:18','2025-10-19 17:38:18'),
(4,'Эхо миров','','/public/uploads/album_covers/68f521f7f26c8.webp','2024-12-19','2025-10-19 20:37:59','2025-10-19 20:37:59'),
(5,'Театр крови и костей (Live)','','/public/uploads/album_covers/68f5233f80410.png','2025-06-27','2025-10-19 20:43:27','2025-10-19 20:43:27'),
(6,'Нескоренна земля','','/public/uploads/album_covers/68f7301192cbb.jpg','2024-12-30','2025-10-21 10:02:41','2025-10-21 10:02:41'),
(7,'Вільний альбом','\"Вільний альбом\" — це потужне музичне висловлювання гурту Master of Illusion, що поєднує елементи симфо-року, пост-металу, ліричних балад та протестного драйву. Це альбом про свободу, боротьбу, біль, надію та внутрішнє світло, яке не згасає навіть у найтемніші часи.\r\n\r\nКожна з 13 композицій є окремою історією, але разом вони формують цілісне полотно — емоційну подорож через тіні минулого, буремну реальність і незламну віру в майбутнє. Від епічних гімнів типу \"Воля в ланцюгах\" і \"За межу\", до тендітних балад \"Незгасна\" та \"Твоє ім’я\", цей альбом балансує між ліричністю й бунтарством.\r\n\r\nТематика варіюється від особистого болю до колективного опору, від ніжних почуттів до вогняної рішучості. У текстах звучать голоси самітників, воїнів, коханих, шукачів світла. Центральною метафорою стає стріла свободи — палаюча, небезпечна, але правдива.\r\n\r\nЦе — музика для тих, хто не зраджує себе. Це — Вільний альбом.','/public/uploads/album_covers/68fbc287e8c00.png','2025-04-20','2025-10-24 21:16:39','2025-10-24 21:16:39');
/*!40000 ALTER TABLE `Albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChatMessages`
--

DROP TABLE IF EXISTS `ChatMessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ChatMessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_edited` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `ChatMessages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChatMessages`
--

LOCK TABLES `ChatMessages` WRITE;
/*!40000 ALTER TABLE `ChatMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `ChatMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Events`
--

DROP TABLE IF EXISTS `Events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('concert','release','announcement','stream') DEFAULT 'announcement',
  `event_date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `ticket_url` varchar(500) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_date` (`event_date`),
  KEY `idx_type` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Events`
--

LOCK TABLES `Events` WRITE;
/*!40000 ALTER TABLE `Events` DISABLE KEYS */;
/*!40000 ALTER TABLE `Events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Favorites`
--

DROP TABLE IF EXISTS `Favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  `addedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_favorite` (`user_id`,`track_id`),
  KEY `track_id` (`track_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `Favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Favorites_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Favorites`
--

LOCK TABLES `Favorites` WRITE;
/*!40000 ALTER TABLE `Favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `Favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PlayHistory`
--

DROP TABLE IF EXISTS `PlayHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PlayHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  `playedAt` datetime DEFAULT current_timestamp(),
  `duration_played` int(11) DEFAULT NULL COMMENT 'Сколько секунд сыграло',
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_track` (`track_id`),
  KEY `idx_played_at` (`playedAt`),
  KEY `idx_play_history_user` (`user_id`),
  CONSTRAINT `PlayHistory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `PlayHistory_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PlayHistory`
--

LOCK TABLES `PlayHistory` WRITE;
/*!40000 ALTER TABLE `PlayHistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `PlayHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PlayQueue`
--

DROP TABLE IF EXISTS `PlayQueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PlayQueue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `current_track_id` int(11) DEFAULT NULL,
  `queue_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON с идами треков в очереди' CHECK (json_valid(`queue_data`)),
  `currentTime` int(11) DEFAULT 0 COMMENT 'Текущая позиция в секундах',
  `isPlaying` tinyint(1) DEFAULT 0,
  `updatedAt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `current_track_id` (`current_track_id`),
  CONSTRAINT `PlayQueue_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `PlayQueue_ibfk_2` FOREIGN KEY (`current_track_id`) REFERENCES `Track` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PlayQueue`
--

LOCK TABLES `PlayQueue` WRITE;
/*!40000 ALTER TABLE `PlayQueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `PlayQueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PlaylistTracks`
--

DROP TABLE IF EXISTS `PlaylistTracks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PlaylistTracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `addedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_playlist_track` (`playlist_id`,`track_id`),
  KEY `track_id` (`track_id`),
  KEY `idx_playlist` (`playlist_id`),
  CONSTRAINT `PlaylistTracks_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `Playlists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `PlaylistTracks_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PlaylistTracks`
--

LOCK TABLES `PlaylistTracks` WRITE;
/*!40000 ALTER TABLE `PlaylistTracks` DISABLE KEYS */;
/*!40000 ALTER TABLE `PlaylistTracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Playlists`
--

DROP TABLE IF EXISTS `Playlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `coverImagePath` varchar(255) DEFAULT NULL,
  `isPublic` tinyint(1) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_public` (`isPublic`),
  CONSTRAINT `Playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Playlists`
--

LOCK TABLES `Playlists` WRITE;
/*!40000 ALTER TABLE `Playlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `Playlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Posts`
--

DROP TABLE IF EXISTS `Posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Posts`
--

LOCK TABLES `Posts` WRITE;
/*!40000 ALTER TABLE `Posts` DISABLE KEYS */;
INSERT INTO `Posts` VALUES
(3,'тест','тест новостей, и смотр внешнего вида',NULL,'2025-10-18 20:06:52','2025-10-18 20:06:52');
/*!40000 ALTER TABLE `Posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ratings`
--

DROP TABLE IF EXISTS `Ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) DEFAULT NULL,
  `album_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_track` (`user_id`,`track_id`),
  UNIQUE KEY `unique_user_album` (`user_id`,`album_id`),
  KEY `track_id` (`track_id`),
  KEY `album_id` (`album_id`),
  CONSTRAINT `Ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Ratings_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Ratings_ibfk_3` FOREIGN KEY (`album_id`) REFERENCES `Albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ratings`
--

LOCK TABLES `Ratings` WRITE;
/*!40000 ALTER TABLE `Ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `Ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RoomMessages`
--

DROP TABLE IF EXISTS `RoomMessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `RoomMessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_edited` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_room` (`room_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_room_messages_room` (`room_id`),
  CONSTRAINT `RoomMessages_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `RoomMessages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RoomMessages`
--

LOCK TABLES `RoomMessages` WRITE;
/*!40000 ALTER TABLE `RoomMessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `RoomMessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rooms`
--

DROP TABLE IF EXISTS `Rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `created_by` (`created_by`),
  KEY `idx_slug` (`slug`),
  CONSTRAINT `Rooms_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rooms`
--

LOCK TABLES `Rooms` WRITE;
/*!40000 ALTER TABLE `Rooms` DISABLE KEYS */;
INSERT INTO `Rooms` VALUES
(9,'General','general','🎸 Основной чат для всех фанов Power Metal','🎸',0,1,'2025-10-19 10:59:19'),
(10,'Gothic Lounge','gothic','🦇 Для поклонников Gothic Metal','🦇',0,1,'2025-10-19 10:59:19'),
(11,'Punk Garage','punk','🤘 Энергичный punk-rock уголок','🤘',0,1,'2025-10-19 10:59:19'),
(12,'News & Announcements','news','📢 Новости о группе и релизах','📢',0,1,'2025-10-19 10:59:19');
/*!40000 ALTER TABLE `Rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SongLyrics`
--

DROP TABLE IF EXISTS `SongLyrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `SongLyrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `track_id` int(11) NOT NULL,
  `lyrics` longtext DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `track_id` (`track_id`),
  CONSTRAINT `SongLyrics_ibfk_1` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SongLyrics`
--

LOCK TABLES `SongLyrics` WRITE;
/*!40000 ALTER TABLE `SongLyrics` DISABLE KEYS */;
INSERT INTO `SongLyrics` VALUES
(2,38,'[Verse 1]\r\nВ далёких горах, где туман\r\nСкрывает древние руины\r\nСпит последний великан\r\nДракон забытой долины.\r\nВека прошли с тех пор\r\nКогда он правил небесами.\r\nТеперь лишь старый фольклор\r\nХранит память о деяньях.\r\n\r\nНо пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Verse 2]\r\nРыцари давно забыли,\r\nКак сражаться с ним.\r\nМечи от ржи затупились,\r\nВ век новых технологий.\r\nНо есть среди людей один,\r\nКто верит в старые сказанья.\r\nОн знает - мифов властелин\r\nВернётся для воздаянья.\r\n\r\nВедь пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Bridge]\r\nПроснись, великий дракон!\r\nТвой час настал опять\r\nРазрушь обмана закон\r\nИ магию верни назад!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстал из глубины.\r\nСломал людские троны\r\nИ рушит их умы.\r\nЗолотым сияньем\r\nМир теперь объят.\r\nНовым мирозданьем\r\nПравит древний ритуал!','2025-10-19 17:40:46','2025-10-19 17:40:46'),
(5,92,'[verse 1]\r\nВ серці тиснуть залізні кайдани,\r\nНа очах у нас тисячі нових стін.\r\nСкільки слів поховали у рани,\r\nСкільки правди затис у свій сплін?\r\n\r\nНас ламають, нас ставлять на коліна,\r\nЇхній страх — це вогонь в наших жилах!\r\nПоки кров закипає в долонях,\r\nМи піднімемось знов!\r\n\r\n[pre-chorus]\r\nМи не мовчимо, наші голоси — грім,\r\nЦе не наша війна, і це не наш режим.\r\nПалають кайдани, тримтять їхні страхи,\r\nНас не зламати, вогонь у серцях!\r\n\r\n[chorus]\r\nВоля в ланцюгах, та серце палає,\r\nКрізь темряву ніч наш промінь сягає.\r\nСлова не розіб\'ють, не спинять біду,\r\nМи волю здобудем — на нашому шляху!\r\n\r\n[verse 2]\r\nЗалишилось тільки слово й надія,\r\nЇхня правда, мов дим на вітру.\r\nХтось боїться, хтось плаче від втрати,\r\nАле ми — це незламні воїни.\r\n\r\nСкільки раз нам в спину стріляли,\r\nСкільки раз нас кидали у бій,\r\nТа залізо не ріже закалений камінь,\r\nБо ми всі свободні. Відбій!\r\n\r\n[pre-chorus]\r\nМи не мовчимо, наші голоси — грім,\r\nЦе не наша війна, і це не наш режим.\r\nПалають кайдани, тримтять їхні страхи,\r\nНас не зламати, вогонь у серцях!\r\n\r\n[chorus]\r\nВоля в ланцюгах, та серце палає,\r\nКрізь темряву ніч наш промінь сягає.\r\nСлова не розіб\'ють, не спинять біду,\r\nМи волю здобудем — на нашому шляху!\r\n\r\n[bridge]\r\nСкільки ще кроків до свободи?\r\nСкільки ще ран витримає земля?\r\nМи не впадем, ми станем стіною!\r\nНашу свободу ми здобудем вогнем!\r\n\r\n\r\n[final chorus]\r\nВоля в ланцюгах — але ми ще живі!\r\nЗ вогнем у руках і гнівом в крові.\r\nЇхні слова — лише попіл вітру,\r\nМи не здамося, наш шлях в руках!\r\n\r\n[outro]\r\nВоля в ланцюгах… Але ми ще живі…\r\nНаш крик не згасне… Ми прорвемо метал.','2025-10-25 10:56:16','2025-10-25 10:56:16'),
(7,95,'[verse 1]\r\nКрізь морок, крізь холод, крізь безліч страхів,\r\nМи йдемо туди, де ще жоден не був.\r\nНа уламках надій ми будуєм мости,\r\nБо зламати цей світ не дозволим собі.\r\n\r\nВітри у обличчя, вогонь у грудях,\r\nНіхто нас не зупинить на цьому шляху!\r\nЦе не просто слова, це не просто ідея,\r\nЦе наш час, і він справді прийшов!\r\n\r\n[pre-chorus]:\r\nВідчуваєш у серці цей битий пульс?\r\nЦе свобода, що рветься крізь час і брухт!\r\n\r\n[chorus]\r\nЗа межу, де світло живе в крові,\r\nМи зламаєм цей морок на самоті!\r\nНаші руки тримають цей світ в долонях,\r\nМи переможем у цій війні!\r\n\r\n[verse 2]:\r\nТам, де тіні минулого рвуться назад,\r\nМи палим мости, щоб не було втрат.\r\nНаші голоси, як грім у ночі,\r\nМи залишим слід, навіть серед дощів.\r\n\r\nКожен із нас — це вогонь і сталь,\r\nНаш вибір простий: вперед або в прірву.\r\nЦей шлях нелегкий, але нас не зламати,\r\nМи вже за межею, і нам не здолати!\r\n\r\n[pre-chorus]\r\nВідчуваєш у серці цей битий пульс?\r\nЦе свобода, що рветься крізь час і брухт!\r\n\r\n[chorus]\r\nЗа межу, де світло живе в крові,\r\nМи зламаєм цей морок на самоті!\r\nНаші руки тримають цей світ в долонях,\r\nМи переможем у цій війні!\r\n\r\n[Bridge]\r\nСтіни падають, грані стираються,\r\nМи творимо світ, де зло розбивається.\r\nНас не зупинить, нас не зламає,\r\nНаш дух летить туди, де світло сягає!\r\n\r\n[final chorus]:\r\nЗа межу, де сонце горить над нами,\r\nДе свобода народжується з вогню!\r\nНаші серця — це зброя, наш крик — це правда,\r\nМи розірвем ланцюги в цьому сну!\r\n\r\n[outro]\r\nВоля жива… Воля в нас…\r\nКожен з нас — це світло, що несе крізь час.','2025-10-25 10:56:52','2025-10-25 10:56:52');
/*!40000 ALTER TABLE `SongLyrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Track`
--

DROP TABLE IF EXISTS `Track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `albumId` int(11) DEFAULT NULL,
  `coverImagePath` varchar(191) NOT NULL,
  `fullAudioPath` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fossbillingProductId` int(11) DEFAULT NULL,
  `lyrics` text DEFAULT NULL,
  `lyricsPath` varchar(255) DEFAULT NULL,
  `videoPath` varchar(255) DEFAULT NULL,
  `videoCoverPath` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT 0 COMMENT 'Длительность в секундах',
  `views` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `Track_albumId_fkey` (`albumId`),
  KEY `idx_track_album` (`albumId`),
  CONSTRAINT `Track_albumId_fkey` FOREIGN KEY (`albumId`) REFERENCES `Albums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Track`
--

LOCK TABLES `Track` WRITE;
/*!40000 ALTER TABLE `Track` DISABLE KEYS */;
INSERT INTO `Track` VALUES
(38,'1. Последний из драконов','<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>/var/www/www-root/data/www/lovix.top/admin/edit_track.php</b> on line <b>279</b><br />',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a937a2.wav','2025-10-19 17:40:10.604','2025-10-19 20:16:13',NULL,NULL,NULL,'/public/uploads/videos/68f51cdce1ecb.mp4',NULL,307,0),
(39,'2. Спящий Страж',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a97462.mp3','2025-10-19 17:40:10.619','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(40,'3. Стальной Легион',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a994f4.wav','2025-10-19 17:40:10.636','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(41,'4. Феникс',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a9d600.wav','2025-10-19 17:40:10.644','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(42,'5. Средиземье',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a9f684.wav','2025-10-19 17:40:10.653','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(43,'6. Прощальный взгляд',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa1726.wav','2025-10-19 17:40:10.661','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(44,'7. Восхождение',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa37ba.wav','2025-10-19 17:40:10.670','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(45,'8. изгнанник мира',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa583e.wav','2025-10-19 17:40:10.678','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(46,'9. Ледяной трон',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa78c4.wav','2025-10-19 17:40:10.686','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(47,'10. Цена Любви',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa9956.wav','2025-10-19 17:40:10.695','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(48,'11. Последний рассвет',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aab9eb.wav','2025-10-19 17:40:10.703','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(49,'12. Симфония вечности',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aada42.wav','2025-10-19 17:40:10.711','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(50,'1 .Шаги на воде',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522237d8e1.mp3','2025-10-19 20:38:43.514','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(51,'2. Бал Вечной Тьмы',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223823d9.mp3','2025-10-19 20:38:43.541','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(52,'3. Шёпот во тьме',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223864e9.mp3','2025-10-19 20:38:43.550','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(53,'4. Сияние в бездне',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238856e.mp3','2025-10-19 20:38:43.558','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(54,'5. Последний рассвет',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238a5f9.mp3','2025-10-19 20:38:43.567','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(55,'6. Ворон',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238c654.mp3','2025-10-19 20:38:43.575','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(56,'7. Аннабель Ли',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238e717.mp3','2025-10-19 20:38:43.583','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(57,'8. Мой создатель. История Франкенштейна',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f5222390796.mp3','2025-10-19 20:38:43.592','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(58,'9. Печать Мефистофеля',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522239280d.mp3','2025-10-19 20:38:43.600','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(59,'10. Маска и тень',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223948a7.mp3','2025-10-19 20:38:43.608','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(60,'11. Phantom of the Opera (бонус)',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f5222396936.mp3','2025-10-19 20:38:43.617','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(61,'1. Скелет под чердаком',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9489c2.wav','2025-10-19 20:45:45.297','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(62,'2. анархист-революционер',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c952c7b.wav','2025-10-19 20:45:45.339','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(63,'3. Грим под гробовой свет',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c95f679.wav','2025-10-19 20:45:45.391','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(64,'4. Баллада о рубщике голов',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c96b73d.wav','2025-10-19 20:45:45.441','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(65,'5. Дом, что смотрит в след',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c977cef.wav','2025-10-19 20:45:45.571','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(66,'6. Гробовщик с гармошкой',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c998736.wav','2025-10-19 20:45:45.625','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(67,'7. Кукловод',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9a8d1b.wav','2025-10-19 20:45:45.692','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(68,'8. Зеркало без отражения',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9b4d65.wav','2025-10-19 20:45:45.741','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(69,'9. Письмо из ниоткуда',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9c51bc.wav','2025-10-19 20:45:45.808','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(70,'10. Охотник За Душами',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9cfb3d.mp3','2025-10-19 20:45:45.851','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(71,'11. Сон в лапах медведя',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9d7ec7.wav','2025-10-19 20:45:45.885','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(72,'12. Полярный адмирал',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9e2fa8.wav','2025-10-19 20:45:45.930','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(73,'13. Театр крови',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9ec766.wav','2025-10-19 20:45:45.970','2025-10-19 20:45:45',NULL,NULL,NULL,NULL,NULL,0,0),
(74,'14. Торговец теней',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca00bdc.wav','2025-10-19 20:45:46.003','2025-10-19 20:45:46',NULL,NULL,NULL,NULL,NULL,0,0),
(75,'15. У тётки Агаты',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca03ccc.wav','2025-10-19 20:45:46.016','2025-10-19 20:45:46',NULL,NULL,NULL,NULL,NULL,0,0),
(76,'16. Хозяйка часов',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca05d3d.wav','2025-10-19 20:45:46.024','2025-10-19 20:45:46',NULL,NULL,NULL,NULL,NULL,0,0),
(77,'17. Театр живых - поппури',NULL,5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca112eb.wav','2025-10-19 20:45:46.071','2025-10-19 20:45:46',NULL,NULL,NULL,NULL,NULL,0,0),
(78,'1. Крила свободи',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303e57aeb.mp3','2025-10-21 10:03:26.359','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(79,'2. Вогонь у венах',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303e65931.mp3','2025-10-21 10:03:26.421','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(80,'3. Відлуння серця',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303eaff92.mp3','2025-10-21 10:03:26.721','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(81,'4. Крила вогню',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ec2b31.mp3','2025-10-21 10:03:26.798','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(82,'5. Крізь відстань',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ecd89c.mp3','2025-10-21 10:03:26.842','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(83,'6. Повна тиша',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ed5bfc.mp3','2025-10-21 10:03:26.876','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(84,'7. Тінь між зорями',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303edc479.mp3','2025-10-21 10:03:26.910','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(85,'8. Нескорена земля',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ee05a7.mp3','2025-10-21 10:03:26.919','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(86,'9. Світло в темряві',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ee261b.mp3','2025-10-21 10:03:26.927','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(87,'10. Сила в серці',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ee676c.mp3','2025-10-21 10:03:26.944','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(88,'11. Чорний попіл',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303ee87d6.mp3','2025-10-21 10:03:26.952','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(89,'12. Янголи',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303eea86e.mp3','2025-10-21 10:03:26.961','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(90,'13. Чорний попіл (бонус, женский вокал)',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303eec8e1.mp3','2025-10-21 10:03:26.969','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(91,'14. Phantom of the Opera (бонус)',NULL,6,'/public/uploads/album_covers/68f7301192cbb.jpg','/public/uploads/full/68f7303eee97f.mp3','2025-10-21 10:03:26.977','2025-10-21 10:03:26',NULL,NULL,NULL,NULL,NULL,0,0),
(92,'1. воля в ланцюгах','',7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc33721987.wav','2025-10-24 21:19:35.146','2025-10-24 21:24:52',NULL,NULL,NULL,NULL,NULL,267,0),
(93,'2. Кров на землі',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc33745980.wav','2025-10-24 21:19:35.287','2025-10-24 21:19:35',NULL,NULL,NULL,NULL,NULL,0,0),
(94,'3. Палаюча стріла',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc3376748a.wav','2025-10-24 21:19:35.424','2025-10-24 21:19:35',NULL,NULL,NULL,NULL,NULL,0,0),
(95,'4. За межу','',7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc3378a13a.wav','2025-10-24 21:19:35.568','2025-10-25 10:56:52',NULL,NULL,NULL,NULL,NULL,292,0),
(96,'5. Незгасна',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc337d1de6.wav','2025-10-24 21:19:35.864','2025-10-24 21:19:35',NULL,NULL,NULL,NULL,NULL,0,0),
(97,'6. Пробуди мене',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc33848ec3.wav','2025-10-24 21:19:36.357','2025-10-24 21:19:36',NULL,NULL,NULL,NULL,NULL,0,0),
(98,'7. сталеві коні',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc338ae1a1.wav','2025-10-24 21:19:36.713','2025-10-24 21:19:36',NULL,NULL,NULL,NULL,NULL,0,0),
(99,'8. Сонце у снах',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc338cdc21.wav','2025-10-24 21:19:36.843','2025-10-24 21:19:36',NULL,NULL,NULL,NULL,NULL,0,0),
(100,'9. Самітник',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc338e58b7.wav','2025-10-24 21:19:37.065','2025-10-24 21:19:37',NULL,NULL,NULL,NULL,NULL,0,0),
(101,'10. твоє ім\'я',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc33928e98.wav','2025-10-24 21:19:37.168','2025-10-24 21:19:37',NULL,NULL,NULL,NULL,NULL,0,0),
(102,'11. Вітер змін - live',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc33946d6d.wav','2025-10-24 21:19:37.290','2025-10-24 21:19:37',NULL,NULL,NULL,NULL,NULL,0,0),
(103,'12. тінь і світло',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc3395c17c.wav','2025-10-24 21:19:37.377','2025-10-24 21:19:37',NULL,NULL,NULL,NULL,NULL,0,0),
(104,'13. Швидше',NULL,7,'/public/uploads/album_covers/68fbc287e8c00.png','/public/uploads/full/68fbc339783f9.wav','2025-10-24 21:19:37.493','2025-10-24 21:19:37',NULL,NULL,NULL,NULL,NULL,0,0);
/*!40000 ALTER TABLE `Track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TrackLikes`
--

DROP TABLE IF EXISTS `TrackLikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TrackLikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_track` (`user_id`,`track_id`),
  KEY `track_id` (`track_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `TrackLikes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `TrackLikes_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TrackLikes`
--

LOCK TABLES `TrackLikes` WRITE;
/*!40000 ALTER TABLE `TrackLikes` DISABLE KEYS */;
/*!40000 ALTER TABLE `TrackLikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserDeviceTokens`
--

DROP TABLE IF EXISTS `UserDeviceTokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserDeviceTokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_token` varchar(500) NOT NULL,
  `device_type` enum('android','ios') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `UserDeviceTokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserDeviceTokens`
--

LOCK TABLES `UserDeviceTokens` WRITE;
/*!40000 ALTER TABLE `UserDeviceTokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `UserDeviceTokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserPreferences`
--

DROP TABLE IF EXISTS `UserPreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `UserPreferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `favorite_tracks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`favorite_tracks`)),
  `favorite_albums` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`favorite_albums`)),
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `email_notifications` tinyint(1) DEFAULT 0,
  `theme` enum('dark','light','gothic','punk') DEFAULT 'dark',
  `language` varchar(5) DEFAULT 'ru',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `UserPreferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserPreferences`
--

LOCK TABLES `UserPreferences` WRITE;
/*!40000 ALTER TABLE `UserPreferences` DISABLE KEYS */;
INSERT INTO `UserPreferences` VALUES
(1,1,NULL,NULL,1,0,'dark','ru','2025-10-19 10:59:19','2025-10-19 10:59:19'),
(2,2,NULL,NULL,1,0,'dark','ru','2025-10-19 11:23:36','2025-10-19 11:25:56'),
(4,4,NULL,NULL,1,0,'dark','ru','2025-10-22 12:29:42','2025-10-22 12:29:42');
/*!40000 ALTER TABLE `UserPreferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(150) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `status` enum('online','away','offline') DEFAULT 'offline',
  `last_seen` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `is_banned` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `verification_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES
(1,'admin','admin@lovix.top','$2y$12$t2X3dPjVV3u3i8V8xK2V0eZ2Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3Z3','Administrator',NULL,NULL,'online',NULL,'2025-10-19 10:59:19','2025-10-19 10:59:19',1,0,1,NULL,NULL),
(2,'dncdante','nfsdante@gmail.com','$argon2id$v=19$m=65536,t=4,p=1$Vk5DeW5sWGJnN3NLY2FwYQ$2t14wnpfvnikpVOKGWaH3LP3yJBOjUKgOTHbkgUnzJA','dncdante',NULL,NULL,'online','2025-10-22 21:40:49','2025-10-19 11:23:36','2025-10-22 21:40:49',0,0,0,'251fb5ec54910b63b1618003a7c0f03293c8f420c7d8efc5a4ec2a5a7ee102a9','2025-10-20 11:23:36'),
(4,'dante','support@sthosr.pro','$argon2id$v=19$m=65536,t=4,p=1$MTl5TGlYYWMvbHA0eUlnWQ$RpC1flRtz32S09fl+6hWlk+j4PH5GLhOCFhKVkYGcIA','dante',NULL,NULL,'offline',NULL,'2025-10-22 12:29:42','2025-10-22 12:29:42',0,0,0,NULL,NULL);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoClips`
--

DROP TABLE IF EXISTS `VideoClips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `VideoClips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `track_id` int(11) NOT NULL,
  `videoPath` varchar(255) NOT NULL,
  `coverImagePath` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Длительность видео в секундах',
  `views` int(11) DEFAULT 0,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_track` (`track_id`),
  CONSTRAINT `VideoClips_ibfk_1` FOREIGN KEY (`track_id`) REFERENCES `Track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoClips`
--

LOCK TABLES `VideoClips` WRITE;
/*!40000 ALTER TABLE `VideoClips` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoClips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'studio' COMMENT 'studio, concert, event, promo',
  `image_url` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_at`),
  KEY `idx_gallery_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'update' COMMENT 'release, event, update',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_at`),
  KEY `idx_news_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES
(1,'test','test','event','','2025-10-20 20:28:03','2025-10-20 20:28:03');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-25 11:02:42
