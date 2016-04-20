/* ============================= CMSGears Notify ======================================== */

--
-- Table structure for table `cmg_notify_event`
--

DROP TABLE IF EXISTS `cmg_notify_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_notify_event` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `preReminderCount` smallint(6) DEFAULT NULL,
  `preReminderInterval` smallint(6) DEFAULT NULL,
  `postReminderCount` smallint(6) DEFAULT NULL,
  `postReminderInterval` smallint(6) DEFAULT NULL,
  `trash` tinyint(1) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `scheduledAt` datetime DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_event_1` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_notify_model_notification`
--

DROP TABLE IF EXISTS `cmg_notify_model_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_notify_model_notification` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_notification_1` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_notify_model_reminder`
--

DROP TABLE IF EXISTS `cmg_notify_model_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_notify_model_reminder` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) DEFAULT NULL,
  `eventId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_reminder_1` (`userId`),
  KEY `fk_cmg_model_reminder_2` (`eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


SET FOREIGN_KEY_CHECKS=0;

--
-- Constraints for table `cmg_notify_event`
--

ALTER TABLE `cmg_notify_event`
	ADD CONSTRAINT `fk_cmg_event_1` FOREIGN KEY (`userId`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_notify_model_notification`
--

ALTER TABLE `cmg_notify_model_notification`
	ADD CONSTRAINT `fk_cmg_model_notification_1` FOREIGN KEY (`userId`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_notify_model_reminder`
--

ALTER TABLE `cmg_notify_model_reminder`
	ADD CONSTRAINT `fk_cmg_model_reminder_1` FOREIGN KEY (`userId`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_model_reminder_2` FOREIGN KEY (`eventId`) REFERENCES `cmg_notify_event` (`id`);

SET FOREIGN_KEY_CHECKS=1;