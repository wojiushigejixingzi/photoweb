/*�û���*/
CREATE TABLE `user` (
      `id` bigint(20) unsigned NOT NULL DEFAULT '0',
			`name` VARCHAR(20) NOT NULL DEFAULT '',
			`password` VARCHAR(20) NOT NULL DEFAULT '',
			`phonenumber` VARCHAR(50) NOT NULL DEFAULT '',
			`email` VARCHAR(50) NOT NULL DEFAULT '',
      `role` varchar(50) NOT NULL DEFAULT '',
      `remember_token` varchar(100) NOT NULL DEFAULT '',
      `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*�ϴ�ͼƬ��*/
CREATE TABLE `image` (
      `id` bigint(20) unsigned NOT NULL DEFAULT '0',
      `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
      `imageMessage` VARCHAR(255) NOT NULL DEFAULT '',
      `phonenumber` VARCHAR(50) NOT NULL DEFAULT '',
      `type` VARCHAR(20) NOT NULL DEFAULT '',
      `imgUrl` VARCHAR(255) NOT NULL DEFAULT '',
      `status` varchar(20) NOT NULL DEFAULT '',
      `commentaryInformation` VARCHAR(255) NOT NULL DEFAULT '',/*�����Ϣ*/
      `reviewerUserId` bigint(20) unsigned NOT NULL DEFAULT '0',
      `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*�ղر�*/
CREATE TABLE `Collection` (
       `id` bigint(20) unsigned NOT NULL DEFAULT '0',
       `imageId` bigint(20) unsigned NOT NULL DEFAULT '0',
       `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
       `folderId` bigint(20) unsigned NOT NULL DEFAULT '0',/*�ղؼ�id*/
       `status` varchar(20) NOT NULL DEFAULT 'VALID',
       `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
       `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*���۱�*/
CREATE TABLE `comment` (
        `id` bigint(20) unsigned NOT NULL DEFAULT '0',
        `imageId` bigint(20) unsigned NOT NULL DEFAULT '0',
        `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
        `message` VARCHAR(255) NOT NULL DEFAULT '',
        `status` varchar(20) NOT NULL DEFAULT 'VALID',
        `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*�ļ��б�*/
CREATE TABLE `folder` (
         `id` bigint(20) unsigned NOT NULL DEFAULT '0',
         `parentId` bigint(20) unsigned NOT NULL DEFAULT '0',
         `userId` bigint(20) unsigned NOT NULL DEFAULT '0',
         `name` VARCHAR(50) NOT NULL DEFAULT '',
         `status` varchar(20) NOT NULL DEFAULT 'VALID',
         `ctime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
         `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;