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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SongLyrics`
--

LOCK TABLES `SongLyrics` WRITE;
/*!40000 ALTER TABLE `SongLyrics` DISABLE KEYS */;
INSERT INTO `SongLyrics` VALUES
(2,38,'[Verse 1]\r\nВ далёких горах, где туман\r\nСкрывает древние руины\r\nСпит последний великан\r\nДракон забытой долины.\r\nВека прошли с тех пор\r\nКогда он правил небесами.\r\nТеперь лишь старый фольклор\r\nХранит память о деяньях.\r\n\r\nНо пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Verse 2]\r\nРыцари давно забыли,\r\nКак сражаться с ним.\r\nМечи от ржи затупились,\r\nВ век новых технологий.\r\nНо есть среди людей один,\r\nКто верит в старые сказанья.\r\nОн знает - мифов властелин\r\nВернётся для воздаянья.\r\n\r\nВедь пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Bridge]\r\nПроснись, великий дракон!\r\nТвой час настал опять\r\nРазрушь обмана закон\r\nИ магию верни назад!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстал из глубины.\r\nСломал людские троны\r\nИ рушит их умы.\r\nЗолотым сияньем\r\nМир теперь объят.\r\nНовым мирозданьем\r\nПравит древний ритуал!','2025-10-19 17:40:46','2025-10-19 17:40:46'),
(5,92,'[verse 1]\r\nВ серці тиснуть залізні кайдани,\r\nНа очах у нас тисячі нових стін.\r\nСкільки слів поховали у рани,\r\nСкільки правди затис у свій сплін?\r\n\r\nНас ламають, нас ставлять на коліна,\r\nЇхній страх — це вогонь в наших жилах!\r\nПоки кров закипає в долонях,\r\nМи піднімемось знов!\r\n\r\n[pre-chorus]\r\nМи не мовчимо, наші голоси — грім,\r\nЦе не наша війна, і це не наш режим.\r\nПалають кайдани, тримтять їхні страхи,\r\nНас не зламати, вогонь у серцях!\r\n\r\n[chorus]\r\nВоля в ланцюгах, та серце палає,\r\nКрізь темряву ніч наш промінь сягає.\r\nСлова не розіб\'ють, не спинять біду,\r\nМи волю здобудем — на нашому шляху!\r\n\r\n[verse 2]\r\nЗалишилось тільки слово й надія,\r\nЇхня правда, мов дим на вітру.\r\nХтось боїться, хтось плаче від втрати,\r\nАле ми — це незламні воїни.\r\n\r\nСкільки раз нам в спину стріляли,\r\nСкільки раз нас кидали у бій,\r\nТа залізо не ріже закалений камінь,\r\nБо ми всі свободні. Відбій!\r\n\r\n[pre-chorus]\r\nМи не мовчимо, наші голоси — грім,\r\nЦе не наша війна, і це не наш режим.\r\nПалають кайдани, тримтять їхні страхи,\r\nНас не зламати, вогонь у серцях!\r\n\r\n[chorus]\r\nВоля в ланцюгах, та серце палає,\r\nКрізь темряву ніч наш промінь сягає.\r\nСлова не розіб\'ють, не спинять біду,\r\nМи волю здобудем — на нашому шляху!\r\n\r\n[bridge]\r\nСкільки ще кроків до свободи?\r\nСкільки ще ран витримає земля?\r\nМи не впадем, ми станем стіною!\r\nНашу свободу ми здобудем вогнем!\r\n\r\n\r\n[final chorus]\r\nВоля в ланцюгах — але ми ще живі!\r\nЗ вогнем у руках і гнівом в крові.\r\nЇхні слова — лише попіл вітру,\r\nМи не здамося, наш шлях в руках!\r\n\r\n[outro]\r\nВоля в ланцюгах… Але ми ще живі…\r\nНаш крик не згасне… Ми прорвемо метал.','2025-10-25 10:56:16','2025-10-25 10:56:16'),
(7,95,'[verse 1]\r\nКрізь морок, крізь холод, крізь безліч страхів,\r\nМи йдемо туди, де ще жоден не був.\r\nНа уламках надій ми будуєм мости,\r\nБо зламати цей світ не дозволим собі.\r\n\r\nВітри у обличчя, вогонь у грудях,\r\nНіхто нас не зупинить на цьому шляху!\r\nЦе не просто слова, це не просто ідея,\r\nЦе наш час, і він справді прийшов!\r\n\r\n[pre-chorus]:\r\nВідчуваєш у серці цей битий пульс?\r\nЦе свобода, що рветься крізь час і брухт!\r\n\r\n[chorus]\r\nЗа межу, де світло живе в крові,\r\nМи зламаєм цей морок на самоті!\r\nНаші руки тримають цей світ в долонях,\r\nМи переможем у цій війні!\r\n\r\n[verse 2]:\r\nТам, де тіні минулого рвуться назад,\r\nМи палим мости, щоб не було втрат.\r\nНаші голоси, як грім у ночі,\r\nМи залишим слід, навіть серед дощів.\r\n\r\nКожен із нас — це вогонь і сталь,\r\nНаш вибір простий: вперед або в прірву.\r\nЦей шлях нелегкий, але нас не зламати,\r\nМи вже за межею, і нам не здолати!\r\n\r\n[pre-chorus]\r\nВідчуваєш у серці цей битий пульс?\r\nЦе свобода, що рветься крізь час і брухт!\r\n\r\n[chorus]\r\nЗа межу, де світло живе в крові,\r\nМи зламаєм цей морок на самоті!\r\nНаші руки тримають цей світ в долонях,\r\nМи переможем у цій війні!\r\n\r\n[Bridge]\r\nСтіни падають, грані стираються,\r\nМи творимо світ, де зло розбивається.\r\nНас не зупинить, нас не зламає,\r\nНаш дух летить туди, де світло сягає!\r\n\r\n[final chorus]:\r\nЗа межу, де сонце горить над нами,\r\nДе свобода народжується з вогню!\r\nНаші серця — це зброя, наш крик — це правда,\r\nМи розірвем ланцюги в цьому сну!\r\n\r\n[outro]\r\nВоля жива… Воля в нас…\r\nКожен з нас — це світло, що несе крізь час.','2025-10-25 10:56:52','2025-10-25 10:56:52'),
(9,39,'[verse 1]\r\nТысячелетие во тьме охраняя вечный свет,\r\nЯ хранил священный дар, нерушимый мой завет.\r\nВ глубине древних пещер, где живу я тысячу лет\r\nТам где время не течет, сон окутывал меня.\r\n\r\n\r\nЧувствую как сквозь века, прорастает в сердце страх\r\nЧто-то древнее зовет, пробуждая меня...\r\n\r\n[chorus]\r\nСтраж вечного огня\r\nВосстань от векового сна.\r\nСвященное пламя зовет,\r\nСудьба свой путь ведет.\r\n\r\n[verse 2]\r\nВ глубоком сне я видел этот день\r\nПредсказанье древних книг.\r\nКогда тьма восстанет вновь,\r\nИ раздастся первый крик.\r\nСквозь завесу долгих лет,\r\nСлышу зов священных слов,\r\nПламя вечности дрожит,\r\nРазрывая цепи снов\r\n\r\n\r\nПробуждение несет, весть о битве впереди.\r\nСтраж пробудился что бы защитить...\r\n\r\n[chorus]\r\nСтраж вечного огня\r\nВосстань от векового сна.\r\nСвященное пламя зовет,\r\nСудьба свой путь ведет.\r\n\r\n[bridge]\r\nВ ветрах при сном святом спят под пеленой,\r\nПламя вечности зовет, стража долг его ведет.\r\nСковзь завесу темноты, вижу яркие следы.\r\nЧас пришел и путь открыт...\r\n\r\n[bridge]\r\nВ глубине моей души, память предков говорит.\r\nО священном долге том, что веками был сокрыт.\r\nСила пламени течет, в венах древней крови той,\r\nЧто связала нас тогда стража с вечною судьбой.\r\n\r\nПоднимаюсь ото сна, слыша зов из далека.\r\nВремя битвы настает, пробудилась - тьма!\r\n\r\n[chorus]\r\nСтраж вечного огня\r\nВосстань от векового сна.\r\nСвященное пламя зовет,\r\nСудьба свой путь ведет.','2025-10-27 08:34:40','2025-10-27 08:34:40'),
(10,40,'[Verse 1]\r\nПод знамёнами стали\r\nИдём сквозь огонь и дым.\r\nБратья по оружию встали,\r\nВ строю непобедимым.\r\nКлятву дали друг другу,\r\nНе отступать назад.\r\nПройдёт любую вьюгу,\r\nСтальной наш легион-отряд.\r\n\r\n\r\nЖелезом закалённые сердца,\r\nБьются в унисон.\r\nНет конца у нашего пути...\r\n\r\n[Chorus]\r\nСтальной легион идёт в бой\r\nМечи сверкают в ночи.\r\nНи шагу назад, только вперёд\r\nБратство стальных палачей.\r\nСтальной легион - наша сила,\r\nВ единстве несокрушимом\r\nВраг пощады не просил,\r\nНо мы пощады не дадим!\r\n\r\n[Verse 2]\r\nНа поле брани кровавом,\r\nМы держим общий строй.\r\nПлечом к плечу со славой,\r\nСражаемся как один герой.\r\nПадут враги у наших ног,\r\nРазрушим их ряды.\r\nНас ведёт в бой сам Бог,\r\nКровавой войны!\r\n\r\n[Chorus]\r\nСтальной легион идёт в бой\r\nМечи сверкают в ночи.\r\nНи шагу назад, только вперёд\r\nБратство стальных палачей.\r\nСтальной легион - наша сила,\r\nВ единстве несокрушимом\r\nВраг пощады не просил,\r\nНо мы пощады не дадим!\r\n\r\n[Bridge]\r\nСталь против стали звенит\r\nВ танце смертельных мечей.\r\nКровь с клинков стекает,\r\nМесть за павших друзей!\r\n\r\n[Chorus]\r\nСтальной легион идёт в бой\r\nМечи сверкают в ночи.\r\nСквозь века пронесём\r\nНаши имена навсегда!','2025-10-27 08:35:03','2025-10-27 08:35:03'),
(11,41,'[Verse 1]\r\nВ храме древнем средь камней\r\nПламя тысячу лет горит\r\nФеникс спит в огне своём\r\nВечным сном он был сокрыт\r\nНо приходит время тьмы\r\nДемоны идут войной\r\nЧтобы свет погасить\r\nИ покрыть мир пеленой\r\n\r\n\r\nСлышит зов священный,\r\nПробуждает дух древний\r\nПоднимается из пепла\r\nОгненный крылатый бог!\r\n\r\n[Chorus]\r\nОгненный феникс восстаёт,\r\nКрылья раскинет над храмом огня.\r\nПротив армии тьмы он летит\r\nСвет защищая от зла.\r\nФеникс летит в поднебесье\r\nИскры сыплются под ним.\r\nДревняя магия, вечная песня\r\nМир от забвения он сохранит!\r\n\r\n[Verse 2]\r\nПамять веков в его глазах\r\nТысячи битв позади\r\nЗнает он - его судьба\r\nВсех живых людей защитить.\r\nДемоны рвутся к алтарю\r\nТьма наступает стеной\r\nНо огненная птица встаёт\r\nНа защиту света.\r\n\r\n\r\nЯрче солнца вспыхнет,\r\nСилой древних богов.\r\nРасправляет Феникс крылья\r\nДля последних врагов!\r\n\r\n[Chorus]\r\nОгненный феникс восстаёт,\r\nКрылья раскинет над храмом огня.\r\nПротив армии тьмы он летит\r\nСвет защищая от сил мрака.\r\nФеникс летит в поднебесье\r\nИскры сыплются под ним.\r\nДревняя магия, вечная песня\r\nМир от забвения он сохранит!\r\n\r\n[Bridge]\r\nВ битве огня против тьмы\r\nРешается судьба земли\r\nФеникс знает - он один\r\nКто мир может спасти!\r\n\r\n[Chorus]\r\nОгненный феникс восстаёт\r\nКрылья раскинет над храмом огня\r\nТьма побеждена, мир спасён,\r\nМир защищён от сил мрака.\r\nФеникс вернётся домой\r\nИскры сыплются звёздным дождём\r\nВечная магия, вечный полёт,\r\nХрам и огонь он хранит день за днём!','2025-10-27 08:35:25','2025-10-27 08:35:25'),
(12,42,'[Verse 1]\r\nВ начале времён Эру создал\r\nМелодию мира из звёзд.\r\nВалар спустились в Арду,\r\nЧтобы воплотить замысел грёз.\r\nЭльфы проснулись в Куивиэнен,\r\nГномы родились в недрах гор.\r\nЛюди пришли с востока,\r\nХоббиты в Шире нашли свой простор.\r\n\r\n\r\nОт Валинора до Мордора,\r\nПростираются земли чудес.\r\nГде добро сражается со злом,\r\nИ каждый выбирает свой путь.\r\n\r\n[Chorus]\r\nСредиземье! Земля легенд!\r\nГде живут мечты и сказки\r\nСредиземье! Через тысячи лет\r\nПомним героев и их подвиги.\r\nРивенделл и Лориэн,\r\nРохан и Гондор,\r\nВ сердцах навеки сохранён\r\nволшебный мир, Средиземья!\r\n\r\n[Verse 2]\r\nКольца Власти были скованы,\r\nНо Тёмный Властелин обманул.\r\nОдно Кольцо чтобы править всеми,\r\nВ лаве Роковой Горы создал он.\r\nТысячи лет длились сраженья\r\nСвета с армиями тьмы.\r\nНо маленький хоббит по имени Фродо,\r\nСпас мир от злой судьбы.\r\n\r\n\r\nОт Шира до Роковой Горы,\r\nЛежит путь героев.\r\n\r\n[Chorus]\r\nСредиземье! Земля легенд!\r\nГде живут мечты и сказки\r\nСредиземье! Через тысячи лет\r\nПомним героев и их подвиги.\r\nРивенделл и Лориэн,\r\nРохан и Гондор,\r\nВ сердцах навеки сохранён\r\nволшебный мир, Средиземья!\r\n\r\n[Bridge]\r\nИ пусть корабли уплыли...\r\nЗа море в далёкий край...\r\nМы песни не забыли,\r\nО земле, что сохранили!\r\n\r\n[Chorus]\r\nСредиземье! Земля чудес!\r\nГде каждый камень хранит историю.\r\nСредиземье! Магический лес,\r\nНавсегда останется с нами в памяти.\r\nОт Серых Гаваней до Мордора,\r\nПростираются земли мечты.\r\nПусть закончилась эпоха\r\nНо в песнях живут... Эти герои!','2025-10-27 08:35:47','2025-10-27 08:35:47'),
(13,43,'[Verse 1]\r\nМоя принцесса, час настал прощанья\r\nСудьба зовёт меня в последний бой\r\nХочу сказать слова, что жгут сознанье\r\nПока ещё я рядом здесь с тобой\r\nТы говоришь, что видишь нити рока\r\nЧто знаешь ты о том, что ждёт меня\r\nНо я прошу - не плачь так одиноко\r\nХрани в душе огонь любви и дня\r\n\r\n\r\nНам не дано прожить обычной жизнью\r\nЛюбовь наша прекрасна и больна...\r\n\r\n[Chorus]\r\nПрощай, моя любовь, прощай навеки,\r\nНо в сердце ты останешься со мной.\r\nПусть разлучают нас дороги, реки,\r\nЛюбовь наша сильнее, чем покой.\r\nПрощальный поцелуй на губах тает,\r\nСлеза последняя катится вниз.\r\nДуша моя тебя не забывает,\r\nГде бы ни был - ты мой парадиз.\r\n\r\n[Verse 2]\r\nКлянусь тебе - вернусь, коль буду жив,\r\nНо если смерть настигнет на дороге.\r\nМолю - не плачь, а помни, как красив,\r\nБыл наш с тобою мир в земном чертоге.\r\nТы обещаешь ждать меня, любя,\r\nВстречать рассветы в муках ожиданья.\r\nИ если смерть возьмёт меня, себя\r\nТы проклянёшь за верность и страданья.\r\n\r\n\r\nСудьба жестока к тем, кто любит страстно\r\nНо наша любовь сильней, чем рок...\r\n\r\n[Chorus]\r\nПрощай, моя любовь, прощай навеки\r\nНо в сердце ты останешься со мной\r\nПусть разлучают нас дороги, реки\r\nЛюбовь наша сильнее, чем покой\r\nПрощальный поцелуй на губах тает\r\nСлеза последняя катится вниз\r\nДуша моя тебя не забывает\r\nГде бы ни был - ты мой парадиз\r\n\r\n[Bridge]\r\nИду я в ночь, а ты молишься небу,\r\nХрани, любовь, нас в разлуке!\r\n\r\n[Chorus]\r\nПрощай, моя любовь, но не навеки,\r\nВ другом мире мы встретимся с тобой.\r\nТам нет разлук, дорог и рек,\r\nТам наша любовь обретёт покой.\r\nПоследний поцелуй как благословенье,\r\nПоследний взгляд как обещанье жить.\r\nВ сердцах храним любви мгновенья,\r\nЧто смерть не сможет погубить.','2025-10-27 08:36:11','2025-10-27 08:36:11'),
(14,45,'[Verse 1]\r\nВ башне древней на краю земли,\r\nГде туман скрывает горизонт.\r\nМаг изгнанный коротает дни,\r\nИзучая запретный плод.\r\n\r\nБелый орден отверг его когда-то,\r\nЗа стремление познать запретный путь.\r\nПравда - в знаниях, но не в догматах,\r\nОн решил тогда свой выбрать путь.\r\n\r\nГоды в ссылке, годы в одиночестве\r\nЛишь луна свидетель его дел.\r\nТёмная магия даёт могущество\r\nНо какую цену он заплатить сумел?\r\n\r\n\r\nМежду светом и тьмой\r\nОн идёт своей тропой\r\nОтвергнутый всеми\r\nНо со свободною душой!\r\n\r\n[Chorus]\r\nЧёрный маг - изгнанник мира,\r\nПроклятый орденом и людьми.\r\nЧёрный маг - хранитель знаний,\r\nЧто другие боятся постичь.\r\n\r\nОдин в башне средь книг и свитков,\r\nСила тёмная течёт в его венах.\r\nЧёрный маг живёт без наследия,\r\nТолько истина в его мечтах!\r\n\r\n[Verse 2]\r\nПомнит он те светлые дни,\r\nКогда братом звали в ордене.\r\nНо вопросы жгли его мозги,\r\nО границах, что нельзя переступать.\r\n\r\n\"Почему запретна эта магия?\r\nРазве знание бывает злом?\r\nИли просто древняя иерархия,\r\nСтрах свой не может постичь?\"\r\n\r\nОн посмел спросить у старейшин\r\nНо ответом был лишь приговор\r\n\"Изгнан будешь ты за ересь\r\nБольше не войдёшь ты в этот двор!\"\r\n\r\n\r\nМежду светом и тьмой\r\nОн идёт своей тропой\r\nВ запретных книгах,\r\nОбрёл смысл живой!\r\n\r\n[Chorus]\r\nЧёрный маг - изгнанник мира,\r\nПроклятый орденом и людьми.\r\nЧёрный маг - хранитель знаний,\r\nЧто другие боятся постичь.\r\n\r\nОдин в башне средь книг и свитков,\r\nСила тёмная течёт в его венах.\r\nЧёрный маг живёт без наследия,\r\nТолько истина в его мечтах!\r\n\r\n[Verse 3]\r\nЗеркало показывает правду\r\nОтражение пугает иногда\r\nЧто он видит? Мудреца? Иль падшего?\r\nГрань стирается день ото дня.\r\n\r\nСила есть, но нет того света,\r\nЧто когда-то светило ему.\r\nОдиночество это - его судьба,\r\nЧто бы мог, мыслить и творить.\r\n\r\n[Pre-Chorus]\r\nМежду светом и тьмой\r\nРастворился образ мой\r\nНи добро, ни зло\r\nТолько знание со мной\r\nЦена свободы высока\r\nОдиночество - мой дом\r\nНо душа ещё жива\r\nПока бьётся сердце в нём!\r\n\r\n[Chorus]\r\nЧёрный маг - повелитель знаний,\r\nНе герой и не злодей.\r\nЧёрный маг - хранитель тайны,\r\nЧто пугает всех людей.\r\nБашня - вечный дом скитальца,\r\nТьма и свет в его глазах.\r\nЧёрный маг продолжит сражаться,\r\nЗа свободу в своих светлых снах!','2025-10-27 08:36:33','2025-10-27 08:36:33'),
(15,46,'[Verse 1]\r\nПринцем был я Лордерона,\r\nСветлым паладином рождён.\r\nЗащищал народ и корону,\r\nВерил в правду и закон.\r\nНо пришла чума и скверна,\r\nДемон Мал\'Ганис сжигал.\r\nПоклялся я отомстить непременно,\r\nВ Нортренд путь свой держал.\r\n\r\n\r\nМесть сжигала моё сердце\r\nОслепила светлый путь...\r\n\r\n[Chorus]\r\nЛедяной трон зовёт меня,\r\nФростморн в руках моих.\r\nДуша продана за силу огня\r\nТьма поглотила мой последний крик.\r\nКорона льда ждёт меня,\r\nЛедяной трон - судьба моя.\r\nНет дороги в прошлые дни\r\nЯ потерян во тьме!\r\n\r\n[Verse 2]\r\nПроклятый меч взял я в ладонь,\r\nВласть и силу приобрёл.\r\nНо забрал он душу и боль\r\nИ свет навеки потерял.\r\nВернулся домой героем мнимым\r\nОтца предал, корону забрал.\r\nСтал я монстром нелюдимым,\r\nВсё что любил - сам же и разрушал.\r\n\r\n\r\nКороль-лич звал меня на север\r\nК трону ледяному во тьме присягнуть...\r\n\r\n[Chorus]\r\nЛедяной трон зовёт меня,\r\nФростморн в руках моих.\r\nДуша продана за силу огня\r\nТьма поглотила мой последний крик.\r\nКорона льда ждёт меня,\r\nЛедяной трон - судьба моя.\r\nНет дороги в прошлые дни\r\nЯ потерян во тьме!\r\n\r\n[Verse 3]\r\nВсё потерял я на пути\r\nСилу обрёл взамен\r\nНа троне буду вечно гнить,\r\nВ царстве холода и тьмы...\r\n\r\n[Chorus]\r\n(Ледяной трон...)\r\nЛедяной трон - вечность моя\r\nВ царстве мёрзлых сердец.\r\nЯ владыка, я смерть, я тьма\r\nАрмию нежити веду\r\n(Ледяной трон) хранит во льдах\r\nТайну падшего героя\r\nНет пути назад в светлых днях\r\nТолько холод, тьма и горе!\r\n\r\n[Outro]\r\n(Ледяной трон...)\r\nвечность моя...\r\n(Ледяной трон...)\r\nТолько холод, тьма и горе!','2025-10-27 08:36:57','2025-10-27 08:36:57'),
(16,47,'[Verse 1]\r\nПомню день, что сердце рвёт,\r\nКогда клинок твой путь пресёк.\r\nТы грудью встала за меня,\r\nМою судьбу приняв в себя.\r\n\r\nТы упала в мои объятья,\r\nКровь текла в лучах заката.\r\nТы шептала: «Всё пройдет»,\r\nНо свет в глазах твоих угас, в тот же час...\r\n\r\n\r\nЯ дышу, а ты ушла,\r\nГде же правда, где судьба?\r\n\r\n[Chorus]\r\nЦена любви — твой светлый лик,\r\nЗа грех мой жизнь твоя горит.\r\nВ каждом вздохе, в каждом дне\r\nТы будешь вечно жить во мне.\r\nНе прощу я сам себя (себя),\r\nЗа ту беду, что к нам пришла.\r\nТы любила, меня храня,\r\nА я в аду живу, скорбя.\r\n\r\n(Ты любила, меня храня)\r\nА я в аду живу, скорбя...\r\n\r\n[Verse 2]\r\nКаждой ночью в тишине\r\nТы приходишь в ясных снах ко мне.\r\nТвоя улыбка — солнца свет,\r\nКак в те года, где боли нет.\r\nТы шепчешь тихо: «Не грусти» (не грусти),\r\nГоворишь мне: «Меня найди» (найди).\r\nНо с рассветом, открыв глаза,\r\nЯ теряю тебя навсегда.\r\n\r\n\r\nЯ живу, а ты ушла,\r\nСправедливость где она?\r\n\r\n[Chorus]\r\nЦена любви — твой светлый лик,\r\nЗа грех мой жизнь твоя горит.\r\nВ каждом вздохе, в каждом дне\r\nТы будешь вечно жить во мне.\r\nНе прощу я сам себя (себя),\r\nЗа ту беду, что к нам пришла.\r\nТы любила, меня храня,\r\nА я в аду живу, скорбя.\r\n\r\n(Ты любила, меня храня)\r\nА я в аду живу, скорбя...\r\n\r\n[Bridge]\r\nЕсли б мог я время вернуть,\r\nЯ б тебе даровал свой путь.\r\nПусть бы смерть забрала меня,\r\nЧтоб ты жила, любовь храня.\r\n\r\n[Chorus]\r\nЦена любви — так высока,\r\nТы заплатила за меня сполна.\r\nПроклинаю я эти дни,\r\nГде нас судьба развела.\r\nБуду помнить я до конца (до конца),\r\nКак спасла ты меня тогда (тогда).\r\nТвоя любовь сильнее смерти,\r\nА боль моя не утихает.\r\n\r\n(Твоя любовь — бессмертный свет)\r\nМоя же боль меня... сжигает...','2025-10-27 08:37:21','2025-10-27 08:37:21'),
(17,48,'[Verse 1]\r\nВ далёких горах, где туман\r\nСкрывает древние руины\r\nСпит последний великан\r\nДракон забытой долины.\r\nВека прошли с тех пор\r\nКогда он правил небесами.\r\nТеперь лишь старый фольклор\r\nХранит память о деяньях.\r\n\r\nНо пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Verse 2]\r\nРыцари давно забыли,\r\nКак сражаться с ним.\r\nМечи от ржи затупились,\r\nВ век новых технологий.\r\nНо есть среди людей один,\r\nКто верит в старые сказанья.\r\nОн знает - мифов властелин\r\nВернётся для воздаянья.\r\n\r\nВедь пламя в сердце не погасло,\r\nХоть мир забыл о чудесах.\r\nПришло время сбросить маски,\r\nИ вновь расправить два крыла!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстанет из пепла.\r\nПройдёт сквозь все миры\r\nЛюдских забытых снов.\r\nОгненным дыханием,\r\nНебо озарит.\r\nИ древним заклинанием\r\nМир преобразит!\r\n\r\n[Bridge]\r\nПроснись, великий дракон!\r\nТвой час настал опять\r\nРазрушь обмана закон\r\nИ магию верни назад!\r\n\r\n[Chorus]\r\nПоследний из драконов\r\nВосстал из глубины.\r\nСломал людские троны\r\nИ рушит их умы.\r\nЗолотым сияньем\r\nМир теперь объят.\r\nНовым мирозданьем\r\nПравит древний ритуал!','2025-10-27 08:37:41','2025-10-27 08:37:41'),
(18,50,'[verse 1]\r\nНа Патриарших тишина,\r\nНо шёпот в воздухе застыл.\r\nСквозь зеркала ночного сна\r\nШаги ведут меня на туда.\r\n\r\nТам Коровьёв смеётся в дыму,\r\nИ Бегемот танцует свой канкан.\r\nВ их глазах — огонь, в словах — обман,\r\nНо мессир зовёт меня за грань.\r\n\r\n[chorus]\r\nШаги на воде, тени в огне,\r\nМессир идёт, не оставляя следа.\r\nЗдесь время застыло, как ледяной круг,\r\nВоланд правит бал — мой приговор и друг.\r\n\r\n[verse 2]\r\nАзазелло молчит, но его взгляд пронзает сталь,\r\nИ Гелла смеётся, как ночной кошмар.\r\nВ их свете кружатся огни фонарей,\r\nОни шепчут мне: \"Доверься своей судьбе.\"\r\n\r\nМир расколот, за гранью — страх,\r\nНо мессир смотрит сквозь века.\r\nОн знает, где правда, где мой долг,\r\nИ в бездне горит его огненный шёлк.\r\n\r\n[chorus]\r\nШаги на воде, тени в огне,\r\nМессир идёт, не оставляя следа.\r\nЗдесь время застыло, как ледяной круг,\r\nВоланд правит бал — мой приговор и друг.\r\n\r\n[bridge]\r\nТы чувствуешь дрожь в небесах?\r\nЭто зов тех, кто пал во мраке.\r\nНити судеб сплела их кровь,\r\nНо мессир всё решит вновь.\r\n\r\nСвет свечей режет мрак насквозь,\r\nТени пляшут, сжигая мосты.\r\nКаждый шаг под звуки струны —\r\nТы в плену у Князя Тьмы!\r\n\r\n[final chors.]:\r\nНа воде растаял след,\r\nМир склонился перед тьмой.\r\nМессир ушёл, но его бал\r\nВечным эхом звучит за тобой.\r\n\r\nЭтот танец в круге веков\r\nСквозь эпохи ведёт за собой.\r\nКто вошёл под свет тех свечей,\r\nСтал тенью в руках Князя теней.','2025-10-27 08:51:59','2025-10-27 08:51:59'),
(19,51,'[verse 1]\r\nТени проступают, ночь зовёт в игру,\r\nСвет дрожит в руках, обнажая тьму.\r\nГаснут зеркала, пряча лица снов,\r\nИ в тиши шаги — шёпот чужих богов.\r\n\r\nСобраны гости в залах пустоты,\r\nСотни лиц без глаз, души без мечты.\r\nЗвуки скрипок, крик, пробуждают страх,\r\nВ черном танце мир превращается в прах.\r\n\r\n[chorus]\r\nБал Вечной Тьмы, нет пути назад,\r\nЗдесь каждый шаг превращается в ад.\r\nТанец в огне, в круге пустоты,\r\nТвоя душа — дар для Князя Тьмы.\r\n\r\n[verse 2]\r\nВзгляд сквозь вечный мрак, стены молчат,\r\nКаждый вздох здесь — клятва, каждый шаг — раскат.\r\nПламя в небесах, словно меч богов,\r\nНо в его огне — шёпот чужих грехов\r\n\r\nМаски смотрят вниз, пряча смех и боль,\r\nЗдесь, за гранью снов, правит новый король.\r\nКружит вихрь теней, музыка слышна,\r\nНо тьма не прощает, ты — её слуга.\r\n\r\n[chorus]\r\nБал Вечной Тьмы, нет пути назад,\r\nЗдесь каждый шаг превращается в ад.\r\nТанец в огне, в круге пустоты,\r\nТвоя душа — дар для Князя Тьмы.\r\n\r\n[bridge]\r\nТень... свет... время сломалось вдруг.\r\nГрех... суд... кольцом замкнулся круг.\r\nХолод, ярость, ревущий звон мечей,\r\nМрак, прощение, но не для людей.\r\n\r\nПыль... прах... лики сгорают в миг.\r\nВзгляд... страх... душу влечет тупик.\r\nШепчут стены: \"Сбежать не дано\".\r\nСлишком поздно — ты здесь... для него.\r\n\r\n[outro]\r\nБал Князя Тьмы, нет спасения тут,\r\nКаждый миг — это вечности суд.\r\nТанец в огне, где правит мессир,\r\nТы пленён его тенью до края могил.\r\n\r\n[final]\r\nПепел на полу, звёзды сгорели в миг,\r\nТень мессира скользит.\r\nИ в его глазах мир дрожит в огне,\r\nКнязь Тьмы унесёт все в своей тишине.\r\n\r\nШаги растворятся, свита вновь уйдёт,\r\nНо огонь на руинах свою песнь споёт.\r\nЭтот бал — лишь знак, тьма не знает сна,\r\nЕё власть бесконечна, её правда одна!','2025-10-27 08:52:26','2025-10-27 08:52:26'),
(20,52,'[verse 1]:\r\nВ ночи, где звёзды падают во мгле,\r\nМой разум тонет в вечной пустоте.\r\nСлова – как пепел, не горит огонь,\r\nИ я молю: \"Кто подарит мне вечный покой?\"\r\n\r\n[chorus]:\r\nШёпот во тьме звучит, как крик,\r\nСилой своей ломает гранит.\r\n\"Возьми то, что хочешь, возьми навсегда,\r\nНо плати за мечты, плати без конца.\"\r\n\r\n[verse 2]:\r\nГлаза мои – разбитое стекло,\r\nМир, что я знал, в прах давно ушёл.\r\nМой голос слаб, но тьма зовёт вперёд,\r\nИ я шепчу: \"О, ночь, меня веди... подари мне покой\"\r\n\r\n[chorus]\r\nШёпот во тьме звучит, как крик,\r\nСилой своей ломает гранит.\r\n\"Возьми то, что хочешь, возьми навсегда,\r\nНо плати за мечты, плати без конца.\"\r\n\r\n[verse 3]:\r\nТьма сгущается вокруг меня,\r\nГолос внутри – мой вечный враг.\r\n\"Что ищешь ты? Любовь или власть?\r\nНо правды нет – лишь мрак, лишь страсть.\"\r\n\r\n[chorus]\r\nШёпот во тьме звучит, как крик,\r\nСилой своей ломает гранит.\r\n\"Возьми то, что хочешь, возьми навсегда,\r\nНо плати за мечты, плати без конца.\"\r\n\r\n[bridge]:\r\nШёпот затих, оставив след,\r\nТьма подарила обманчивый свет.\r\nВидишь ты всё, что хотел обрести,\r\nНо с каждым шагом теряешь след своего пути.\r\n\r\n[outro]:\r\nИ там, где ночь прячет ответ,\r\nВ душе звучит забытый свет.\r\nНо знаю я: мой выбор сделан,\r\nИ тьма зовёт в последний раз…','2025-10-27 08:52:51','2025-10-27 08:52:51'),
(21,53,'[verse 1]:\r\nСквозь мрак веков я вижу свет,\r\nЕё глаза, где страха нет.\r\nОна – как ангел, дарит тёплый свет,\r\nНо ад внутри шепчет мне: \"Ответа нет.\"\r\n\r\n[chorus]:\r\nСияние в бездне, свет среди теней,\r\nНо грех мой тянет за собой людей.\r\nЛюбовь и боль танцуют в унисон,\r\nВ душе звучит небес и ада звон.\r\n\r\n[verse 2]:\r\nСреди теней я вижу твой свет,\r\nНо он тает в закатах прожитых лет.\r\nТы мой покой, ты мой грех,\r\nНо рядом с тобой мир уходит во тьму, бесследных лет.\r\n\r\n[chorus]:\r\nСияние в бездне, свет среди теней,\r\nНо грех мой тянет за собой людей.\r\nЛюбовь и боль танцуют в унисон,\r\nВ душе звучит небес и ада звон.\r\n\r\n[bridge]:\r\nВстаю на край бездны – я вижу лишь тьму,\r\nНо свет её глаз зовёт меня в мою мечту.\r\n\"О, не веди меня в свой грех,\r\nЯ в плену любви и вечных, надежд.\"\r\n\r\n[choir]:\r\n\"Любовь или тьма – судьба не щадит,\r\nСвет обжигает, мрак победит!\"\r\n\r\n[verse 3]:\r\n\"Любовь сильна, но судьбы рукой\r\nМы разделены, как лёд и огонь.\"\r\n\"Прости меня, я только тень,\r\nНо я сожгу весь мир ради твоих же дней!\"\r\n\r\n[chorus]:\r\nСияние в бездне, свет среди теней,\r\nНо грех мой тянет за собой людей.\r\nЛюбовь и боль танцуют в унисон,\r\nВ душе звучит небес и ада звон.\r\n\r\n[final]:\r\n\"Я обречён, я знаю теперь,\r\nСвет, что сиял, растаял, как тень.\r\nТы мой свет, ты мой сон,\r\nНо мы встретимся там, где мрак унесёт нас вдаль.\"\r\n\r\n\"Ты мой свет, но огонь во тьме.\r\nЛюбовь моя в небесах зажжётся,\r\nНо где конец?\"','2025-10-27 08:53:58','2025-10-27 08:53:58'),
(22,54,'[куплет 1]:\r\nЯ вижу пропасть, нет пути назад,\r\nГрехи мои звучат, как вечный ад.\r\nВ объятиях ночи – моя судьба,\r\nНо свет внутри зовёт: \"Бороться пора!\"\r\n\r\nМоя душа – как рвущийся клинок,\r\nМеж светом и тьмой я сделаю шаг.\r\nО, голос тьмы, твой зов звучит,\r\nНо свет борьбы в сердце горит!\r\n\r\n[припев]:\r\nПоследний рассвет, взойдёт надо мной,\r\nСвет и тьма схлестнулись в смертный бой.\r\nЯ выбираю свой путь вперёд,\r\nВечный свет меня спасёт, и он вернёт домой!\r\n\r\n[Хор]:\r\n(Бори-и-ись! Взойди-и-и свет!)\r\n\r\n[куплет 2]:\r\nТы думаешь, свет твой спасёт тебя?\r\nЛишь иллюзия – вера, пустая мечта!\r\nЯ твой хозяин, ты дал мне клятву,\r\nТы мой навеки, в моей ты власти!\r\n\r\n[бридж]:\r\n\"Нет, тьма! Ты не возьмёшь меня!\r\nВо мне горит свет любви, он спасёт от тебя.\"\r\n\r\n\"Глупец! Тебе не спастись,\r\nВ бездне веков ты просто пыль!\"\r\n\r\n[хор] (\"Тьма победит! Свет умрёт!\")\r\n\r\n[куплет 3]:\r\nСквозь мрак веков я вижу свет,\r\nСвет в душе – мой мой ответ!\r\nПусть цепи грехов держат меня,\r\nНо я восстану из этого зла!\r\n\r\nСвет сильней, чем вся эта тьма,\r\nВера горит, как звезда.\r\nЯ выбираю свободу свою,\r\nАд позади, я стою на краю, в новом раю!\r\n\r\n[финальный припев]\r\nПоследний рассвет взойдёт надо мной,\r\nСвет и тьма схлестнулись в смертный бой.\r\nЯ победил, мой путь завершён,\r\nСвет в душе моё имя вернёт!\r\n\r\n[хор]:\r\n(\"Вечный свет! Победа в тебе!\")','2025-10-27 08:54:32','2025-10-27 08:54:32'),
(23,55,'[verse 1]:\r\nНочь безмолвна, ветер стух,\r\nШепчет тайны древний дух.\r\nТьма струится за порог,\r\nСловно тени вечных строк.\r\nШаг за шагом мир дрожит,\r\nСердце в страхе замолчит.\r\nКамень вечности застыл,\r\nГолос шепчет — «Будь терпим».\r\n\r\n[chorus]:\r\n«Nevermore» — звучит, как стон,\r\nВорон чёрный сел на трон.\r\nКрылья тьмы сомкнули свет,\r\nЗдесь спасения больше нет.\r\n\r\n[verse 2]:\r\nСквозь туманы вижу я,\r\nВзгляд пустой, как ночь моя.\r\nГрусть навеки — мой венец,\r\nКаждый шаг — преддверье склеп.\r\nЭхо слов зовет ввысь,\r\nНо под ним лишь бездны нить.\r\nЧто за шутка? Мир погас,\r\nИ мой дух застыл на век сейчас.\r\n\r\n[chorus]:\r\n«Nevermore» — звенит, как крик,\r\nВорон вечности велик.\r\nТень укажет новый путь,\r\nГде найду я вечный суд.\r\n\r\n[bridge]:\r\nНа обломках света встану,\r\nСлышу карканье тумана.\r\nЭтот стон — мне знак извне,\r\nТьма не сдастся в пустоте.\r\nВорон, ворон, страж бессмертный,\r\nТвой полет мой путь последний.\r\nСвета крохи — лишь мираж,\r\nПризрак ночи — твой шантаж.\r\n\r\n[verse 3]:\r\nКрылья света, крылья тьмы,\r\nТени стали вечной тьмы.\r\nЯ теперь не человек,\r\nМне не нужен больше век.\r\nТы пророк или обман?\r\nГде мой путь и где мой храм?\r\nШепот в сердце — боль, ответ,\r\nЗдесь спасения больше нет.\r\n\r\n[chorus]:\r\n«Nevermore» — судьбы финал,\r\nТьма узор свой начертал.\r\nЖизнь под крыльями мертва,\r\nВорон шепчет — «навсегда».\r\n\r\n[outro]:\r\nВорон, ворон, я — лишь тень,\r\nТвой полет закроет день.\r\n«Nevermore» — как сердца стук,\r\nЭтот мир — лишь чёрный круг.','2025-10-27 08:54:56','2025-10-27 08:54:56'),
(24,56,'[verse 1]:\r\nТам, у шепчущих волн скал,\r\nГде вечно дыханье морей,\r\nМоя Аннабель, светлый идеал,\r\nСпала в мире ночей.\r\nНас ангелы знали, завидуя нам,\r\nЛюбви, что сильнее мира.\r\nНо звезды затмились в небесных слезах,\r\nИ тьма отняла у меня тебя мило.\r\n\r\n[chorus]:\r\nАннабель Ли, мой вечный свет,\r\nМоя душа тебя хранит.\r\nВ холоде звезд твой образ живет,\r\nМоё сердце тобою пылает.\r\n\r\n[verse 2]:\r\nНа крыльях ветров унесли тебя,\r\nНо душа осталась со мной.\r\nКак тени ночные следят за судьбой,\r\nТак я вечно следую за тобой.\r\nИ ангелы шепчут, и море рыдает,\r\nНо ты, Аннабель, со мной.\r\nВ каждом закате твоё лик обитает\r\nМоё счастье в мире ином.\r\n\r\n[chorus]:\r\nАннабель Ли, в безмолвных снах\r\nЯ слышу твой нежный смех.\r\nВ вечных волнах, в ветрах, в облаках\r\nТы зовешь меня на новый рассвет.\r\n\r\n\r\n[bridge]:\r\nСквозь горы, сквозь тени, сквозь мрак миров,\r\nЯ вечно ищу твой взгляд.\r\nМоё сердце горит, как пламя веков,\r\nИ любовь моя не уйдёт назад.\r\nВ холоде могил я слышу твой зов,\r\nТы живая в каждом шаге.\r\n\r\n[verse 3]:\r\nНа берегах, где клятвы звучали,\r\nМы создали наш Эдем.\r\nТеперь лишь звезды всё те же сияли,\r\nНо ночь помнит всё о тебе.\r\nМой путь завершится у края морей,\r\nГде встречу я вновь тебя.\r\nТы мой свет, Аннабель, в судьбе моей,\r\nИ нет для нас слова «прощай».\r\n\r\n[chorus]:\r\nАннабель Ли, я найду покой\r\nЛишь там, где твой нежный лик.\r\nВетры, унесите меня с собой,\r\nВ мир, где нет боли и страха.\r\n\r\n[outro]:\r\nАннабель Ли, звезда моя,\r\nТы вечный свет в судьбе.\r\nНи ангелы, ни смерть, ни время, ни тьма\r\nНе заберут тебя от меня.','2025-10-27 08:55:16','2025-10-27 08:55:16'),
(25,57,'\"Кто дал мне жизнь? Кто обрёк меня на тьму?\"\r\n\r\n[verse 1]\r\nЯ смотрю на свет, но он режет мне грудь,\r\nЯ кричу в пустоту — зачем меня ты создал?\r\nТы вдохнул в меня жизнь, подарил мне путь,\r\nНо оставил на дне своих страхов.\r\n\r\nЯ искал тепло в холодных руках,\r\nНо их взгляд разбивает мою мечту.\r\nТы бежал от меня, ты отверг свой суть,\r\nНо я — твой облик в тёмном аду.\r\n\r\n[chorus]\r\nМой создатель, зачем дал мне жизнь?\r\nМой мир погас в бессмертной тени.\r\nТы создал меня, но проклял навек,\r\nЯ лишь тень, я лишь боль, я твой грех.\r\n\r\n[verse 2]\r\nКаждый шаг в этом мире — как удар ножа,\r\nКаждый взгляд — это пламя, что жжёт до костей.\r\nЯ искал смысл там, где живёт душа,\r\nНо нашёл лишь отраженье твоих страхов.\r\n\r\nТы молил о силе, о знании богов,\r\nНо теперь твоя жизнь — лишь шёпот грехов.\r\nТы создал меня, чтобы стать выше всех,\r\nНо теперь я твой страх, я твой смертный грех.\r\n\r\n[chorus]\r\nМой создатель, зачем дал мне жизнь?\r\nМой мир погас в бессмертной тени.\r\nТы создал меня, но проклял навек,\r\nЯ лишь тень, я лишь боль, я твой грех.\r\n\r\n[bridge]\r\nТы хотел быть богом, но стал человеком,\r\nТы искал славу, но нашёл вечный ад.\r\nЯ — твой выбор, я — твоя ошибка,\r\nНо зачем мне жить, если всё — обман?\r\n\r\n[verse 3]\r\nЯ ухожу в снег, где тишина без конца,\r\nПусть холод заберёт моё сердце и боль.\r\nТы остался один, мой создатель и враг,\r\nНо я твой вечный крест, я твой страшный путь.\r\n\r\n[chorus]\r\nМой создатель, зачем дал мне жизнь?\r\nМой мир погас в бессмертной тени.\r\nТы создал меня, но проклял навек,\r\nЯ лишь тень, я лишь боль, я твой грех.\r\n\r\n(\"Ты — мой грех, ты — мой страх...\")\r\n\r\n[outro]\r\n\"Ты создал меня, но исчез сам...\"','2025-10-27 08:55:42','2025-10-27 08:55:42'),
(26,58,'[verse 1]\r\nНа границе тьмы и света я стоял,\r\nВ жажде знаний мой разум пылал.\r\nСквозь пыль веков звучал его зов,\r\nМефистофель протянул мне свой кров.\r\n\r\nТы мечтал быть выше богов,\r\nНо за силу платишь кровью грехов.\r\nТы дал клятву — разрушить предел,\r\nНо душа твоя — его трофей и цель.\r\n\r\n[chrous]\r\nПечать Мефистофеля горит на руках,\r\nТы связал свой путь с его тенями в вечных снах.\r\nКрылья свободы сгорели в огне,\r\nТеперь ты — пленник в дьявольской игре.\r\n\r\n[verse 2]\r\nКаждый шаг в его мире — как шаг за грань,\r\nЕго обещания — яд, его слово — петля.\r\nТы искал истину в его словах,\r\nНо нашёл лишь бездну в кровавых слезах.\r\n\r\nВкус власти стал горечью на губах,\r\nТы создал свой ад, пытаясь достичь небес.\r\nМефистофель смеётся: \"Ты всего лишь пешка!\"\r\nНо ты в его руках — инструмент его мести.\r\n\r\n[chorus]\r\nПечать Мефистофеля горит на руках,\r\nТы связал свой путь с его тенями в веках.\r\nКрылья свободы сгорели в огне,\r\nТеперь ты — пленник в дьявольской игре.\r\n\r\nИскушение — твой вечный крест,\r\nТы больше не человек, ты стал его месть.\r\n\r\n[bridge]\r\nТы познал всё, но потерял себя,\r\nЗнание стало клинком, что пронзает тебя.\r\nТвоё имя исчезнет в потоках времён,\r\nА его тень будет вечным сном.\r\n\r\n[verse 3]\r\nНа пороге вечности, в последний миг,\r\nТы просишь прощения, но его нет.\r\nМефистофель смеётся, сжимая тебя:\r\n\"Ты сам выбрал этот путь, ты мой раб навсегда!\"\r\n\r\n[chorus]\r\nПечать Мефистофеля горит на руках,\r\nТы связал свой путь с его тенями в веках.\r\nКрылья свободы сгорели в огне,\r\nТеперь ты — пленник в дьявольской игре.\r\n\r\nНо истина одна — цена высока:\r\nТы стал богом, но остался в царстве сна.\r\n\r\n[outro]\r\n(\"Ты хотел всё... Но остался ни с чем...\")','2025-10-27 08:56:04','2025-10-27 08:56:04'),
(27,59,'[verse 1]\r\nМой дом — тени зеркальных стен,\r\nГде звёзды гаснут в глазах людей.\r\nЯ — тот, кто шёпотом пронзает тьму,\r\nМой голос звучит в этом вечном плену.\r\n\r\nЯ творю из боли, я пою в ночи,\r\nМоё сердце горит, но скрыто в тени.\r\nЕё свет зовёт, но отводит взгляд,\r\nОна — мой рай, она — мой ад.\r\n\r\n[chorus]\r\nМаска и тень — мой вечный путь,\r\nЯ пел о любви, но не смог её коснуться.\r\nМузыка звучит, но ранит меня,\r\nПризрак оперы — моя судьба.\r\n\r\n[verse 2]\r\nЯ смотрю на неё, как на свет звезды,\r\nНо мой облик в зеркале — это лишь грехи.\r\nЯ построил ей храм из звуков и снов,\r\nНо в моих руках только боль и кровь.\r\n\r\nКаждый звук её голоса — как кинжал,\r\nКаждый взгляд её — мой последний обман.\r\nОна мой смысл, но я её страх,\r\nЯ заберу её... хоть в вечный мрак.\r\n\r\n[chorus]\r\nМаска и тень — мой вечный путь,\r\nЯ пел о любви, но не смог её коснуться.\r\nМузыка звучит, но ранит меня,\r\nПризрак оперы — моя судьба.\r\n\r\n[bridge]\r\nТы слышишь мой голос, он зовёт во тьму,\r\nНо я лишь тень, что живёт в плену.\r\nМоя музыка вечна, как этот зал,\r\nНо в твоём сердце я стал лишь враг.\r\n\r\n[final verse]\r\nЯ уйду в ночь, где нет ни звука, ни звёзд,\r\nМоя песня станет эхом грёз.\r\nНо когда свечи погаснут в зале теней,\r\nТы вспомнишь мой голос, что звучал сильней.\r\n\r\n[chorus]\r\nМаска и тень — мой вечный путь,\r\nЯ пел о любви, но не смог её коснуться.\r\nМузыка звучит, но ранит меня,\r\nПризрак оперы — моя судьба.\r\n\r\n[choir] (\"Моя судьба... Вечная тьма...\")\r\n\r\n[outro]\r\n(\"Я лишь тень... Но музыка будет жить вечно...\")\r\n(\"Я лишь тень... Но музыка будет жить вечно...\")','2025-10-27 08:56:32','2025-10-27 08:56:32'),
(28,61,'[Verse 1]\r\nВ старом доме, где тени живут,\r\nСкрипит чердак — и время идёт назад.\r\nТам скелет в кожанке лежит,\r\nИ ржавой струной мрак сторожит.\r\n\r\n(ТАМ — ГДЕ — НОЧЬ!)\r\n(ТАМ — ГДЕ — ГРОБ!)\r\n\r\nОн был кумиром в проклятом дворе,\r\nГремел костями на костре.\r\nТеперь — он тишина, но только на вид,\r\nКогда он поёт — весь мир дрожит!.\r\n\r\nПрипев:\r\nСкелет под чердаком — играет рок,\r\nГвоздями по струнам, швыряет ток!\r\nОн ждёт тебя, он слышит шаг…\r\nИ если ты войдёшь — то не выйдешь никак.\r\n\r\n[Verse 2]\r\nОн был безбашен, бунтарь с гвоздём,\r\nШил себе песни иглой по венам.\r\nНо рок-н-ролл в его крови,\r\nТеперь играет он — в тени.\r\n\r\n(Сыграй со мной…\r\nна вечном концерте мертвецов…)\r\n\r\n[Chorus 2]\r\nСкелет под чердаком — твой новый друг,\r\nНа гитаре — кости, но звук не стих.\r\nОн вспомнит твой голос, припомнит след…\r\nИ сделает тебя — одной из легенд.\r\n\r\nПрипев\r\nСкелет под чердаком — вечный панк!\r\nОн играет, пока свет — не погас!\r\nИ если ты слышал аккорд —\r\nТы уже там… под его чердаком, где вечный бэдлам!\r\n\r\nИ скрипнет пол… и затихнет звук…\r\nТы стал аккордом… в вечном танце мертвецов.','2025-10-27 08:57:12','2025-10-27 08:57:12'),
(29,62,'Verse 1\r\nЕго хоронили дважды — в бетон закатали,\r\nНо каждую ночь — его воскрешают.\r\nОн лежит, но не тихо — гроб дрожит,\r\nПока над ним кто-то снова слова кричит.\r\n\r\nНе забыт! Не прощен!\r\nНЕ ЗАБЫТ! НЕ ПРОЩЕН!\r\nОн сам себе снова плакат рисует.\r\nГвоздь в табличке — это не крест,\r\nЭто флаг — и он взмывает до небес.\r\n\r\nПрипев:\r\nМёртвый революционер!\r\nОн не сдался, он ушёл под марш!\r\nМёртвый революционер!\r\nДаже смерть — его не победит!\r\nОн в цепях — из кожи и жил,\r\nНо рвёт, тех кто остался в живых.\r\n\r\n(Вечная агитация...)\r\n\r\n[Verse 2]\r\nНа его черепе — наклейки “не трогать”,\r\nА в руках — листовки для новых героев.\r\nКто на него плюёт — находит на спине\r\nЦитаты, что не стираются нигде.\r\n\r\nСобраны в склепе, лозунги в рифму,\r\nВ черепе поёт — но только мотив.\r\nДаже черви — и те маршируют,\r\nИ не спрятаться от него, уже ни где.\r\n\r\nПрипев:\r\nМёртвый революционер!\r\nОн восстанет — если ты боишься.\r\nМёртвый революционер!\r\nПохоронен — но не забыт.\r\nНо в тебе течет его кровь,\r\nИ живёт в нем - вечный огонь.\r\n\r\nБридж:\r\nОн не верит в рай.\r\nОн не верит во власть.\r\nОн просто идёт.\r\nПо головам — к себе.\r\n\r\nПрипев:\r\nМёртвый революционер!\r\nСкелет, но с сердцем в кулаке!\r\nМёртвый революционер!\r\nЭто анархист, шагающий к тебе на встречу.\r\nОн в тебе живёт, и снова зовёт,\r\nМёртвый анархист-революционер,\r\nТак не дай ему зря умереть.\r\n\r\nАутро:\r\n(Он уже устал...\r\nНо слышен его крик...)\r\nХОЙ! ХОЙ! ХОЙ!','2025-10-27 08:57:36','2025-10-27 08:57:36'),
(30,63,'[Verse 1]\r\nНа краю старого пирса — стоит заброшенный зал,\r\nТам скрипка играет, но не видно зеркал.\r\nКостюмы из плесени, маски из тени,\r\nБал начинается… с твоей же смены.\r\n\r\nВходишь — не спрашивают имя и суть,\r\nТы уже здесь, а назад — не повернуть.\r\nСтавят бокал — и он пахнет землёй…\r\nНо ты улыбаешься. Ну что ж — ты уже свой.\r\n\r\nПрипев:\r\nГрим под гробовой свет,\r\nТы танцуешь — но следа больше нет.\r\nНа полу — не тени, а следы ногтей…\r\nБал для тех, кто забыл, что хотел.\r\n\r\n[Verse 2]\r\nТвоя пара — скелет в шёлке из снов,\r\nСмеётся без рта, но знает твой зов.\r\nТы шепчешь «я жив?» — а в ответ: «ещё нет!»\r\nИ танцуешь с ней…\r\n...свой последний менуэ́т.\r\n\r\nБридж:\r\nСначала один шаг — потом весь зал.\r\nОни знают где, душу продал.\r\nТы хотел быть звездой?\r\nТак вот — твой свет,\r\nНо гаснешь — под гробовой свет.\r\n\r\n\r\nПрипев:\r\nГрим под гробовой свет,\r\nТы сияешь… но только в этой тьме.\r\nТы — пустота, но в ней барабанит ночь,\r\nИ в костях твоих марширует мощь.\r\n\r\nАутро:\r\n(Пока играет музыка…\r\nты — один из нас…)\r\n(но как стихнет... Пробьёт твой час)','2025-10-27 08:57:57','2025-10-27 08:57:57'),
(31,64,'[Verse 1]\r\nЯ рубил без счёта, без вины и цены,\r\nМеч мой — судья, без любви и страха.\r\nВсё, что просили — я исполнял,\r\nИ ни разу за голову — я не дрожал.\r\n\r\nНо однажды в списке, в пыли приговора,\r\nЯ прочёл своё имя — дрогнула рука.\r\nИ понял я:\r\nКлинок мой — не вечен, впрочем, как и я.\r\n\r\nПрипев\r\nРубщик голов, без имени и снов,\r\nТы стал легендой среди криков и гробов.\r\nНо смерть пришла — без повязки, без зла —\r\nОна была мной… да, мною же была!\r\n\r\n(Ты снёс сто душ…\r\nА свою… сохранишь ли?)\r\n\r\n[Verse 2]\r\nЯ шёл за списком, как верный пёс,\r\nНо имена стирались — остался лишь вопрос.\r\nКто я без приговора, без плахи и стен?\r\nПросто мясо в чьём-то рассказе, о тенях и щепках сцен?\r\n\r\nБридж:\r\nЯ рубил за золото, за долги, за грехи…\r\nТеперь бы выкупить — хотя бы вдох…\r\nНо мой последний список — мой страх и итог:\r\nЯ сам в нём…\r\n…под номером - бог.\r\n\r\nПрипев\r\nРубщик голов — теперь молчит,\r\nБез лица, без пути, без причин и молитв.\r\nИ последний удар — не по шее, а душе:\r\nЯ отсёк себе... голову во тьме.\r\n\r\nАутро:\r\nИ топор мой упал,\r\nИз рук во мгле.\r\nНо эхо звучит,\r\nВ полной тишине...','2025-10-27 08:58:22','2025-10-27 08:58:22'),
(32,65,'[Verse 1]\r\nДом стоит на окраине, весь в траве,\r\nС подоконником, но сломанный весь.\r\nИ каждый, кто войдёт туда хоть раз —\r\nЧувствует взгляд… когда идёт назад.\r\n\r\nТы не вспомнишь, как пахнут цветы,\r\nНо вспомнишь, но шагать стало тяжело.\r\nОн не зовёт, он не шепчет во сне —\r\nОн просто ждёт… когда ты повернёшься.\r\n\r\nПрипев:\r\nДом, что смотрит в спину!\r\nОн не живой — но он дышит.\r\nДом, что смотрит в спину!\r\nЕсли ты там был —\r\nТы уже часть… того, что дышит в спину.\r\n\r\n(Что-то шевельнулось… в пустом зеркале…)\r\n\r\n[Verse 2]\r\nТот мальчик смеялся, крича “я не боюсь!”\r\nТеперь он молчит — где-то там, внизу.\r\nКто-то сказал: “Это ветер гуляет”…\r\nНо ветер не топает… и не вспоминает.\r\n\r\nНа чердаке лежит старый альбом,\r\nТы листал его — и нашёл себя в нём.\r\nХотя… ты здесь никогда не жил,\r\nА имя твоё — на двери висит.\r\n\r\nПрипев:\r\nДом, что смотрит в спину!\r\nСмотрит, когда ты идёшь прочь.\r\nДом, что помнит…\r\nдаже если ты — забыл все прочь.\r\n\r\nБридж:\r\nНе оборачивайся…\r\nЕсли не хочешь…\r\nувидеть себя…\r\nгде ты не должен быть.\r\n\r\nПрипев:\r\nДом, что смотрит в спину…\r\nОн — как страх, что идёт за тобой.\r\nДом, где ты остался… Даже если ты…\r\nУшёл.\r\n\r\nАутро:\r\n(Если ты здесь был…\r\nТо остался здесь навеки.)','2025-10-27 08:58:40','2025-10-27 08:58:40'),
(33,66,'[Verse 1]\r\nНа краю деревни, за оврагом мшистым,\r\nЖил старик, как тень, с лицом серо-листым.\r\nОн в закат выходил — фрак и сапоги надел,\r\nИ под гармошку провожал всех на восток...\r\n\r\nГоворят, кто услышит его аккорд,\r\nТот не доживёт до следующей недели…\r\nОн играет — и ветви шепчут в такт,\r\nА сзади за спиной — уже вырыт тракт.\r\n\r\nПрипев:\r\nГробовщик с гармошкой,\r\nОн не шутит, не орёт.\r\nГробовщик с гармошкой,\r\nВ яму сам тебя ведёт!\r\nПод весёлый перебор,\r\nТы идёшь, смеясь, в укор.\r\nА когда он замолчит…\r\n— гроб закроют… навсегда.\r\n\r\n[Verse 2]\r\nПарень в трактир как-то крикнул всерьёз:\r\n«Песни старика — да это всё не в серьёз!»\r\nНа утро гармошку нашли у ворот…\r\nА самого парня — не нашли. Даже следа… вот.\r\n\r\nКаждый аккорд — как финальный звон.\r\nТы не уйдёшь, если слышишь звон.\r\nИ кто на него смеётся сейчас —\r\nТот в землю уйдёт… в тот же час.\r\n\r\nПрипев:\r\nГробовщик с гармошкой!\r\nОн не щадит, не даёт отсрочек!\r\nГробовщик с гармошкой!\r\nПесню он сыграл — и конец строчкой!\r\n\r\nПрипев:\r\nГробовщик — не человек!\r\nОн играет всем за грех!\r\nИ когда звучит финал…\r\n\r\nАутро:\r\n(\"Гробовщик... Гробовщик...\")\r\n(\"Гробовщик! Гробовщик!\")\r\nГробовщик с гармошкой!\r\nОн не плачет не смеётся,\r\nОн несет тебе прощальный звон.\r\nГробовщик с гармошкой,\r\nБудет весело плясать.\r\nГробовщик с гармошкой,\r\nЭто все... Что надо знать!','2025-10-27 08:59:06','2025-10-27 08:59:06'),
(34,67,'[Verse 1]\r\nВ переулке за цирком стоит фургон,\r\nТам смеются без глаз — и поют в унисон.\r\nОн шьёт себе кукол из кожи живых,\r\nИ каждая кукла — как ты… из кожи живых!\r\n\r\nОн говорит: «Твоя кожа — как ноты!»\r\nОн играет на венах, как кто-то — на флейте.\r\nИ если ты слушаешь это — он ещё здесь,\r\nС иглой в ладони… и твоим именем — на петле!\r\n\r\nПрипев:\r\nПеснь кожаного кукловода —\r\n“Даже если не хочешь!”\r\nОн вшивает в тебя свой мотив,\r\n“Ты не будешь живым!”\r\n\r\n[Verse 2]\r\nЕго сцена — из костей и шрамов,\r\nОн дирижирует болью и драмой.\r\nКуклы смеются — без ртов и глаз,\r\nИ ты смеёшься… но уже в последний раз!\r\n\r\nБридж:\r\nОн поёт иглой по твоей спине,\r\nТы кричишь — и это звучит вполне.\r\nВ каждом стежке — по одной судьбе,\r\nИ ты шепчешь имя своё — но уже не себе.\r\n\r\nПрипев:\r\nПеснь кожаного кукловода —\r\n“Песнь кожаного кукловода!”\r\nТы — спектакль, и твой голос — стих,\r\nОн не отпустит, он не забудет…\r\nТы стал куклой —\r\n“И куклой остался!”\r\nИ ей не уйти прочь!','2025-10-27 08:59:28','2025-10-27 08:59:28'),
(35,68,'[Verse 1]\r\nЯ купила зеркало в лавке у снов,\r\nСказали: «Покажет всё — даже любовь».\r\nНо в нём не было моего лица,\r\nЛишь тех, кто умрёт… из-за меня.\r\n\r\nСначала — кошки, потом — собаки,\r\nЗатем — любимый… потом — НИКТО!\r\nЯ видела смерть раньше, чем сделать вдох…\r\nНо зеркало… я не бросила в окно!\r\n\r\nПрипев:\r\nЗеркало без отражения —\r\nМой шик! Моё проклятье! Моё превращение!\r\nСмотрю — и смеюсь, ну, что за портрет!\r\nОн ещё дышит... но его уже нет! Ха-ха-ха! О-о-о!\r\n\r\n\r\n(Твоё отражение теперь здесь…\r\nоно ждёт… оно ждёт.)\r\n\r\n[Verse 2]\r\nЯ пыталась забыть, сжечь, утопить,\r\nНо оно всплывало — снова звать, звать жить.\r\nЯ закрыла глаза… но он снова там!\r\nИ в сердце моём — зеркальный обман…\r\n\r\nБридж:\r\nЯ ведьма… или просто пророк,\r\nЧто предсказывает — но забывает итог.\r\nЯ знаю финалы, где никто не живёт,\r\nИ каждый мой взгляд — как билет в окно.\r\nЯ тону в отражении, как в чёрной воде,\r\nГде лица других — медленно тонут во мне.\r\n\r\nПрипев:\r\nМоё зеркало без отражения —\r\nМой мир, мой страх, моё забвение.\r\nЯ вижу его… а он — меня…\r\nНо мы никогда не будем как «вчера».\r\n\r\nАутро:\r\n(Смотри…\r\nЭто не ты. Это — я…)','2025-10-27 08:59:52','2025-10-27 08:59:52'),
(36,69,'[Verse 1]\r\nЯ пишу тебе ночью — здесь нет часов,\r\nЗдесь всё застыло… и даже боль — без слов.\r\nМожет, ты не получишь ни строчки из них,\r\nНо я верю: хоть одно попадёт между снов.\r\n\r\nЯ хотела сказать «прости» —\r\nНо не знаю, за что.\r\nЯ шептала «прощай» —\r\nНо осталась в голове твоей… навсегда.\r\n\r\nПрипев:\r\nЭто письмо из ниоткуда,\r\nТам, где шагов — не слышно.\r\nЭто письмо — без даты,\r\nНо я пишу, как дышу…\r\nПоследний раз. В предсмертный час!\r\n\r\n(Ты чувствуешь? Пальцы дрожат —\r\nХоть и не твои…)\r\n\r\n[Verse 2]\r\nЯ нашла старый свет — он остался в словах.\r\nТы не читал, но я слышала, как ты плакал во тьме.\r\nПисьма не горят… но зола — исчезает.\r\nТы носишь её в кармане — сам не зная чего.\r\n\r\nЯ хотела сказать «привет» —\r\nНо боялась, что сорвёшься в крик.\r\nЯ хотела остаться живой —\r\nНо осталась лишь… почерк, на листе.\r\n\r\nПрипев:\r\nЭто письмо из ниоткуда,\r\nГде всё пишется — в слепую.\r\nЭто письмо без подписи, имени и цели.\r\nНо ты точно знаешь…\r\nКто его писал.\r\nИ зачем оно к тебе,\r\nСнова вернулось!!!\r\n\r\nБридж:\r\nЯ — там, где дождь идёт вверх.\r\nЯ — между тенью и светом.\r\nЯ — строка, что не дочитали до конца.\r\nИ ты… всё ещё держишь меня в себе.\r\n\r\n(Письмо из ниоткуда…)\r\nПисьмо из ниоткуда…\r\n\r\nПрипев:\r\nПисьмо из ниоткуда…\r\nНо я не зову — я просто пою.\r\nЯ оставила звук —\r\nЧтобы ты не забыл,\r\nКак звучит тишина.\r\nИ только голос мой,\r\nРазбудит тебя... От вечного сна.\r\n\r\nАутро:\r\n(Если ты читаешь это…\r\nЗначит, я всё ещё есть. Я все еще жива, и все еще с тобой... Навсегда!)','2025-10-27 09:00:17','2025-10-27 09:00:17'),
(37,70,'Куплет:\r\nВ старой лавке в полумраке\r\nДед торгует зеркалами.\r\nА в глазах его блестящих\r\nОтражается зола.\r\nКаждый вечер кто-то новый\r\nЗабредает к нему домой,\r\nТолько утром дед считает\r\nКто вернуться в мир не смог\r\n\r\nПрипев:\r\nЭй, старик-хитрец, что в твоих глазах за свет?\r\nКто стучится в дверь твою?\r\nЧья душа в зеркалах… Утопает!\r\n\r\nКуплет:\r\nЯ зашел туда случайно\r\nПросто время скоротать.\r\nА старик мне улыбнулся:\r\n«есть что тебе показать!»\r\nНо в зеркалах его кривых\r\nЯ увидел целый ад!\r\nА за спинами отраженье\r\nИ чей-то очень жадный взгляд\r\n\r\nПрипев:\r\nЭй, старик-хитрец, что в твоих глазах за свет?\r\nКто стучится в дверь твою?\r\nЧья душа в зеркалах… Утопает!\r\n\r\nКуплет:\r\nЕли ноги я унёс из той лавки роковой,\r\nНо, теперь в любом стекле вижу облик не живой.\r\nА старик все так же ждет, зазывает в свой капкан\r\nТолько зеркала в той лавке, это души горожан!!!\r\n\r\nПрипев:\r\nЭй, старик-хитрец, что в твоих глазах за свет?\r\nКто стучится в дверь твою?\r\nЧья душа в зеркалах… Утопает!\r\n\r\nБридж:\r\nЕсли вдруг в пустой витрине,\r\nТы увидишь свой портрет.\r\nЗначит скоро в лавку к деду,\r\nТы найдешь прямой – БИЛЕТ!!!\r\n\r\nПрипев:\r\nЭй, старик-хитрец, что в твоих глазах за свет?\r\nКто стучится в дверь твою?\r\nЧья душа в зеркалах… Утопает!','2025-10-27 09:00:39','2025-10-27 09:00:39'),
(38,71,'[Verse 1]\r\nОн шёл по снегу всю ночь без сна,\r\nСледы исчезали — будто судьба.\r\nНа груди крест, в рюкзаке — хлеб,\r\nИ лицо как у тех, кто не просит совет.\r\nОн дошёл до избушки, где никто не живёт,\r\nНо дым поднимался — как будто кто-то ждёт.\r\nОн вошёл, не спросив, не сказав ни слова —\r\nИ за печкой что-то сказало: «Готова...»\r\n\r\nПрипев:\r\nОн уснул в лапах медведя,\r\nИ никто не видел, как он исчез.\r\nОн уснул в лапах медведя...\r\nТолько сон — был не про лес.\r\n\r\n\"Только сон — был не про лес...\"\r\n\r\n[Verse 2]\r\nВ том сне он был мальчиком снова,\r\nБежал по болоту, сбивая сердце.\r\nА медведь шептал: «Ты теперь мой»,\r\nНо он гладил его... по спине рукой.\r\n\r\nА за окном уже солнце село,\r\nИ звёзды смеялись, как стая волков.\r\nОн проснуться хотел, но глаза — не открылись,\r\nА тело — как пыль, и душа растворилась.\r\n\r\nПрипев:\r\nОн уснул в лапах медведя,\r\nГде не снится ни страх, ни боль, ни свет.\r\nОн уснул в лапах медведя...\r\nИ с тех пор… его следа нет.\r\n\"Его следа нет…\"\r\n\r\nБридж:\r\nГоворят, что в тех лесах\r\nИногда слышат дыхание.\r\nИ если ты устал...\r\nОн предложит тебе — отдохнуть.\r\n\r\nПрипев:\r\nОн уснул в лапах медведя...\r\nНе проси — не вернётся, не плачь, не вернёшься.\r\nОн уснул… и теперь зовёт\r\nТех, кто устал, кто не молится,\r\nИ не поёт.\r\n\r\nАутро:\r\n(Если ты устал — не ложись просто так.\r\nПослушай… где-то там… он дышит, ждёт и смеётся...)','2025-10-27 09:01:08','2025-10-27 09:01:08'),
(39,72,'[Verse 1]\r\nЛёд скрипит под палубой ночью,\r\nФлаг не трепещет — он давно застыл.\r\nКомпас ржавеет, карты — все в прах,\r\nНо он всё идёт — сквозь бурю и страх.\r\n\r\nКорабль — не судно, а проклятый зал,\r\nГде эхо приказов всё ещё звучит.\r\nОн не слышит шторма — он слышит парад\r\nТех, кто погиб, но флаг не спустит.\r\n\r\nПрипев:\r\nПолярный адмирал!\r\nНе сменит курс, даже если нет земли.\r\nПолярный адмирал!\r\nВода чёрная, но это часть пути.\r\nЕго команда померла давно,\r\nНо тени в строю — и ему всё равно.\r\n\r\n[Verse 2]\r\nГрузовые люки стучат, как сердца,\r\nТолько никто не кричит \"молния!\".\r\nНа его груди — вырезано \"Страсть\",\r\nА в глазах — маршрут в никуда.\r\n\r\nРадист не отвечает, но он всё шлёт,\r\nСигналы — в никуда, в проклятый лёд.\r\nОн знает, никто не ответит в эфире —\r\nНо он продолжает в своём мире.\r\n\r\nПрипев:\r\nПолярный адмирал!\r\nНе сменит курс, даже если нет земли!\r\nПолярный адмирал!\r\nВода чёрная, но это часть пути!\r\nОн плывёт сквозь смерть и боль —\r\nПотому что знает свою роль.\r\n\r\nБридж:\r\nОн не герой.\r\nОн отдал последний приказ.\r\nВсё забыл... и не смог вернуться назад...\r\n\r\nПрипев:\r\nПолярный адмирал!\r\nЕго путь — не к берегу, а вглубь!\r\nПолярный адмирал!\r\nПока плывёт — никто не забыт!\r\nИ если услышишь —\r\nГудок среди льдов…\r\nОн всё ещё рядом,\r\nОн не лёг на дно!\r\nПолярный адмирал!\r\n(Полярный адмирал!)\r\nПолярный адмирал — идёт на северный ФЛОТ!','2025-10-27 09:01:37','2025-10-27 09:01:37'),
(40,73,'[Verse 1]\r\nСцена пыльная, занавес рваный,\r\nГаснут свечи — спектакль желанный.\r\nПервый актёр выходит, дрожит…\r\nА в руке у него — не реквизит.\r\n\r\nВ каждом акте — одна смерть точна,\r\nКто играет — тот не вернётся со дна.\r\nПублика хлопает — крики в ответ…\r\nАплодисменты — и актёра уже нет.\r\n\r\nПрипев:\r\nВ Театре Крови…\r\nКаждый акт — как суд без вины.\r\nВ Театре Крови…\r\nТы на сцене —\r\nИ ты в роли… жертвы.\r\n\r\n(Тишина лишь в ответ...)\r\n\r\n[Verse 2]\r\nРежиссёр — не человек, не дух,\r\nОн не говорит… но знает слуг.\r\nОн шепчет сценарии в сны актёрам,\r\nА утром — играют… в закрытом костюме.\r\n\r\nКостюмер — с косой, билетёр — с клыками,\r\nТы вышел в свет — и закрылся занавес.\r\nСлова, что читаешь — не ты писал.\r\nСейчас твоя роль — навсегда финал.\r\n\r\nПрипев:\r\nВ Театре Крови!\r\nВсе кричат — а ты молчишь.\r\nВ Театре Крови!\r\nПублика жива…\r\nА ты — как раньше, больше не дышишь.\r\n\r\nБридж:\r\n(Ты мечтал сыграть Гамлета?..\r\nА теперь — ты череп.)\r\n(Твои реплики стерты…\r\nТвой текст — написан кровью.)\r\n(Аплодисменты, как гвозди в крышке гроба…)\r\n\r\n[Verse 3]\r\nПублика хлопает, пьёт и поёт,\r\nА за их спинами — что-то встаёт.\r\nНекоторые — будто и не живы давно,\r\nА один в ряду — смотрит вверх сквозь окно.\r\n\r\nПрипев:\r\nВ Театре Крови —\r\nПоследний крик — твой монолог.\r\nИ занавес падает…\r\n…навек в твой гроб!\r\n\r\nАутро:\r\nВ театре крови…\r\n(В театре крови…)\r\nВ театре крови…\r\nВсе живые выходят на бис.\r\nВ театре крови…\r\nКаждый мечтает быть живым.\r\nНо занавес падает —\r\nи не поднимется вновь.','2025-10-27 09:02:01','2025-10-27 09:02:01'),
(41,74,'[Verse 1]\r\nЯ торговец теней и лунных зеркал\r\nГде ночь — королева, а разум — провал.\r\nВ переулках шепчу, продаю страх и дым\r\nМои сказки из боли, и конец в них один.\r\n\r\nПрипев:\r\nДобро пожаловать в мрак — твой новый дом\r\nЗдесь каждый сон — это бой со злом\r\nЗови меня тихо, кричи в тишине\r\nЯ принесу кошмар твоей семье.\r\n\r\n[Verse 2]\r\nМои пальцы — как крюки, рвут сны на куски,\r\nЯ ловлю твою душу на цепи тоски.\r\nВ витринах иллюзий ты выбрал страх,\r\nЗдесь платят болью за мой ритуал.\r\n\r\nПрипев:\r\nДобро пожаловать в мрак — твой новый дом\r\nЗдесь каждый сон — это бой со злом\r\nЗови меня тихо, кричи в тишине\r\nЯ принесу кошмар твоей семье.\r\n\r\nБридж:\r\nТы думал уйти, но я знал наперёд.\r\nТы в петле моего сна, где надежда умрёт.\r\nКаждый шёпот твой — как молитва во тьму,\r\nЯ уже внутри, и не выйду к утру\r\n\r\n[Verse 3]\r\nЯ шагнул из зеркала прямо в твой коридор,\r\nНа обоях — мой след, а на полках — позор.\r\nТы искал утешенье, но нашёл лишь меня,\r\nИ теперь ты мой гость до последнего дня.\r\n\r\nПрипев:\r\nДобро пожаловать в мрак — твой новый дом\r\nЗдесь каждый сон — это бой со злом\r\nЗови меня тихо, кричи в тишине\r\nЯ принесу кошмар твоей семье','2025-10-27 09:02:23','2025-10-27 09:02:23'),
(42,75,'[Verse 1]\r\nНа краю деревни — дом под замком,\r\nС дорожкой из соли и веником у порога.\r\nВнутри висят портреты без лиц,\r\nИ каждый час здесь звучит, как финал.\r\n\r\nСкатерть из кожи, чайник поёт,\r\nА голос хозяйки — то ласка, то лёд.\r\nВместо часов — у неё календарь,\r\nНа котором все даты вычёркнуты прочь.\r\n\r\nЕсли дверь откроется — не зови по имени.\r\nТам не услышат, там всё наоборот.\r\nТы войдёшь — и не узнаешь стен,\r\nТолько лица — на потолке, в тишине.\r\n\r\nПрипев:\r\nУ тётки Агаты\r\nВ этом доме не бывает гостей.\r\nУ тётки Агаты\r\nКаждый звук превращается в крик.\r\nУ тётки Агаты\r\nВсё, что живо, забывает себя.\r\nИ никто не выходит туда, где трава.\r\n\r\n[Verse 2]\r\nНа полках бутылки — без этикеток,\r\nВнутри — то ли зелье, то ли чья-то душа.\r\nОна гладит по плечу и говорит:\r\n«Тебе повезло — я давно не варила».\r\n\r\nЗеркала закрыты, но в каждом углу\r\nТы видишь движения, которых не ждёшь.\r\nА в подвале — не ступени, а ступени судеб,\r\nКаждая из которых заканчивается вниз.\r\n\r\nБридж:\r\nЯ слышала, кто-то звал по ночам,\r\nИз темноты, из-под пола, из стен.\r\nЯ думала — ветер, сквозняк, пустота…\r\nНо потом голос сказал: «Теперь ты — здесь».\r\nОн не был злым, он был уставшим,\r\nКак тот, кто давно не говорил по делу.\r\nА я стояла… и не смогла отвернуться,\r\nПотому что — уже не могла уйти.\r\n\r\nПрипев:\r\nУ тётки Агаты\r\nЗдесь не бывает света дневного.\r\nУ тётки Агаты\r\nДаже слова здесь дышат иначе.\r\nУ тётки Агаты\r\nТы не чувствуешь боли —\r\nПока не вспомнишь,\r\nКем была… раньше.\r\n\r\n[Verse 3]\r\nОна шепчет сказки, в которых ты — герой,\r\nНо конец — не прописан, он пишется тобой.\r\nКто смеётся громко — тот первый упадёт,\r\nА кто молчит — того заберёт котёл.\r\n\r\nТы поймёшь всё сразу, когда закроют замок,\r\nКогда чай остынет, и свет уйдёт за порог.\r\nОстанется только дыхание стен\r\nИ твой голос… в голосе тех, кто был тут до тебя.\r\n\r\nПрипев:\r\nУ тётки Агаты\r\nТы не гость — ты часть уюта.\r\nУ тётки Агаты\r\nПамять — это только звук.\r\nУ тётки Агаты\r\nЕсли позвала — откликнись.\r\nНо помни:\r\nНе каждый зов — зов живых.\r\n\r\nАутро:\r\nНикто не выходит назад по ступеням.\r\nНикто не спрашивает «почему».\r\nТолько чай на столе и улыбка\r\nУ той, что зовёт по имени…\r\nИ никогда не отпускает.','2025-10-27 09:02:46','2025-10-27 09:02:46'),
(43,76,'[Verse 1]\r\nУ подножья холма — старая башня,\r\nГде никто не живёт, но горит свет.\r\nКаждый час — словно шёпот вчерашний,\r\nКаждый звон — словно смерти привет.\r\nЕё зовут просто — Хозяйка часов,\r\nНе живая, не мёртвая — между миров.\r\nСидит у циферблата с улыбкой пустой,\r\nИ шепчет: \"Твой час… уже на исходе.\"\r\n\r\nПрипев:\r\nВ доме, где стрелки молчат,\r\nВремя застыло навеки.\r\nВ доме, где стрелки молчат,\r\nДуши становятся эхом.\r\n\"Становятся эхом…\"\r\n\r\n[Verse 2]\r\nОн пришёл, как и многие до него,\r\nС вопросами, страхом и грузом вины.\r\nСпросил: «Сколько мне осталось времени?»\r\nОна засмеялась: \"Его больше нет!\"\r\nИ понял он — назад дороги нет,\r\nВ этот дом ты заходишь — теряя рассудок.\r\nОна не хранит — она плетёт судьбы,\r\nВ которых ты будешь навек один.\r\n\r\nТе, кто уходят — остаются эхом,\r\nВ каждом тике — их забытый шаг.\r\nТы хотел время? — теперь ты — стрелка.\r\nИ она заведёт тебя… в полный мрак.\r\n\r\nПрипев:\r\nВ доме, где стрелки молчат,\r\nКаждый стук — это сердце.\r\nВ доме, где стрелки молчат,\r\nНет ни начала, ни конца.\r\n\"Ни начала, ни конца…\"\r\n\r\nБридж:\r\n(Ты искал любовь — но нашёл проклятье.\r\nТы хотел ответы — получил молчание.\r\nТеперь ты — нота в её мелодии,\r\nЕщё один голос в хоре потерянных душ.\r\nСлушай... слышишь?\r\nЭто бьётся твоё сердце...\r\nВ последний раз...\r\nА потом — только тишина.)\r\n\r\nПрипев:\r\nВ доме, где стрелки молчат,\r\nТы не исчез — ты стал строчкой в книге.\r\nВ доме, где стрелки молчат,\r\nВремя и смерть стали вместе, опять.\r\n[Crowd Sing-Along]: \"Время и смерть стали вместе…\"\r\n\r\nАутро:\r\n(Когда часы молчат…\r\nОна начинает петь.\r\nИ каждый, кто слышит эту песню...\r\nИдёт к башне.)','2025-10-27 09:03:10','2025-10-27 09:03:10'),
(44,77,'[Verse 1]\r\nВ старом доме, где тени живут,\r\nСкрипит чердак — и время идёт назад.\r\nТам скелет в кожанке лежит,\r\nИ ржавой струной мрак сторожит.\r\n\r\nПрипев:\r\nСкелет под чердаком — играет рок,\r\nГвоздями по струнам, швыряет ток!\r\nОн ждёт тебя, он слышит шаг...\r\nИ если ты войдёшь — то не выйдешь никак.\r\n\r\n[Verse 2]\r\nНа краю деревни, за оврагом мшистым,\r\nЖил старик, как тень, с лицом серо-листым.\r\nОн в закат выходил — фрак и сапоги надел,\r\nИ под гармошку провожал всех на восток...\r\n\r\nПрипев:\r\nГробовщик с гармошкой!\r\nОн не шутит, не орёт!\r\nГробовщик с гармошкой!\r\nВ яму сам тебя ведёт!\r\nПод веселый перебор,\r\nТы идешь смеясь в укор...\r\n\r\n[Verse 3]\r\nВ старой лавке в полумраке\r\nДед торгует зеркалами.\r\nА в глазах его блестящих\r\nОтражается зола...\r\nКаждый вечер кто-то новый\r\nЗабредает к нему домой,\r\nТолько утром дед считает\r\nКто вернуться в мир не смог\r\n\r\nПрипев:\r\nЭй, старик-хитрец, что в твоих глазах за свет?\r\nКто стучится в дверь твою?..\r\nЧья душа в зеркалах... Утопает!\r\n\r\nБридж:\r\nЕсли вдруг в пустой витрине,\r\nТы увидишь свой портрет.\r\nЗначит скоро в лавку к деду,\r\nТы найдешь прямой – БИЛЕТ!!!\r\n\r\n[Verse 4]\r\nЯ шагнул из зеркала прямо в твой коридор,\r\nНа обоях — мой след, а на полках — позор.\r\nТы искал утешенье, но нашёл лишь меня,\r\nИ теперь ты мой гость до последнего дня.\r\n\r\n\r\nПрипев:\r\nДобро пожаловать в мрак — твой новый дом\r\nЗдесь каждый сон — это бой со злом\r\nЗови меня тихо, кричи в тишине\r\nЯ принесу кошмар твоей семье','2025-10-27 09:03:31','2025-10-27 09:03:31');
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
(38,'1. Последний из драконов','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a937a2.wav','2025-10-19 17:40:10.604','2025-10-27 08:33:58',NULL,NULL,NULL,'/public/uploads/videos/68f51cdce1ecb.mp4',NULL,307,0),
(39,'2. Спящий Страж','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a97462.mp3','2025-10-19 17:40:10.619','2025-10-27 08:34:40',NULL,NULL,NULL,NULL,NULL,289,0),
(40,'3. Стальной Легион','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a994f4.wav','2025-10-19 17:40:10.636','2025-10-27 08:35:03',NULL,NULL,NULL,NULL,NULL,296,0),
(41,'4. Феникс','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a9d600.wav','2025-10-19 17:40:10.644','2025-10-27 08:35:25',NULL,NULL,NULL,NULL,NULL,304,0),
(42,'5. Средиземье','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84a9f684.wav','2025-10-19 17:40:10.653','2025-10-27 08:35:47',NULL,NULL,NULL,NULL,NULL,309,0),
(43,'6. Прощальный взгляд','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa1726.wav','2025-10-19 17:40:10.661','2025-10-27 08:36:11',NULL,NULL,NULL,NULL,NULL,318,0),
(44,'7. Восхождение',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa37ba.wav','2025-10-19 17:40:10.670','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(45,'8. изгнанник мира','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa583e.wav','2025-10-19 17:40:10.678','2025-10-27 08:36:33',NULL,NULL,NULL,NULL,NULL,346,0),
(46,'9. Ледяной трон','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa78c4.wav','2025-10-19 17:40:10.686','2025-10-27 08:36:57',NULL,NULL,NULL,NULL,NULL,308,0),
(47,'10. Цена Любви','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aa9956.wav','2025-10-19 17:40:10.695','2025-10-27 08:37:20',NULL,NULL,NULL,NULL,NULL,319,0),
(48,'11. Последний рассвет','',3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aab9eb.wav','2025-10-19 17:40:10.703','2025-10-27 08:37:41',NULL,NULL,NULL,NULL,NULL,367,0),
(49,'12. Симфония вечности',NULL,3,'/public/uploads/album_covers/68f4f7da160db.png','/public/uploads/full/68f4f84aada42.wav','2025-10-19 17:40:10.711','2025-10-19 17:40:10',NULL,NULL,NULL,NULL,NULL,0,0),
(50,'1 .Шаги на воде','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522237d8e1.mp3','2025-10-19 20:38:43.514','2025-10-27 08:51:59',NULL,NULL,NULL,NULL,NULL,410,0),
(51,'2. Бал Вечной Тьмы','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223823d9.mp3','2025-10-19 20:38:43.541','2025-10-27 08:52:26',NULL,NULL,NULL,NULL,NULL,265,0),
(52,'3. Шёпот во тьме','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223864e9.mp3','2025-10-19 20:38:43.550','2025-10-27 08:52:51',NULL,NULL,NULL,NULL,NULL,240,0),
(53,'4. Сияние в бездне','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238856e.mp3','2025-10-19 20:38:43.558','2025-10-27 08:53:58',NULL,NULL,NULL,NULL,NULL,345,0),
(54,'5. Последний рассвет','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238a5f9.mp3','2025-10-19 20:38:43.567','2025-10-27 08:54:32',NULL,NULL,NULL,NULL,NULL,226,0),
(55,'6. Ворон','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238c654.mp3','2025-10-19 20:38:43.575','2025-10-27 08:54:56',NULL,NULL,NULL,NULL,NULL,349,0),
(56,'7. Аннабель Ли','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522238e717.mp3','2025-10-19 20:38:43.583','2025-10-27 08:55:16',NULL,NULL,NULL,NULL,NULL,240,0),
(57,'8. Мой создатель. История Франкенштейна','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f5222390796.mp3','2025-10-19 20:38:43.592','2025-10-27 08:55:42',NULL,NULL,NULL,NULL,NULL,217,0),
(58,'9. Печать Мефистофеля','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f522239280d.mp3','2025-10-19 20:38:43.600','2025-10-27 08:56:04',NULL,NULL,NULL,NULL,NULL,339,0),
(59,'10. Маска и тень','',4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f52223948a7.mp3','2025-10-19 20:38:43.608','2025-10-27 08:56:31',NULL,NULL,NULL,NULL,NULL,294,0),
(60,'11. Phantom of the Opera (бонус)',NULL,4,'/public/uploads/album_covers/68f521f7f26c8.webp','/public/uploads/full/68f5222396936.mp3','2025-10-19 20:38:43.617','2025-10-19 20:38:43',NULL,NULL,NULL,NULL,NULL,0,0),
(61,'1. Скелет под чердаком','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9489c2.wav','2025-10-19 20:45:45.297','2025-10-27 08:57:12',NULL,NULL,NULL,NULL,NULL,183,0),
(62,'2. анархист-революционер','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c952c7b.wav','2025-10-19 20:45:45.339','2025-10-27 08:57:36',NULL,NULL,NULL,NULL,NULL,234,0),
(63,'3. Грим под гробовой свет','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c95f679.wav','2025-10-19 20:45:45.391','2025-10-27 08:57:57',NULL,NULL,NULL,NULL,NULL,259,0),
(64,'4. Баллада о рубщике голов','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c96b73d.wav','2025-10-19 20:45:45.441','2025-10-27 08:58:22',NULL,NULL,NULL,NULL,NULL,223,0),
(65,'5. Дом, что смотрит в след','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c977cef.wav','2025-10-19 20:45:45.571','2025-10-27 08:58:40',NULL,NULL,NULL,NULL,NULL,267,0),
(66,'6. Гробовщик с гармошкой','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c998736.wav','2025-10-19 20:45:45.625','2025-10-27 08:59:06',NULL,NULL,NULL,NULL,NULL,201,0),
(67,'7. Кукловод','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9a8d1b.wav','2025-10-19 20:45:45.692','2025-10-27 08:59:28',NULL,NULL,NULL,NULL,NULL,199,0),
(68,'8. Зеркало без отражения','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9b4d65.wav','2025-10-19 20:45:45.741','2025-10-27 08:59:52',NULL,NULL,NULL,NULL,NULL,219,0),
(69,'9. Письмо из ниоткуда','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9c51bc.wav','2025-10-19 20:45:45.808','2025-10-27 09:00:17',NULL,NULL,NULL,NULL,NULL,266,0),
(70,'10. Охотник За Душами','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9cfb3d.mp3','2025-10-19 20:45:45.851','2025-10-27 09:00:39',NULL,NULL,NULL,NULL,NULL,234,0),
(71,'11. Сон в лапах медведя','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9d7ec7.wav','2025-10-19 20:45:45.885','2025-10-27 09:01:08',NULL,NULL,NULL,NULL,NULL,284,0),
(72,'12. Полярный адмирал','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9e2fa8.wav','2025-10-19 20:45:45.930','2025-10-27 09:01:37',NULL,NULL,NULL,NULL,NULL,244,0),
(73,'13. Театр крови','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523c9ec766.wav','2025-10-19 20:45:45.970','2025-10-27 09:02:01',NULL,NULL,NULL,NULL,NULL,230,0),
(74,'14. Торговец теней','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca00bdc.wav','2025-10-19 20:45:46.003','2025-10-27 09:02:23',NULL,NULL,NULL,NULL,NULL,214,0),
(75,'15. У тётки Агаты','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca03ccc.wav','2025-10-19 20:45:46.016','2025-10-27 09:02:46',NULL,NULL,NULL,NULL,NULL,252,0),
(76,'16. Хозяйка часов','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca05d3d.wav','2025-10-19 20:45:46.024','2025-10-27 09:03:10',NULL,NULL,NULL,NULL,NULL,259,0),
(77,'17. Театр живых - поппури','',5,'/public/uploads/album_covers/68f5233f80410.png','/public/uploads/full/68f523ca112eb.wav','2025-10-19 20:45:46.071','2025-10-27 09:03:31',NULL,NULL,NULL,NULL,NULL,354,0),
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoClips`
--

LOCK TABLES `VideoClips` WRITE;
/*!40000 ALTER TABLE `VideoClips` DISABLE KEYS */;
INSERT INTO `VideoClips` VALUES
(1,38,'/public/uploads/videos/68f51cdce1ecb.mp4','/public/uploads/album_covers/68f4f7da160db.png','1. Последний из драконов',NULL,307,0,'2025-10-27 08:33:59','2025-10-27 08:33:59');
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

-- Dump completed on 2025-10-28 10:30:03
