-- phpMyAdmin SQL Dump
-- version 3.0.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 16, 2012 at 11:57 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tshop_pest`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `admin_status_online` tinyint(1) NOT NULL default '0' COMMENT '1 : Online | 0 : Offline',
  `admin_login_last` int(20) NOT NULL default '0',
  `admin_active_last` int(20) NOT NULL default '0',
  `admin_log_sessid` text,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=34 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `admin_status_online`, `admin_login_last`, `admin_active_last`, `admin_log_sessid`) VALUES
(1, 1, 0, 0, 1318835852, '31d00d55329550a6bf532114705c612e'),
(2, 2, 0, 0, 1318904035, '3e4aeaaadec731f5136260726622f959');

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE IF NOT EXISTS `banner` (
  `banner_id` int(11) NOT NULL auto_increment,
  `banner_file` text,
  `file_type_id` int(11) NOT NULL,
  `banner_link` text,
  `banner_target` varchar(20) NOT NULL,
  `banner_width` int(5) NOT NULL,
  `banner_height` int(5) NOT NULL,
  `banner_alt` text,
  PRIMARY KEY  (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`banner_id`, `banner_file`, `file_type_id`, `banner_link`, `banner_target`, `banner_width`, `banner_height`, `banner_alt`) VALUES
(1, '1349316327banner1.png', 1, '', '_blank', 0, 0, ''),
(2, '1349316339banner2.png', 1, '', '_blank', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `bbs`
--

CREATE TABLE IF NOT EXISTS `bbs` (
  `bbs_id` int(11) NOT NULL auto_increment,
  `bbs_password` varchar(200) default NULL,
  `bbs_count` int(11) default '1',
  `bbs_download` int(11) NOT NULL,
  `bbs_author` varchar(50) default NULL,
  `bbs_date_created` int(11) default NULL,
  `bbs_date_modified` int(11) default NULL,
  `bbs_order` int(11) NOT NULL,
  `bbs_status` tinyint(1) NOT NULL default '1',
  `bbs_page_id` int(11) NOT NULL default '0',
  `bbs_left` int(11) default '0',
  `bbs_right` int(11) default '0',
  `bbs_level` int(11) default '0',
  `bbs_sort_order` int(11) default NULL,
  PRIMARY KEY  (`bbs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bbs`
--

INSERT INTO `bbs` (`bbs_id`, `bbs_password`, `bbs_count`, `bbs_download`, `bbs_author`, `bbs_date_created`, `bbs_date_modified`, `bbs_order`, `bbs_status`, `bbs_page_id`, `bbs_left`, `bbs_right`, `bbs_level`, `bbs_sort_order`) VALUES
(1, '', 1, 0, '', NULL, NULL, 0, 1, 1, 1, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bbs_content`
--

CREATE TABLE IF NOT EXISTS `bbs_content` (
  `bbs_id` int(11) NOT NULL,
  `languages_id` tinyint(11) NOT NULL,
  `bbs_title` varchar(200) NOT NULL,
  `bbs_content` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bbs_content`
--


-- --------------------------------------------------------

--
-- Table structure for table `bbs_file`
--

CREATE TABLE IF NOT EXISTS `bbs_file` (
  `bbs_file_id` int(11) NOT NULL auto_increment,
  `bbs_id` int(11) NOT NULL,
  `file_type_id` tinyint(11) NOT NULL,
  `bbs_file_name` text NOT NULL,
  `bbs_file_description` text,
  `bbs_file_date_created` int(20) NOT NULL,
  `bbs_file_download` int(20) NOT NULL default '0',
  `bbs_file_order` int(11) NOT NULL,
  PRIMARY KEY  (`bbs_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bbs_file`
--


-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL auto_increment,
  `bbs_id` int(11) NOT NULL,
  `page_type_name` varchar(255) NOT NULL,
  `page_id` int(11) NOT NULL,
  `comment_password` varchar(50) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_time` int(20) NOT NULL,
  `comment_author` varchar(20) NOT NULL,
  `comment_email` varchar(100) NOT NULL,
  `comment_status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=18 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_id` int(11) NOT NULL auto_increment,
  `configuration_title` text NOT NULL,
  `configuration_key` varchar(255) NOT NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` text NOT NULL,
  `configuration_group_id` int(11) NOT NULL default '0',
  `configuration_sort_order` int(5) default NULL,
  `configuration_last_modified` datetime default NULL,
  `configuration_date_added` datetime NOT NULL default '0001-01-01 00:00:00',
  `configuration_use_function` text,
  `configuration_set_function` text,
  PRIMARY KEY  (`configuration_id`),
  UNIQUE KEY `unq_config_key_zen` (`configuration_key`),
  KEY `idx_key_value_zen` (`configuration_key`,`configuration_value`(10)),
  KEY `idx_cfg_grp_id_zen` (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=43 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`configuration_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`, `configuration_last_modified`, `configuration_date_added`, `configuration_use_function`, `configuration_set_function`) VALUES
(1, 'Banner Left', 'BANNER_LEFT', 'order', '', 0, NULL, NULL, '2010-04-12 00:00:00', NULL, NULL),
(2, 'Banner Top', 'BANNER_TOP', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(3, 'Banner Right', 'BANNER_RIGHT', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(4, 'Banner Center', 'BANNER_CENTER_TOP', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(5, 'Banner Bottom', 'BANNER_BOTTOM', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(6, 'Template', 'TEMPLATE', 'mch', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(7, 'Show Login form', 'LOGIN_FRM', '2', '1 : left 2 : right 0 : hide show login form in Client page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(8, 'Show Left Column', 'LEFT_COLUMN', '1', '1 : show 0 : hidden Show left column in Client page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(9, 'Right Column', 'RIGHT_COLUMN', '1', '1 : show 0 : hidden Show right column in Client page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(10, 'Google Calendar', 'GOOGLE_CALENDAR', '0', '1 : active 0 : inactive', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(11, 'Num Line Admin', 'ADMIN_NUM_LINE', '10', 'Number line of Admin page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(12, 'Num Line Client', 'CLIENT_NUM_LINE', '5', 'Number line of Client page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(13, 'Admin Languages', 'ADMIN_LANG', '1', 'Languages of Admin page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(14, 'Client Lang', 'CLIENT_LANG', '1', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(15, 'Format Short Date', 'FORMAT_SHORT_DATE', 'm/d/Y', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(16, 'Format Long Date', 'FORMAT_LONG_DATE', 'F j, Y, g:i a', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(17, 'Admin Theme', 'ADMIN_THEME', '', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(18, 'Client Theme', 'CLIENT_THEME', 'styleMCH10', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(19, 'Default Status Register', 'DEF_SREG', '1', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(20, 'Home Num Row', 'HOME_NUM_ROW', '1', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(21, 'Home Num Column', 'HOME_NUM_COL', '1', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(22, 'Home Number Record Default', 'HOME_NUM_REC_DEF', '1', 'Number of records in one cell of Home Table', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(23, 'Target Menu', 'TARGET_MENU', '_blank', 'Target for external link in Menu', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(24, 'Banner Center Bottom', 'BANNER_CENTER_BOTTOM', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(25, 'Show Contact form', 'SUPPORT_FRM', '2', '1 : left 2 : right 0 : hide show contact form in Client page', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(26, 'Max Width Of Image On Detail', 'MAX_WIDTH_IMAGE_DETAIL', '450', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(27, 'Max Height Of Image On Detail', 'MAX_HEIGHT_IMAGE_DETAIL', '0', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(28, 'Banner Left Outside', 'BANNER_LEFT_OUTSIDE', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(29, 'Banner Right Outside', 'BANNER_RIGHT_OUTSIDE', 'order', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(30, 'Banner Top on Top', 'BANNER_TOP_T', 'order', 'Banner Top on Top', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(31, 'Banner Top on Bottom', 'BANNER_TOP_B', 'order', 'Banner Top on Bottom', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(32, 'Banner Left on Top', 'BANNER_LEFT_T', 'order', 'Banner Left on Top', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(33, 'Banner Left on Bottom', 'BANNER_LEFT_B', 'order', 'Banner Left on Bottom', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(34, 'Banner Right on Top', 'BANNER_RIGHT_T', 'order', 'Banner Right on Top', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(35, 'Banner Right on Bottom', 'BANNER_RIGHT_B', 'order', 'Banner Right on Bottom', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(36, 'Max Width Banner Admin List', 'MAX_WIDTH_BANNER_ADMIN_LIST', '150', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(37, 'Max Height Banner Admin List', 'MAX_HEIGHT_BANNER_ADMIN_LIST', '60', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(38, 'Enable Comment', 'ENABLE_COMMENT', '1', '1:Enable, 0:Disable', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(39, 'Banner Center', 'BANNER_CENTER', 'order', '', 0, NULL, NULL, '2012-04-12 00:00:00', NULL, NULL),
(40, 'Enable Akcomp', 'ENABLE_AKCOMP', '1', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(41, 'RSS NEWS', 'RSS_NEWS_URL', 'http://', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(42, 'RSS BLOG', 'RSS_BLOG_URL', 'http://', '', 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `contact_id` tinyint(1) NOT NULL auto_increment,
  `contact_email` tinyint(1) NOT NULL default '1',
  `contact_address` tinyint(1) NOT NULL default '1',
  `contact_city` tinyint(1) NOT NULL default '1',
  `contact_state` tinyint(1) NOT NULL default '1',
  `contact_zipcode` tinyint(1) NOT NULL default '1',
  `contact_phone` tinyint(1) NOT NULL default '1',
  `contact_fax` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `contact_email`, `contact_address`, `contact_city`, `contact_state`, `contact_zipcode`, `contact_phone`, `contact_fax`) VALUES
(1, 1, 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customers_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `customers_name` varchar(255) default NULL,
  `customers_no` varchar(255) default NULL,
  `customers_firstname` varchar(32) NOT NULL,
  `customers_lastname` varchar(32) NOT NULL,
  `customers_nick` varchar(96) NOT NULL,
  `customers_address` varchar(255) default NULL,
  `customers_address2` varchar(255) default NULL,
  `customers_city` varchar(255) default NULL,
  `customers_state` varchar(255) default NULL,
  `customers_zipcode` varchar(255) default NULL,
  `customers_phone` varchar(32) NOT NULL,
  `customers_cellphone` varchar(32) default NULL,
  `customers_fax` varchar(32) default NULL,
  `customers_newsletter` char(1) default NULL,
  `customers_type` tinyint(4) NOT NULL COMMENT '0:General,1:Wholesaler',
  `customers_point` int(11) default NULL,
  `customers_account_no` varchar(255) default NULL,
  `customers_company` varchar(255) default NULL,
  `customers_note` text,
  PRIMARY KEY  (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=10 ;

--
-- Dumping data for table `customers`
--


-- --------------------------------------------------------

--
-- Table structure for table `data_template`
--

CREATE TABLE IF NOT EXISTS `data_template` (
  `configuration_id` int(11) NOT NULL auto_increment,
  `languages_id` int(11) default NULL,
  `configuration_title` text,
  `configuration_key` varchar(255) default NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` text,
  `configuration_group_id` int(11) default '0',
  `configuration_sort_order` int(5) default NULL,
  `configuration_last_modified` datetime default NULL,
  `configuration_date_added` datetime default '0001-01-01 00:00:00',
  `configuration_use_function` text,
  `configuration_set_function` text,
  PRIMARY KEY  (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=10 ;

--
-- Dumping data for table `data_template`
--

INSERT INTO `data_template` (`configuration_id`, `languages_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`, `configuration_last_modified`, `configuration_date_added`, `configuration_use_function`, `configuration_set_function`) VALUES
(1, 1, 'Email Contact', 'EMAIL_CONTACT', '<p>#contact_name#,</p>\r\n<p>Phone: #contact_phone#,</p>\r\n<p>#contact_content#</p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(2, 2, 'Email Contact', 'EMAIL_CONTACT', '<p>#contact_name#,</p>\r\n<p>ĐT: #contact_phone#,</p>\r\n<p>#contact_content#</p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(3, 3, 'Email Contact', 'EMAIL_CONTACT', '<p>#contact_name#,</p>\r\n<p>전화: #contact_phone#,</p>\r\n<p>#contact_content#</p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(4, 1, 'Email Register', 'EMAIL_REGISTER', '<p>Dear <strong>#name#</strong>,</p>\r\n<p>Congratulations! Your new account has been successfully created! <br />\r\nYou can now take advantage of our member privileges. If you have any<br />\r\nquestions about the operation of our website, please email to <strong>#sitename#</strong>. <br />\r\nThis is your account:<br />\r\nUser name: <strong>#username#</strong><br />\r\nPassword: <strong>#password#</strong><br />\r\n<br />\r\nAdministrator</p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(5, 2, 'Email Register', 'EMAIL_REGISTER', '<p>Chào <strong>#name#</strong>,</p>\r\n<p>Chúc mừng! Tài khoản của bạn đã được tạo! <br />\r\nBạn đã có đủ quyền của thành viên. Nếu bạn có bất cứ<br />\r\ncâu hỏi nào về website, vui lòng liên hệ với chúng tôi. <br />\r\nĐây là tài khoản của bạn: <br />\r\nTên đăng nhập: <strong>#username#</strong><br />\r\nMật khẩu: <strong>#password#</strong><br />\r\n<br />\r\nQuản trị</p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(6, 3, 'Email Register', 'EMAIL_REGISTER', '<p> 친애하는 <strong>#name#</strong>, </p>\r\n<p> 전환 축하합니다! 새 계정이 성공적으로 만들어졌습니다! <br/>\r\n이제 우리 회원 권한을 활용할 수 있습니다. 혹시 <br/>있다면\r\n우리의 웹사이트의 작동에 대해 질문, <strong>#sitename#</strong><br/>\r\n이것은 사용자 계정입니다 : <br의 />\r\n사용자 이름 : <strong>#username#</strong> <br/>\r\n비밀 번호 : <strong>#password#</strong> <br/>\r\n<br/>\r\n관리자 </p>', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(7, 1, 'Email Forgotpass', 'EMAIL_FORGOTPASS', '<p>Dear <strong>#name#</strong>,</p>\r\n<p>This email has been sent to you because you forgot your password.<br />\r\nIf you have any questions about the operation of this website, please email <strong>#sitename#</strong>.</p>\r\n<p>This is your information:<br />\r\nUsername: <strong>#username#</strong><br />\r\nPassword: <strong>#password#</strong><br />\r\n<br />\r\nAdministrator', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(8, 2, 'Email Forgotpass', 'EMAIL_FORGOTPASS', '<p>Chào <strong>#name#</strong>,</p>\r\n<p>Email này gửi cho bạn bởi vì bạn quên mật khẩu.<br />\r\nNếu bạn có bất cứ câu hỏi nào về website, vui lòng liên hệ <strong>#sitename#</strong>.</p>\r\n<p>Đây là thông tin của bạn:<br />\r\nTên đăng nhập: <strong>#username#</strong><br />\r\nMật khẩu: <strong>#password#</strong><br />\r\n<br />\r\nQuản trị', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL),
(9, 3, 'Email Forgotpass', 'EMAIL_FORGOTPASS', '<p> 친애하는 <strong> #name# </strong>, </p>\r\n비밀 번호를 잊었 때문에이 이메일로 발송되었습니다 \r\n<br/>을\r\n이 웹 사이트의 작동에 대한 문의 사항이 있으시면, 이메일 주시기 바랍니다 때 <strong>#sitename#</strong>. </p>\r\n<p> 전환이 귀하의 정보입니다 : <br/>\r\n사용자 이름 : <strong>#username#</strong><br />\r\n비밀 번호 : <strong>#password#</strong> <br/>\r\n<br/>\r\n관리자', NULL, 0, NULL, NULL, '0001-01-01 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_type`
--

CREATE TABLE IF NOT EXISTS `file_type` (
  `file_type_id` tinyint(11) NOT NULL auto_increment,
  `file_type_detail` varchar(50) NOT NULL,
  `file_type_ext` varchar(50) NOT NULL,
  PRIMARY KEY  (`file_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=9 ;

--
-- Dumping data for table `file_type`
--

INSERT INTO `file_type` (`file_type_id`, `file_type_detail`, `file_type_ext`) VALUES
(1, 'image', 'jpg,jpeg,gif,png'),
(2, 'flash', 'flv,swf'),
(3, 'document', 'doc,docx,pdf,rtf,txt'),
(4, 'worksheet', 'xls,xlsx'),
(5, 'presentation', 'ppt,pptx'),
(6, 'video', 'avi,mpg,mpeg,dat,wmv,rm,mov'),
(7, 'compress', 'zip,rar,tar,gz,bz2,7z'),
(8, 'music', 'mp3,wma');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE IF NOT EXISTS `footer` (
  `footer_id` tinyint(11) NOT NULL auto_increment,
  `footer_type` tinyint(1) NOT NULL COMMENT '0:general| 1:image fullt | 2:image component | 3:flash',
  `footer_image` text,
  `footer_flash` text,
  `footer_content` text NOT NULL,
  PRIMARY KEY  (`footer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`footer_id`, `footer_type`, `footer_image`, `footer_flash`, `footer_content`) VALUES
(1, 0, NULL, NULL, 'Copyright © 2012 MCH. All rights reserved. Powered by <a href="http://www.tknowledge.com/" target="_blank">TechKnowledge</a>');

-- --------------------------------------------------------

--
-- Table structure for table `header`
--

CREATE TABLE IF NOT EXISTS `header` (
  `page_id` tinyint(11) NOT NULL,
  `header_type` tinyint(4) default '2' COMMENT '0:General,1:image fullt,2:image component,3:flash',
  `header_content` text,
  `header_image` text,
  `header_flash` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `header`
--

INSERT INTO `header` (`page_id`, `header_type`, `header_content`, `header_image`, `header_flash`) VALUES
(1, 2, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE IF NOT EXISTS `home` (
  `home_id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL,
  `home_row` tinyint(5) NOT NULL,
  `home_col` tinyint(1) NOT NULL,
  `num_row` tinyint(4) NOT NULL,
  PRIMARY KEY  (`home_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=144 ;

--
-- Dumping data for table `home`
--

INSERT INTO `home` (`home_id`, `page_id`, `home_row`, `home_col`, `num_row`) VALUES
(90, -1, 0, 1, 0),
(140, -1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `languages_id` int(11) NOT NULL auto_increment,
  `languages_name` varchar(32) default NULL,
  `languages_code` char(10) default NULL,
  `languages_charset` varchar(255) default NULL,
  `languages_image` varchar(64) default NULL,
  `languages_status` tinyint(4) default NULL,
  `languages_sort_order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`languages_id`),
  KEY `idx_languages_name_zen` (`languages_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=8 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languages_id`, `languages_name`, `languages_code`, `languages_charset`, `languages_image`, `languages_status`, `languages_sort_order`) VALUES
(1, 'English', 'en_US', 'utf-8', 'us.png', 1, 0),
(3, 'Korean', 'ko_KR', 'utf-8', 'kr.png', 0, 0),
(2, 'Viet Nam', 'vi_VN', 'utf-8', 'vn.png', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE IF NOT EXISTS `menu_categories` (
  `menu_categories_id` int(11) NOT NULL auto_increment,
  `menu_categories_image` varchar(64) default NULL,
  `menu_categories_link` varchar(64) default NULL,
  `menu_categories_parent_id` int(11) default '0',
  `menu_categories_sort_order` int(3) default NULL,
  `menu_categories_date_added` datetime default NULL,
  `menu_categories_last_modified` datetime default NULL,
  `menu_categories_status` tinyint(1) default '1',
  `menu_categories_pid` int(11) default NULL,
  `menu_categories_gid` int(11) default NULL,
  `menu_categories_level` tinyint(4) default NULL,
  `menu_categories_path` varchar(255) default NULL,
  `menu_categories_left` int(11) default NULL,
  `menu_categories_right` int(11) default NULL,
  PRIMARY KEY  (`menu_categories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=51 ;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`menu_categories_id`, `menu_categories_image`, `menu_categories_link`, `menu_categories_parent_id`, `menu_categories_sort_order`, `menu_categories_date_added`, `menu_categories_last_modified`, `menu_categories_status`, `menu_categories_pid`, `menu_categories_gid`, `menu_categories_level`, `menu_categories_path`, `menu_categories_left`, `menu_categories_right`) VALUES
(1, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, 94),
(34, '', '', 1, NULL, NULL, NULL, 1, 1, NULL, 1, '34|', 2, 7),
(35, '', 'admin_account', 34, NULL, NULL, NULL, 1, 34, NULL, 2, '34|35|', 3, 4),
(36, '', 'admin_customer', 34, NULL, NULL, NULL, 1, 34, NULL, 2, '34|36|', 5, 6),
(37, '', 'admin_layout', 1, NULL, NULL, NULL, 1, 1, NULL, 1, '37|', 8, 9),
(38, '', 'admin_page/view/mptt', 1, NULL, NULL, NULL, 1, 1, NULL, 1, '38|', 10, 15),
(39, '', 'admin_page/view/mptt', 38, NULL, NULL, NULL, 0, 38, NULL, 2, '38|39|', 11, 12),
(40, '', 'admin_page/view/list', 38, NULL, NULL, NULL, 0, 38, NULL, 2, '38|40|', 13, 14),
(41, '', 'admin_banner', 1, NULL, NULL, NULL, 1, 1, NULL, 1, '41|', 16, 17),
(42, '', '', 1, NULL, NULL, NULL, 1, 1, NULL, 1, '42|', 18, 33),
(43, '', 'admin_language', 42, NULL, NULL, NULL, 0, 42, NULL, 2, '42|43|', 31, 32),
(44, '', 'admin_config', 42, NULL, NULL, NULL, 1, 42, NULL, 2, '42|44|', 19, 20),
(45, '', 'admin_templates', 42, NULL, NULL, NULL, 0, 42, NULL, 2, '42|45|', 29, 30),
(46, '', 'admin_themes', 42, NULL, NULL, NULL, 1, 42, NULL, 2, '42|46|', 21, 22),
(48, '', 'admin_backup_file', 50, NULL, NULL, NULL, 1, 50, NULL, 3, '42|50|48|', 26, 27),
(49, '', 'admin_backup_db', 50, NULL, NULL, NULL, 1, 50, NULL, 3, '42|50|49|', 24, 25),
(50, '', '', 42, NULL, NULL, NULL, 1, 42, NULL, 2, '42|50|', 23, 28);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories_description`
--

CREATE TABLE IF NOT EXISTS `menu_categories_description` (
  `menu_categories_description_id` int(11) NOT NULL auto_increment,
  `menu_categories_id` int(11) default '0',
  `languages_id` int(11) default '1',
  `menu_categories_name` varchar(32) default NULL,
  `menu_categories_description` text,
  PRIMARY KEY  (`menu_categories_description_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=81 ;

--
-- Dumping data for table `menu_categories_description`
--

INSERT INTO `menu_categories_description` (`menu_categories_description_id`, `menu_categories_id`, `languages_id`, `menu_categories_name`, `menu_categories_description`) VALUES
(32, 34, 1, 'Account Manager', ''),
(33, 35, 1, 'Administrator', ''),
(34, 36, 1, 'Customer', ''),
(35, 37, 1, 'Global Page Layout', ''),
(36, 38, 1, 'Page Manager', ''),
(37, 39, 1, 'View as MULTI-Level Menu', ''),
(38, 40, 1, 'View as ONE-Level Menu', ''),
(39, 41, 1, 'Banners', ''),
(40, 42, 1, 'Configuration', ''),
(41, 43, 1, 'Languages', ''),
(42, 44, 1, 'Website', ''),
(43, 45, 1, 'Templates', ''),
(44, 46, 1, 'Themes', ''),
(46, 48, 1, 'Files', ''),
(47, 49, 1, 'Database', ''),
(48, 34, 2, 'Quản lý Tài khoản', ''),
(49, 34, 3, '계정관리', ''),
(50, 35, 2, 'Quản trị', ''),
(51, 35, 3, '관리자', ''),
(52, 36, 2, 'Khách hàng', ''),
(53, 36, 3, '회원', ''),
(54, 37, 2, 'Bố cục Chung', ''),
(55, 37, 3, '글로벌 페이지 레이아웃', ''),
(56, 38, 2, 'Quản lý Trang', ''),
(57, 38, 3, '페이지관리', ''),
(58, 40, 2, 'Xem theo dạng Menu 1 cấp', ''),
(59, 40, 3, 'View as ONE level Menu', ''),
(60, 39, 2, 'Xem theo dạng Menu đa cấp', ''),
(61, 39, 3, 'View as MPT Menu', ''),
(62, 41, 2, 'Banners', ''),
(63, 41, 3, '배너관리', ''),
(64, 42, 2, 'Cấu hình', ''),
(65, 42, 3, '설정', ''),
(66, 46, 2, 'Chủ đề', ''),
(67, 46, 3, '테마', ''),
(68, 43, 2, 'Ngôn ngữ', ''),
(69, 43, 3, '언어', ''),
(80, 50, 1, 'Backup', ''),
(72, 45, 2, 'Bản mẫu', ''),
(73, 45, 3, 'Templates', ''),
(74, 49, 2, 'Sao lưu Dữ liệu', ''),
(75, 49, 3, '데이터베이스 백업', ''),
(76, 48, 2, 'Sao lưu tập tin', ''),
(77, 48, 3, '파일 백업', ''),
(78, 44, 2, 'Website', ''),
(79, 44, 3, '웹사이트', '');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_title_seo` varchar(255) default NULL,
  `page_keyword` varchar(200) default NULL,
  `page_description` text,
  `page_status` tinyint(1) default '0',
  `page_read_permission` tinyint(1) NOT NULL default '5' COMMENT '1 : Super admin | 2 : Admin | 3 : Staff | 4 : Register | 5 : Everybody',
  `page_write_permission` tinyint(2) NOT NULL default '2' COMMENT '1 : Super admin | 2 : Admin | 3 : Staff | 4 : Register | 5 : Everybody',
  `page_type_id` int(11) NOT NULL,
  `page_level` tinyint(1) NOT NULL,
  `page_left` int(11) NOT NULL,
  `page_right` int(11) NOT NULL,
  `page_order` int(11) NOT NULL,
  `page_target` varchar(200) default NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=167 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`page_id`, `page_title_seo`, `page_keyword`, `page_description`, `page_status`, `page_read_permission`, `page_write_permission`, `page_type_id`, `page_level`, `page_left`, `page_right`, `page_order`, `page_target`) VALUES
(1, NULL, NULL, NULL, 0, 5, 1, 0, 0, 1, 36, 1, NULL),
(2, '', '', '', 1, 5, 1, 7, 1, 4, 5, 0, ''),
(3, NULL, '', '', 1, 5, 1, 8, 1, 10, 11, 0, ''),
(5, NULL, '', '', 1, 5, 1, 12, 1, 32, 33, 0, ''),
(62, NULL, '', '', 1, 5, 1, 15, 1, 14, 15, 0, NULL),
(64, NULL, '', '', 1, 5, 2, 9, 1, 8, 9, 0, ''),
(65, NULL, '', '', 1, 5, 2, 18, 1, 6, 7, 0, ''),
(98, NULL, '', '', 1, 5, 2, 19, 1, 12, 13, 0, ''),
(159, '', '', '', 1, 5, 2, 1, 1, 18, 23, 0, ''),
(160, '', '', '', 1, 5, 2, 1, 2, 21, 22, 0, ''),
(161, '', '', '', 1, 5, 2, 1, 2, 19, 20, 0, ''),
(162, '', '', '', 1, 5, 2, 1, 1, 24, 25, 0, ''),
(163, '', '', '', 1, 5, 2, 11, 1, 30, 31, 0, ''),
(164, '', '', '', 1, 5, 2, 13, 1, 34, 35, 0, '_blank'),
(165, '', '', '', 1, 5, 2, 11, 1, 26, 27, 0, ''),
(166, '', '', '', 1, 5, 2, 11, 1, 28, 29, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `page_description`
--

CREATE TABLE IF NOT EXISTS `page_description` (
  `page_id` int(11) NOT NULL,
  `languages_id` tinyint(11) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `page_description`
--

INSERT INTO `page_description` (`page_id`, `languages_id`, `page_title`, `page_content`) VALUES
(2, 1, 'Home', '<br />'),
(2, 2, 'Trang chủ', '<br />'),
(2, 3, 'Home', NULL),
(3, 1, 'Register', NULL),
(3, 2, 'Đăng ký', '<br />'),
(3, 3, 'Register', NULL),
(5, 1, 'Contact', ''),
(5, 2, 'Liên hệ', '<br />'),
(5, 3, 'Contact', NULL),
(62, 1, 'RSS', '<br />'),
(62, 2, 'RSS', '<br />'),
(62, 3, 'RSS', '<br />'),
(64, 1, 'Login', '<br />'),
(64, 2, 'Đăng nhập', '<br />'),
(64, 3, 'Login', '<br />'),
(65, 1, 'Support', '<br />'),
(65, 2, 'Hỗ trợ', '<br />'),
(65, 3, 'Support', '<br />'),
(132, 1, 'Hình ảnh', ''),
(132, 2, 'Hình ảnh', ''),
(132, 3, 'Hình ảnh', ''),
(98, 1, 'Comment', '<br />'),
(98, 2, 'Comment', '<br />'),
(98, 3, 'Comment', '<br />'),
(159, 1, 'About Us', ''),
(159, 2, 'About Us', ''),
(159, 3, 'About Us', ''),
(160, 1, 'Products', ''),
(160, 2, 'Products', ''),
(160, 3, 'Products', ''),
(161, 1, 'Services', ''),
(161, 2, 'Services', ''),
(161, 3, 'Services', ''),
(162, 1, 'Pest ID', ''),
(162, 2, 'Pest ID', ''),
(162, 3, 'Pest ID', ''),
(163, 1, 'FAQs', ''),
(163, 2, 'FAQs', ''),
(163, 3, 'FAQs', ''),
(164, 1, 'Admin', '/tkadmin'),
(164, 2, 'Admin', '/tkadmin'),
(164, 3, 'Admin', '/tkadmin'),
(165, 1, 'News', ''),
(165, 2, 'News & Blog', ''),
(165, 3, 'News & Blog', ''),
(166, 1, 'Blog', ''),
(166, 2, 'Blog', ''),
(166, 3, 'Blog', '');

-- --------------------------------------------------------

--
-- Table structure for table `page_layout`
--

CREATE TABLE IF NOT EXISTS `page_layout` (
  `page_id` int(11) NOT NULL,
  `center_banner` varchar(200) default NULL,
  `center_top_banner` varchar(200) default NULL,
  `center_bottom_banner` varchar(200) default NULL,
  `center_banner_order` tinyint(1) NOT NULL default '2',
  `content_center_order` tinyint(1) NOT NULL default '1',
  `top_menu` text NOT NULL,
  `top_menu_level` tinyint(1) NOT NULL default '0' COMMENT '0 : multi menu | n : level of menu',
  `center_menu` text NOT NULL,
  `center_menu_level` tinyint(1) NOT NULL default '0' COMMENT '0 : multi menu | n : level of menu',
  `top_banner` varchar(200) default NULL,
  `top_T_banner` varchar(255) default NULL,
  `top_B_banner` varchar(255) default NULL,
  `top_banner_order` tinyint(1) NOT NULL default '2',
  `center_menu_order` tinyint(11) NOT NULL default '1',
  `left_menu` text NOT NULL,
  `left_menu_level` tinyint(1) NOT NULL default '0' COMMENT '0 : multi menu | n : level of menu',
  `left_banner` varchar(200) default NULL,
  `left_T_banner` varchar(255) default NULL,
  `left_B_banner` varchar(255) default NULL,
  `left_banner_order` tinyint(1) NOT NULL default '2',
  `left_menu_order` tinyint(1) NOT NULL default '1',
  `right_menu` text NOT NULL,
  `right_menu_level` tinyint(1) NOT NULL default '0' COMMENT '0 : multi menu | n : level of menu',
  `right_banner` varchar(200) default NULL,
  `right_T_banner` varchar(255) default NULL,
  `right_B_banner` varchar(255) default NULL,
  `right_banner_order` tinyint(1) NOT NULL default '2',
  `right_menu_order` tinyint(1) NOT NULL default '1',
  `bottom_banner` varchar(200) default NULL,
  `bottom_menu` text NOT NULL,
  `bottom_menu_level` tinyint(1) NOT NULL default '0' COMMENT '0 : multi menu | n : level of menu',
  `left_outside_banner` varchar(200) default NULL,
  `right_outside_banner` varchar(200) default NULL,
  `left_col` tinyint(4) NOT NULL COMMENT '1: Enable | 0: Disable',
  `right_col` tinyint(4) NOT NULL COMMENT '1: Enable | 0: Disable'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `page_layout`
--

INSERT INTO `page_layout` (`page_id`, `center_banner`, `center_top_banner`, `center_bottom_banner`, `center_banner_order`, `content_center_order`, `top_menu`, `top_menu_level`, `center_menu`, `center_menu_level`, `top_banner`, `top_T_banner`, `top_B_banner`, `top_banner_order`, `center_menu_order`, `left_menu`, `left_menu_level`, `left_banner`, `left_T_banner`, `left_B_banner`, `left_banner_order`, `left_menu_order`, `right_menu`, `right_menu_level`, `right_banner`, `right_T_banner`, `right_B_banner`, `right_banner_order`, `right_menu_order`, `bottom_banner`, `bottom_menu`, `bottom_menu_level`, `left_outside_banner`, `right_outside_banner`, `left_col`, `right_col`) VALUES
(1, '', '', '', 2, 1, '2|159|161|160|162|165|166|163|5', 0, '', 0, '20|', '', '1|2|', 1, 2, '', 0, '', '', '', 2, 1, '', 0, '24|', '', '', 1, 2, '', '2|164', 1, '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_type`
--

CREATE TABLE IF NOT EXISTS `page_type` (
  `page_type_name` varchar(50) NOT NULL,
  `page_type_id` int(11) NOT NULL auto_increment,
  `page_type_special` tinyint(1) NOT NULL default '0' COMMENT '0 : normal | 1 : Special',
  `page_type_status` tinyint(1) NOT NULL default '1' COMMENT '1 : active | 0 : inactive',
  PRIMARY KEY  (`page_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=20 ;

--
-- Dumping data for table `page_type`
--

INSERT INTO `page_type` (`page_type_name`, `page_type_id`, `page_type_special`, `page_type_status`) VALUES
('general', 1, 0, 1),
('album', 3, 0, 1),
('contact', 12, 1, 1),
('home', 7, 1, 1),
('register', 8, 1, 1),
('login', 9, 1, 1),
('blog', 10, 0, 1),
('bbs', 11, 0, 1),
('menu', 13, 0, 1),
('news', 14, 0, 1),
('rss', 15, 1, 1),
('form', 16, 0, 1),
('tab', 17, 0, 1),
('support', 18, 1, 1),
('comment', 19, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `permissions_id` int(11) NOT NULL auto_increment,
  `permissions_code` varchar(255) NOT NULL,
  `permissions_name` varchar(255) NOT NULL,
  `permissions_status` int(1) default '1' COMMENT '1:active|0:inactive',
  `permissions_order` int(11) NOT NULL,
  PRIMARY KEY  (`permissions_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permissions_id`, `permissions_code`, `permissions_name`, `permissions_status`, `permissions_order`) VALUES
(1, 'admin_customer', 'Admin Customers', 1, 0),
(2, 'admin_banner', 'Admin Banners', 1, 0),
(3, 'admin_page', 'Admin Pages', 1, 0),
(4, 'admin_layout', 'Admin Layout', 1, 0),
(5, 'admin_config', 'Admin Config', 1, 0),
(6, 'admin_home', 'Admin Home', 1, 0),
(7, 'admin_contact', 'Admin Contact', 1, 0),
(8, 'admin_support', 'Admin Support', 1, 0),
(9, 'admin_comment', 'Admin Comment', 1, 0),
(10, 'admin_news', 'Admin News', 1, 0),
(11, 'admin_blog', 'Admin Blog', 1, 0),
(12, 'admin_album', 'Admin Album', 1, 0),
(13, 'admin_bbs', 'Admin BBS', 1, 0),
(14, 'admin_tab', 'Admin Tab', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL auto_increment,
  `role_name` varchar(255) default NULL,
  `role_status` int(1) NOT NULL default '1' COMMENT '1:active|0:inactive',
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_status`) VALUES
(1, 'Administrator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_perms`
--

CREATE TABLE IF NOT EXISTS `role_perms` (
  `role_perms_id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `role_perms_value` text COMMENT '1:All | 2:View | 3:Add | 4:Edit | 5:Delete',
  PRIMARY KEY  (`role_perms_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=266 ;

--
-- Dumping data for table `role_perms`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(127) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `last_activity`, `data`) VALUES
('fa6fefe91c91db34714ddaae4f2f0da5', 1350032978, 'c2Vzc2lvbl9pZHxzOjMyOiJmYTZmZWZlOTFjOTFkYjM0NzE0ZGRhYWU0ZjJmMGRhNSI7dG90YWxfaGl0c3xpOjIzO19rZl9mbGFzaF98YTowOnt9dXNlcl9hZ2VudHxzOjk5OiJNb3ppbGxhLzUuMCAoV2luZG93cyBOVCA2LjEpIEFwcGxlV2ViS2l0LzUzNy40IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzIyLjAuMTIyOS45NCBTYWZhcmkvNTM3LjQiO2lwX2FkZHJlc3N8czo5OiIxMjcuMC4wLjEiO2xhc3RfYWN0aXZpdHl8aToxMzUwMDMyOTc1O3Nlc3NfaGlzX2NsaWVudHxhOjI6e3M6NDoiYmFjayI7czoxMToiYmJzL3BpZC8xNjUiO3M6NzoiY3VycmVudCI7czo0OiJob21lIjt9c2Vzc19oaXNfYWRtaW58YToyOntzOjQ6ImJhY2siO3M6MTM6ImFkbWluX2FjY291bnQiO3M6NzoiY3VycmVudCI7czoxMjoiYWRtaW5fY29uZmlnIjt9c2Vzc19hZG1pbl9sYW5nfHM6MToiMSI7c2Vzc19yb2xlfE47c2Vzc19hZG1pbnxhOjU6e3M6MjoiaWQiO3M6MToiMSI7czo1OiJsZXZlbCI7czoxOiIxIjtzOjg6InVzZXJuYW1lIjtzOjEwOiJzdXBlcmFkbWluIjtzOjU6ImVtYWlsIjtzOjIwOiJsZW9AdGVjaGtub3dsZWRnZS52biI7czo0OiJyb2xlIjtzOjE6IjAiO30='),
('5e0ca835b9c18e380cb336188ce4368a', 1350363437, 'c2Vzc2lvbl9pZHxzOjMyOiI1ZTBjYTgzNWI5YzE4ZTM4MGNiMzM2MTg4Y2U0MzY4YSI7dG90YWxfaGl0c3xpOjU5O19rZl9mbGFzaF98YTowOnt9dXNlcl9hZ2VudHxzOjk5OiJNb3ppbGxhLzUuMCAoV2luZG93cyBOVCA2LjEpIEFwcGxlV2ViS2l0LzUzNy40IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzIyLjAuMTIyOS45NCBTYWZhcmkvNTM3LjQiO2lwX2FkZHJlc3N8czoxMToiMS41NC4yMzcuMzAiO2xhc3RfYWN0aXZpdHl8aToxMzUwMzYzNDM3O3Nlc3NfaGlzX2FkbWlufGE6Mjp7czo0OiJiYWNrIjtzOjE1OiJhZG1pbl9iYWNrdXBfZGIiO3M6NzoiY3VycmVudCI7czoxNzoiYWRtaW5fYmFja3VwX2ZpbGUiO31zZXNzX2FkbWluX2xhbmd8czoxOiIxIjtzZXNzX3JvbGV8TjtzZXNzX2FkbWlufGE6NTp7czoyOiJpZCI7czoxOiIxIjtzOjU6ImxldmVsIjtzOjE6IjEiO3M6ODoidXNlcm5hbWUiO3M6MTA6InN1cGVyYWRtaW4iO3M6NToiZW1haWwiO3M6MjA6Imxlb0B0ZWNoa25vd2xlZGdlLnZuIjtzOjQ6InJvbGUiO3M6MToiMCI7fQ==');

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `site_id` tinyint(2) NOT NULL auto_increment,
  `site_name` varchar(255) NOT NULL,
  `site_address` varchar(255) NOT NULL,
  `site_city` varchar(255) NOT NULL,
  `site_state` varchar(255) NOT NULL,
  `site_zipcode` varchar(255) NOT NULL,
  `site_logo` text,
  `site_logo_width` int(5) NOT NULL,
  `site_logo_height` int(5) NOT NULL,
  `site_phone` varchar(255) NOT NULL,
  `site_fax` varchar(255) default NULL,
  `site_slogan` varchar(255) default NULL,
  `site_email` varchar(255) default NULL,
  `site_title` varchar(255) default NULL,
  `site_keyword` varchar(255) default NULL,
  `site_description` text,
  `site_contact_name` text,
  `site_facebook` text,
  `site_twitter` text,
  `site_youtube` text,
  `site_linkedin` text,
  PRIMARY KEY  (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`site_id`, `site_name`, `site_address`, `site_city`, `site_state`, `site_zipcode`, `site_logo`, `site_logo_width`, `site_logo_height`, `site_phone`, `site_fax`, `site_slogan`, `site_email`, `site_title`, `site_keyword`, `site_description`, `site_contact_name`, `site_facebook`, `site_twitter`, `site_youtube`, `site_linkedin`) VALUES
(1, 'A&K Computers Inc.', '440 North Wolfe Rd.', 'Sunnyvale', 'CA', '94085', 'logo_mch.png', 97, 99, '1-408-244-4811', '1-408-493-4426', 'Computer experts for today''s PMP', 'webmaster@akcomp.com', 'A&K Computers Inc', 'Pest Control Company / Termite control Company', 'Pest Control Company / Termite control Company', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `support_id` int(3) NOT NULL auto_increment,
  `support_name` varchar(255) default NULL,
  `support_nick` varchar(255) default NULL,
  `support_type` tinyint(2) default '1' COMMENT '1:yahoo, 2:skype, 3:hotline',
  `support_sort_order` int(11) default NULL,
  `support_status` tinyint(2) default NULL,
  PRIMARY KEY  (`support_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=11 ;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`support_id`, `support_name`, `support_nick`, `support_type`, `support_sort_order`, `support_status`) VALUES
(8, 'leo', 'leo.techknowledge', 2, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `templates_id` int(11) NOT NULL auto_increment,
  `templates_name` varchar(255) NOT NULL,
  `templates_dir` text NOT NULL,
  `templates_order` int(11) NOT NULL,
  `templates_status` tinyint(5) NOT NULL,
  PRIMARY KEY  (`templates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`templates_id`, `templates_name`, `templates_dir`, `templates_order`, `templates_status`) VALUES
(1, 'MCH', 'mch', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `themes_id` int(11) NOT NULL auto_increment,
  `themes_name` varchar(255) default NULL,
  `themes_dir` varchar(255) default NULL,
  `themes_sort_order` int(11) default NULL,
  `themes_status` tinyint(4) default NULL,
  PRIMARY KEY  (`themes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`themes_id`, `themes_name`, `themes_dir`, `themes_sort_order`, `themes_status`) VALUES
(1, 'Style 1', 'styleMCH1', NULL, 1),
(2, 'Style 2', 'styleMCH2', NULL, 1),
(3, 'Style 3', 'styleMCH3', NULL, 1),
(4, 'Style 4', 'styleMCH4', NULL, 1),
(5, 'Style 5', 'styleMCH5', NULL, 1),
(6, 'Style 6', 'styleMCH6', NULL, 1),
(7, 'Style 7', 'styleMCH7', NULL, 1),
(8, 'Style 8', 'styleMCH8', NULL, 1),
(9, 'Style 9', 'styleMCH9', NULL, 1),
(10, 'Style 10', 'styleMCH10', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(200) NOT NULL,
  `user_pass` text NOT NULL,
  `user_email` text NOT NULL,
  `user_level` tinyint(5) NOT NULL COMMENT '1 : super admin | 2 : admin | 3 : staff | 4 : registered',
  `user_role` text,
  `user_status` tinyint(5) NOT NULL,
  `user_reg_date` int(11) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=28 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_pass`, `user_email`, `user_level`, `user_role`, `user_status`, `user_reg_date`) VALUES
(1, 'superadmin', 'e10adc3949ba59abbe56e057f20f883e', 'leo@techknowledge.vn', 1, '0', 1, 0),
(2, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'leo.techknowledge@gmail.com', 2, '1', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `version_id` tinyint(1) NOT NULL auto_increment,
  `app_name` varchar(200) NOT NULL,
  `url_server_update` varchar(50) NOT NULL,
  `xml_list_file` varchar(200) NOT NULL,
  `cur_version` varchar(20) NOT NULL,
  PRIMARY KEY  (`version_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `version`
--

INSERT INTO `version` (`version_id`, `app_name`, `url_server_update`, `xml_list_file`, `cur_version`) VALUES
(1, 'mch', 'http://project.tikay.net/update/', 'list_versions.xml', '2011b');
