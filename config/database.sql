-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `articleAlias` int(10) unsigned NOT NULL default '0',
  `randomCE` char(1) NOT NULL default '',
  `keepCE` varchar(3) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

