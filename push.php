<?php
header("Content-Type: text/html; charset=UTF-8");
//이중 로그인과 db정보 연동을 위한 파일 로딩2013-08-07
   	define('__ZBXE__', true);
	include("../files/config/db.config.php");
	$dbname=$db_info->master_db['db_userid'];
	$dbpass=$db_info->master_db['db_password'];
class DBConnection{
	function getConnection($dbname,$dbpass){
	  //change to your database server/user name/password
		mysql_connect("localhost",$dbname,$dbpass) or
         die("Could not connect: " . mysql_error());
    //change to your database name
		mysql_select_db($dbname) or 
		     die("Could not select database: " . mysql_error());
	}
}
    $db = new DBConnection();
    $db->getConnection($dbname,$dbpass);
    
    $rc_sql = "INSERT INTO `toto_ClinicCode` (`id`, `CLNC_CODE`, `CLNC_KORA`, `CLNC_ENGL`, `CLST_CODE`, `SORT_NUMB`, `USE__FLAG`, `UPDT_DATE`, `UPDT_USER`, `UPDT_IPAD`) VALUES
(1, '0100', '레이져류', '', '', 1, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(2, '0101', '레이저-탄산가스', 'Laser-CO2', '0103', 2, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(3, '0102', '레이저-어븀', 'Laser-Erbium laser', '0103', 3, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(4, '0103', '레이저-엔디야그', 'Laser-Nd-YAG', '0104', 4, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(5, '0104', '레이저-알렉스', 'Laser-Alex', '0104', 5, '0', '2010-02-11 11:22:52', 332, '192.168.0.30'),
(6, '0105', '레이저-브이빔', 'Laser-V-beam', '0105', 6, '0', '2010-02-11 11:19:29', 332, '192.168.0.30'),
(7, '0106', '레이저-브이스타', 'Laser-V-star', '0105', 7, '0', '2010-02-11 11:19:34', 332, '192.168.0.30'),
(8, '0107', '레이저-쿨터치', 'Laser-Cool touch', '0106', 8, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(9, '0108', '레이저-제오', 'Laser-Xeo', '', 9, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(10, '0109', '레이저-헬륨네온', 'Laser-He-Ne', '0112', 10, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(11, '0110', '레이저-아포지', 'Laser-Apogee', '0107', 11, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(12, '0111', '레이저-다이오드', 'Laser-Diode', '0107', 12, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(13, '0112', '레이저-쿨글라이드', 'Laser-Cool glide', '0107', 13, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(14, '0113', 'A-Tone(IPL)', 'A-Tone(IPL)', '0104', 14, '1', '2011-06-30 18:09:50', 332, '192.168.0.77'),
(15, '0114', '고바야시', 'Kobayashi', '0107', 15, '0', '2007-01-25 09:41:55', 332, '211.56.250.35'),
(16, '0115', '고바야시-다한증', 'Kobayashi-Ecc', '0108', 16, '1', '2007-01-25 09:15:29', 332, '211.56.250.35'),
(17, '0116', '레이저-케이티피', 'Laser-KTP', '', 17, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(18, '0117', '레이저-벤테이지', 'Laser-Vantage', '', 18, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(19, '0118', '레이저-울트라펄스', 'Laser-Ultrapulse', '0106', 19, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(20, '0119', '레이저-로우벨파로', 'Laser-Low belpharo', '', 20, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(21, '0120', '레이저-제오(제네시스)', 'Laser-Xeo(Genesis)', '0101', 0, '0', '2004-01-13 08:34:42', 332, '211.232.172.130'),
(22, '0121', '레이저-제오(혈관)', 'Laser-Xeo(angioma)', '0105', 0, '0', '2004-01-13 08:34:55', 332, '211.232.172.130'),
(23, '0122', '레이저-제오(제모)', 'Laser-Xeo(Hypertrichosis)', '0107', 0, '0', '2004-01-13 08:35:10', 332, '211.232.172.130'),
(24, '0123', '레이저-제오(IPL)', 'Laser-Xeo(IPL)', '0104', 0, '0', '2004-01-13 08:35:25', 332, '211.232.172.130'),
(25, '0124', '레이저-클리너', 'Laser-Cleaner', '0109', 0, '0', '2010-02-11 11:32:20', 332, '192.168.0.30'),
(26, '0125', 'Gemini', 'Gemini', '0105', 0, '0', '2006-11-17 11:00:10', 332, '211.56.250.35'),
(27, '0126', 'MTS', 'MTS', '0106', 0, '1', '2006-11-13 13:19:06', 332, '211.56.250.35'),
(28, '0127', 'Lumenis lone', 'Lumenis lone', '0104', 0, '0', '2006-11-13 13:28:13', 332, '211.56.250.35'),
(29, '0128', '고바야시-액취증(제모)', 'Kobayashi-액취증(제모)', '0107', 0, '1', '2007-01-25 09:40:55', 332, '211.56.250.35'),
(30, '0129', '고바야시-블렉헤드', 'Kobayashi-블렉헤드', '0109', 0, '1', '2007-01-25 09:14:37', 332, '211.56.250.35'),
(31, '0130', '고바야시-여드름', 'Kobayashi-여드름', '0109', 0, '1', '2007-01-25 09:14:52', 332, '211.56.250.35'),
(32, '0131', '케이 레이저', 'K-Laser', '0109', 0, '0', '2007-07-04 15:05:30', 332, '211.56.250.35'),
(33, '0132', '레이저-시너지', 'Laser-cynergy', '0105', 0, '0', '2010-02-09 11:34:47', 332, '192.168.0.6'),
(34, '0133', '레이저-이맥스', 'Laser-Emax', '0101', 0, '0', '2010-02-08 10:01:34', 332, '192.168.0.6'),
(35, '0134', '마스터펄스', 'masterpuls', '0111', 0, '1', '2007-08-04 10:34:58', 332, '211.56.250.35'),
(36, '0135', '프렉셔날 S(FS) ', '프렉셔날 S(FS) ', '0106', 0, '1', '2010-02-11 11:04:06', 332, '192.168.0.30'),
(37, '0136', '옐로우 레이저', 'Dual Yellow Laser', '0104', 0, '0', '2007-12-24 14:15:04', 332, '211.56.250.35'),
(38, '0137', 'co2 프랙셔날', 'cofrax', '0106', 0, '0', '2008-08-05 14:34:36', 332, '211.56.250.35'),
(39, '0138', 'Light sheer', 'Light sheer', '', 0, '1', '2008-08-05 14:39:46', 332, '211.56.250.35'),
(40, '0139', '펄레이저', '', '', 0, '0', '2008-10-13 13:20:37', 332, '211.56.250.35'),
(41, '0140', '에어젠트', '', '', 0, '0', '2010-02-11 11:26:59', 332, '192.168.0.30'),
(42, '0141', '베리라이트', 'verylight', '', 0, '0', '2008-11-27 17:36:42', 332, '211.56.250.35'),
(43, '0142', 'BBL', 'BBL', '', 0, '0', '2009-03-11 09:52:27', 332, '211.56.250.35'),
(44, '0143', '메디오스타', '메디오스타', '', 0, '0', '2009-04-10 10:10:33', 332, '211.56.250.35'),
(45, '0144', 'Criocell', 'Criocell', '', 0, '1', '2009-04-20 10:52:03', 332, '211.56.250.35'),
(46, '0145', 'MCL', 'MCL', '', 0, '1', '2009-05-06 09:52:14', 332, '211.56.250.35'),
(47, '0146', '프렉셔날 SW(FSW)', '', '0106', 0, '1', '2010-02-11 11:04:57', 332, '192.168.0.30'),
(48, '0147', '프렉셔날 SR(FSR)', '', '0106', 0, '1', '2010-02-11 11:05:26', 332, '192.168.0.30'),
(49, '0148', '프렉셔날 SC(FSC)', '', '0106', 0, '1', '2010-02-11 11:05:43', 332, '192.168.0.30'),
(50, '0149', '인트라셀', 'Intracel', '0106', 0, '1', '2010-05-25 20:20:23', 332, '192.168.0.18'),
(51, '0150', '제네시스 케어', '', '', 0, '1', '2010-05-25 20:20:33', 332, '192.168.0.18'),
(52, '0151', '제네시스(롱펄스)', '', '', 0, '1', '2012-03-09 13:40:57', 332, '192.168.0.77'),
(53, '0152', '엘빔', '', '', 0, '1', '2012-03-09 13:58:42', 332, '192.168.0.77'),
(54, '0153', '메트릭스', '', '', 0, '1', '2012-03-09 13:58:45', 332, '192.168.0.77'),
(55, '0154', '트리악 케어', '', '', 0, '1', '2012-12-06 19:18:26', 332, '192.168.0.10'),
(56, '0155', '에보킨', '', '', 0, '1', '2013-05-24 12:49:35', 332, '192.168.0.101'),
(57, '0200', '스케일링류', '', '', 21, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(58, '0201', '스케일링1-여드름', 'Scaling 1', '0109', 22, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(59, '0202', '스케일링2-보습', 'Scaling 2', '0109', 23, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(60, '0203', '스케일링3-예민', 'Scaling 3', '0109', 24, '0', '2010-02-08 10:04:43', 332, '192.168.0.6'),
(61, '0204', '아미노필', 'Amino peel', '0110', 25, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(62, '0205', '크리스탈필', 'Crystal peel', '0109', 26, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(63, '0206', '크리스탈필-아미노필', 'Crystal peel-Amino peel', '', 27, '0', '2004-02-17 09:11:17', 332, '211.232.172.130'),
(64, '0207', '다이아몬드필', 'Diamond peel', '0110', 28, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(65, '0208', '다이아몬드필-아미노필', 'Diamond peel-Amino peel', '', 29, '0', '2004-02-17 09:11:22', 332, '211.232.172.130'),
(66, '0209', '위켄드 필', 'Weekend peel', '0110', 30, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(67, '0210', '해초박피', 'Deep sea herb peel', '0110', 31, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(68, '0211', '딥필', 'Deep peel', '0101', 32, '0', '2008-12-02 16:58:06', 887, '211.56.250.36'),
(69, '0212', '비타민C 필', 'VitaminC peel', '0110', 33, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(70, '0213', '클레이 필', 'Clay peel', '0110', 96, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(71, '0214', '포지티브(솔트) 필', 'Salt peel', '0109', 97, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(72, '0215', '산소필', 'Oxy peel', '0110', 0, '0', '2004-06-17 17:53:12', 332, '211.232.172.161'),
(73, '0216', '필링이온자임', 'peeling Ionzyme', '0110', 0, '0', '2004-06-17 17:54:07', 332, '211.232.172.161'),
(74, '0217', '살리실릭 필', 'Salycilic peel', '0109', 0, '1', '2004-06-28 09:28:15', 332, '211.232.172.190'),
(75, '0218', '타임리스필', 'Timeles peel', '0110', 0, '0', '2005-04-29 10:18:00', 332, '211.232.172.161'),
(76, '0219', '알라딘필', 'Aladin peel', '0110', 0, '0', '2005-10-10 14:54:42', 332, '211.232.172.161'),
(77, '0220', '이지필', 'Easy peel', '0110', 0, '1', '2010-03-16 12:06:52', 332, '192.168.0.130'),
(78, '0221', '카복시필', 'Carboxy peel', '0110', 0, '0', '2005-10-10 14:53:54', 332, '211.232.172.161'),
(79, '0222', '오바지 필', 'Obagi peel', '0110', 0, '0', '2006-07-03 09:35:41', 332, '211.56.250.35'),
(80, '0223', '포토스케일링', '', '', 0, '0', '2010-02-11 11:33:36', 332, '192.168.0.30'),
(81, '0224', '해피필', 'Happy peel', '0110', 0, '1', '2009-07-24 18:32:55', 332, '211.56.250.35'),
(82, '0225', '레이저 필', 'Laser peel', '', 0, '1', '2010-03-16 12:12:21', 332, '192.168.0.130'),
(83, '0226', 'A peel', 'A peel', '', 0, '1', '2011-05-12 16:11:23', 332, '192.168.0.19'),
(84, '0227', 'W peel', 'W peel', '', 0, '1', '2011-05-12 16:11:30', 332, '192.168.0.19'),
(85, '0300', '일반관리류', '', '', 34, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(86, '0301', '에피큐렌', 'Epicuren', '0110', 35, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(87, '0302', '바이탈 1-미백치료', 'Vitaliont  1', '0110', 36, '1', '2010-02-08 10:32:42', 332, '192.168.0.6'),
(88, '0303', '바이탈 2-보습치료', 'Vitaliont  2', '0110', 37, '0', '2010-02-08 10:32:47', 332, '192.168.0.6'),
(89, '0304', '바이탈 3-여드름치료', 'Vitaliont  3', '0110', 38, '1', '2010-02-08 10:32:51', 332, '192.168.0.6'),
(90, '0305', '바이탈 4-재생치료', 'Vitaliont  4', '0110', 39, '0', '2010-02-08 10:32:54', 332, '192.168.0.6'),
(91, '0306', '이온자임1-미백', 'Ionzyme1', '0110', 40, '1', '2008-11-03 14:57:19', 332, '211.56.250.35'),
(92, '0307', '태반1-진정치료', 'Placenta  1', '0109', 41, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(93, '0308', '태반2-보습치료', 'Placenta  2', '0109', 42, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(94, '0309', '태반3-여드름치료', 'Placenta  3', '0109', 43, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(95, '0310', '옥시젯', 'Oxyget', '0110', 44, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(96, '0311', '스킨마스터', 'Skin master', '0110', 45, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(97, '0312', 'M2-펩타이드', 'M2-peptide', '0110', 0, '0', '2010-02-08 10:03:14', 332, '192.168.0.6'),
(98, '0313', '메조테라피', 'Mesotherapy', '0104', 0, '1', '2004-01-09 09:36:48', 332, '211.232.172.130'),
(99, '0314', '피브로인', 'Fibroin', '', 0, '0', '2004-02-19 11:04:44', 332, '211.232.172.130'),
(100, '0315', '에너지플러스', 'Energy plus', '0111', 0, '1', '2004-09-22 13:10:50', 332, '211.232.172.161'),
(101, '0316', '큐라', 'CURA', '0110', 0, '0', '2006-03-20 16:11:18', 404, '211.232.172.161'),
(102, '0317', 'BTX', 'BTX', '0110', 0, '0', '2006-03-24 16:25:52', 404, '211.232.172.161'),
(103, '0318', '이온자임3-여드름', 'Ionzyme3', '0110', 0, '0', '2010-02-08 10:04:23', 332, '192.168.0.6'),
(104, '0319', '프리미엄 바이탈1-미백치료', 'P-vital 1', '0110', 0, '0', '2007-12-24 14:06:33', 332, '211.56.250.35'),
(105, '0320', '프리미엄 바이탈2-보습치료', 'P-vital 2', '0110', 0, '0', '2007-12-24 14:06:40', 332, '211.56.250.35'),
(106, '0321', '프리미엄 바이탈3-여드름치료', 'P-vital 3', '0110', 0, '0', '2007-12-24 14:06:49', 332, '211.56.250.35'),
(107, '0322', '프리미엄 바이탈4-재생치료', 'P-vital 4', '0110', 0, '0', '2007-12-24 14:06:57', 332, '211.56.250.35'),
(108, '0323', '프리미엄 이온자임1-미백치료', 'P-lonzyme 1', '0110', 0, '0', '2007-12-24 14:07:10', 332, '211.56.250.35'),
(109, '0324', '프리미엄 이온자임2-보습치료', 'P-Ionzyme 2', '0110', 0, '0', '2007-12-24 14:07:29', 332, '211.56.250.35'),
(110, '0325', '아이 레이스', 'Eye Lace', '0110', 0, '1', '2007-12-24 14:07:49', 332, '211.56.250.35'),
(111, '0326', '아이 마스터', 'Eye Master', '0110', 0, '1', '2007-12-24 14:07:58', 332, '211.56.250.35'),
(112, '0327', '스팟 클리어', 'Spot Clear', '0110', 0, '1', '2007-12-24 14:08:05', 332, '211.56.250.35'),
(113, '0328', '임산부 이온자임', 'Preg. Ionzyme', '0110', 0, '1', '2007-12-24 14:08:13', 332, '211.56.250.35'),
(114, '0329', '임산부 옥시젯', 'Preg. Oxyjet', '0110', 0, '1', '2007-12-24 14:08:22', 332, '211.56.250.35'),
(115, '0330', 'BeCS 1-미백', 'BeCS 1', '0101', 0, '1', '2008-11-27 17:46:31', 332, '211.56.250.35'),
(116, '0331', 'BeCS 2-보습', 'BeCS 2', '0101', 0, '1', '2008-11-27 17:46:44', 332, '211.56.250.35'),
(117, '0332', 'BeCS 3-노화', 'BeCS 3', '0101', 0, '1', '2009-07-24 18:38:29', 332, '211.56.250.35'),
(118, '0333', '디디에스 임플란트', 'DDS implant', '', 0, '1', '2009-07-24 18:24:11', 332, '211.56.250.35'),
(119, '0334', '프로폴리스', 'Propolice', '0112', 0, '1', '2009-07-24 18:30:49', 332, '211.56.250.35'),
(120, '0335', '클라이오', 'cryo thelaphy', '0112', 0, '1', '2009-07-24 18:36:16', 332, '211.56.250.35'),
(121, '0336', '알러지바이탈', 'allergy vital', '0112', 0, '1', '2009-12-07 14:51:50', 332, '192.168.0.15'),
(122, '0337', 'BeCs Mask', 'BeCs Mask', '0101', 0, '1', '2010-02-08 10:15:42', 332, '192.168.0.6'),
(123, '0338', '콜라겐 젤 마스크', '', '0110', 0, '1', '2010-02-08 10:15:55', 332, '192.168.0.6'),
(124, '0339', 'EGF-1', 'EGF-1', '0110', 0, '1', '2010-02-08 10:21:51', 332, '192.168.0.6'),
(125, '0340', 'EGF-3', 'EGF-3', '0109', 0, '1', '2010-02-08 10:22:03', 332, '192.168.0.6'),
(126, '0341', 'Pla -1', 'Pla -1', '0110', 0, '1', '2010-02-08 10:22:30', 332, '192.168.0.6'),
(127, '0342', 'Pla -3', 'Pla -3', '0109', 0, '1', '2010-02-08 10:22:37', 332, '192.168.0.6'),
(128, '0343', '알러지-E', '', '0112', 0, '1', '2010-02-08 10:22:51', 332, '192.168.0.6'),
(129, '0344', '알러지-P', '', '0112', 0, '1', '2010-02-08 10:22:58', 332, '192.168.0.6'),
(130, '0345', '시술후 관리', '', '0110', 0, '1', '2010-05-25 20:23:02', 332, '192.168.0.18'),
(131, '0346', 'PGA Mask', 'PGA Mask', '', 0, '1', '2010-07-16 17:02:18', 332, '192.168.0.18'),
(132, '0347', '울트라셀1', '', '', 0, '1', '2012-03-09 14:15:37', 332, '192.168.0.77'),
(133, '0348', '울트라셀2', '', '', 0, '1', '2012-03-09 14:15:41', 332, '192.168.0.77'),
(134, '0349', 'vita PDT', '', '', 0, '1', '2012-03-09 14:23:54', 332, '192.168.0.77'),
(135, '0350', '엘세보', '', '', 0, '1', '2012-03-09 14:25:53', 332, '192.168.0.77'),
(136, '0351', '세보', '', '', 0, '1', '2012-03-09 14:25:56', 332, '192.168.0.77'),
(137, '0352', '사해마스크', '', '', 0, '1', '2012-08-14 11:08:33', 332, '192.168.0.101'),
(138, '0353', '오가닉', '', '', 0, '1', '2012-11-07 16:29:49', 332, '192.168.0.7'),
(139, '0354', 'JK EGF', 'JK EGF', '', 0, '1', '2013-02-18 12:19:15', 332, '192.168.100.11'),
(140, '0355', 'FSL 엘케어', 'FSL 엘케어', '', 0, '1', '2013-02-18 12:19:30', 332, '192.168.100.11'),
(141, '0356', 'LUX 케어', 'LUX 케어', '', 0, '1', '2013-02-18 12:19:56', 332, '192.168.100.11'),
(142, '0357', '에보킨케어', '에보킨케어', '', 0, '1', '2013-05-24 12:49:18', 332, '192.168.0.101'),
(143, '0358', '학생FSL엘', '학생FSL엘', '', 0, '1', '2013-02-18 12:21:08', 332, '192.168.100.11'),
(144, '0359', 'A-JK EGF', 'A-JK EGF', '', 0, '1', '2013-05-24 13:11:40', 332, '192.168.0.101'),
(145, '0360', '모델링마스크', '', '', 0, '1', '2013-05-24 14:08:33', 332, '192.168.0.101'),
(146, '0400', '질환관리류', '', '', 46, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(147, '0401', '아이알(IR)', 'InfraRed', '0112', 47, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(148, '0402', '알러지치료1-심층', 'Allergy tx. 1', '0110', 48, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(149, '0403', '알러지치료2-일반', 'Allergy tx. 2', '0110', 49, '0', '2010-02-08 10:00:36', 332, '192.168.0.6'),
(150, '0404', '여드름치료2-민감', 'Acne tx. 2', '0109', 50, '0', '2010-02-08 10:00:44', 332, '192.168.0.6'),
(151, '0405', '여드름치료3-일반', 'Acne tx. 3', '0109', 51, '0', '2010-02-08 10:00:46', 332, '192.168.0.6'),
(152, '0406', '여드름치료5-중증', 'Acne tx. 5', '0109', 52, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(153, '0407', '압출', 'Extraction', '0109', 53, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(154, '0408', '벨벳마스크', 'Velvet mask', '0110', 54, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(155, '0409', '벨벳처치', 'Velvet tx.', '0110', 55, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(156, '0410', '스킨케어1-집중', 'Skin care 1', '0110', 56, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(157, '0411', '스킨케어2-일반', 'Skin care 2', '0110', 57, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(158, '0412', '두피치료', 'Scalp tx.', '0112', 58, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(159, '0413', 'Only touch', 'Only touch', '0104', 0, '1', '2006-10-18 09:23:25', 332, '211.56.250.35'),
(160, '0414', '알러지치료3-보습', 'Allergy skin care 3', '0110', 0, '0', '2008-11-03 14:58:19', 332, '211.56.250.35'),
(161, '0415', '카복시', 'Carboxy', '', 0, '0', '2008-10-13 13:25:27', 332, '211.56.250.35'),
(162, '0416', '포어덤', '포어덤', '', 0, '0', '2009-05-06 09:44:50', 332, '211.56.250.35'),
(163, '0417', '닥터 PGA I(노화)', '', '', 0, '1', '2010-07-14 19:14:57', 332, '192.168.0.18'),
(164, '0418', '닥터 PGA II(색소)', '', '', 0, '1', '2010-07-14 19:15:05', 332, '192.168.0.18'),
(165, '0419', '엘케어', 'el Care', '', 0, '1', '2011-04-14 11:10:51', 332, '192.168.0.21'),
(166, '0420', '엘 화이트 케어', 'el white Care', '', 0, '1', '2011-04-14 11:11:34', 332, '192.168.0.21'),
(167, '0421', '엘 바이탈케어', 'el vital care', '', 0, '1', '2011-04-14 11:12:16', 332, '192.168.0.21'),
(168, '0422', '엘 토닝', 'el tuning', '', 0, '1', '2011-04-14 11:18:33', 332, '192.168.0.21'),
(169, '0423', '루치온주사', '', '', 0, '1', '2012-11-07 16:26:55', 332, '192.168.0.7'),
(170, '0424', '미수금', '', '', 0, '1', '2012-11-07 16:34:42', 332, '192.168.0.7'),
(171, '0425', '비타민D주사', '', '', 0, '1', '2012-12-06 19:20:41', 332, '192.168.0.10'),
(172, '0426', '대상포진백신', '', '', 0, '1', '2012-12-06 19:20:48', 332, '192.168.0.10'),
(173, '0427', '메조리프트', '', '', 0, '1', '2013-05-24 14:01:30', 332, '192.168.0.101'),
(174, '0428', '히알라아제', '', '', 0, '1', '2013-05-24 14:01:35', 332, '192.168.0.101'),
(175, '0500', '처치치료류', '', '', 59, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(176, '0501', '보톡스', 'Botox', '0102', 60, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(177, '0502', '''레스틸렌, 펄레인''', '''Restylene, Perlane''', '0102', 61, '0', '2011-01-20 15:31:26', 332, '192.168.0.19'),
(178, '0503', '알로덤', 'Aloderm', '0102', 62, '0', '2007-01-23 13:26:30', 332, '211.56.250.35'),
(179, '0504', '메타크릴', 'Metachril', '0102', 63, '0', '2007-01-23 13:26:23', 332, '211.56.250.35'),
(180, '0505', '모발이식술', 'Hair transplantation', '0108', 64, '0', '2010-02-11 11:34:01', 332, '192.168.0.30'),
(181, '0506', '반영구화장', 'Semi-permanent make-up', '0112', 65, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(182, '0507', '자가지방이식술', 'Autologous fat injection', '0108', 66, '0', '2010-02-11 11:30:25', 332, '192.168.0.30'),
(183, '0508', '레이저박피술', 'Resurfacing', '0106', 67, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(184, '0509', '제스너필링', 'Jessner peeling', '0109', 68, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(185, '0510', '레이저 눈밑지방 제거술', 'Laser infraorbital fat reducti', '0108', 69, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(186, '0511', '도트필링(TCA)', 'Dot peeling(TCA)', '0106', 70, '1', '2004-01-29 18:23:55', 329, '211.232.172.169'),
(187, '0512', '병변내주사 및 압출', 'ILI with extraction', '0109', 71, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(188, '0513', '레이저 후 압출', 'CO2 extraction', '0101', 72, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(189, '0514', '표피이식', 'Epidermal graft', '0112', 73, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(190, '0515', '기타', '', '0112', 74, '1', '2006-09-08 14:59:33', 332, '211.56.250.35'),
(191, '0516', '조직생검', 'Biopsy', '0112', 75, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(192, '0517', '액취증수술', 'Osmi op', '0108', 76, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(193, '0518', '펀치절제술', 'Punch', '0112', 77, '0', '2010-02-11 11:34:56', 332, '192.168.0.30'),
(194, '0519', '진피절제술', 'Subcision', '0112', 78, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(195, '0520', '라이포 셋', 'Lipo sat(Liposuction)', '0108', 0, '1', '2005-02-16 09:18:18', 332, '211.232.172.161'),
(196, '0521', '태반주사', 'Plasenta injection', '0112', 0, '1', '2004-01-09 09:49:24', 332, '211.232.172.130'),
(197, '0522', '다이펜사이프론(DPCP)', 'Diphencyprone(DPCP)', '0112', 0, '0', '2010-02-11 11:34:20', 332, '192.168.0.30'),
(198, '0523', '절제봉합술', 'Excision', '0112', 0, '0', '2010-02-11 11:35:51', 332, '192.168.0.30'),
(199, '0524', '아쿠아미드(바)', 'Aquamid(Bio)', '', 0, '0', '2007-01-23 13:16:24', 332, '211.56.250.35'),
(200, '0525', '와이어절제술', 'Wire scalpal', '0106', 0, '0', '2010-02-11 11:31:33', 332, '192.168.0.30'),
(201, '0526', '메트리듀어', 'Matridur', '0102', 0, '0', '2010-02-11 11:28:50', 332, '192.168.0.30'),
(202, '0527', '아쿠아미드', 'Aqua mid', '0102', 0, '0', '2010-02-11 11:29:00', 332, '192.168.0.30'),
(203, '0528', '써마지', 'Thermage', '0101', 0, '1', '2004-03-08 08:33:59', 332, '211.232.172.130'),
(204, '0529', '폴라리스', 'Polaris', '0101', 0, '0', '2010-02-09 11:34:15', 332, '192.168.0.6'),
(205, '0530', '증모술', '', '0112', 0, '1', '2004-08-17 11:24:12', 332, '211.232.172.161'),
(206, '0531', '타이탄', 'Titan', '0101', 0, '0', '2010-02-11 11:26:46', 332, '192.168.0.30'),
(207, '0532', '릴렉스 F', 'Relax F', '0101', 0, '0', '2005-03-29 17:08:29', 332, '211.232.172.161'),
(208, '0533', '프락셀 2', 'Fraxel 2 XENA', '0106', 0, '0', '2010-02-09 11:32:44', 332, '192.168.0.6'),
(209, '0534', '러블리', 'Lovely system', '0104', 0, '0', '2005-08-03 13:25:33', 332, '211.232.172.161'),
(210, '0535', 'IRF(내부고주파)', 'IRF', '0101', 0, '1', '2008-01-14 17:53:59', 332, '211.56.250.35'),
(211, '0536', 'T.NED', 'T.NED', '0103', 0, '1', '2005-10-04 10:26:40', 332, '211.232.172.161'),
(212, '0537', '스마트리포(지방용해술)', 'Smart Lipo', '0111', 0, '1', '2005-12-05 10:20:22', 332, '211.232.172.161'),
(213, '0538', '리포빔(지방용해술)', 'Lipo Beam', '0111', 0, '1', '2005-12-05 10:20:44', 332, '211.232.172.161'),
(214, '0539', 'Mega요법', 'Mega', '0104', 0, '1', '2006-03-24 13:36:14', 404, '211.232.172.161'),
(215, '0540', 'easy TCA', 'easy TCA', '0106', 0, '1', '2006-03-24 13:37:37', 404, '211.232.172.161'),
(216, '0541', 'HPL', 'HPL', '0111', 0, '1', '2006-03-24 13:47:20', 404, '211.232.172.161'),
(217, '0542', 'HLC', 'HLC', '0111', 0, '1', '2006-03-24 13:47:27', 404, '211.232.172.161'),
(218, '0543', '지방분해주사', '', '0111', 0, '1', '2006-03-24 13:47:33', 404, '211.232.172.161'),
(219, '0544', '초음파', '', '0111', 0, '1', '2006-03-24 13:47:36', 404, '211.232.172.161'),
(220, '0545', '베이저', 'VASER', '0111', 0, '0', '2010-02-11 11:23:39', 332, '192.168.0.30'),
(221, '0546', 'PSR', 'PSR', '0101', 0, '0', '2010-02-11 11:27:48', 332, '192.168.0.30'),
(222, '0547', '컨투어', 'Contour', '0111', 0, '1', '2006-07-11 10:17:31', 332, '211.56.250.35'),
(223, '0548', 'Easy  Phytic', 'Easy  Phytic', '0109', 0, '0', '2007-02-12 16:25:55', 332, '211.56.250.36'),
(224, '0549', '레이저 토닝', 'Laser toning', '0104', 0, '0', '2010-02-11 11:03:17', 332, '192.168.0.30'),
(225, '0550', '리펌', 'Refirme', '0101', 0, '1', '2006-09-18 13:40:46', 332, '211.56.250.35'),
(226, '0551', 'lux-IR', 'lux-IR', '0101', 0, '0', '2010-02-09 11:34:36', 332, '192.168.0.6'),
(227, '0552', 'lux-1540', 'lux-1540', '0106', 0, '0', '2006-09-19 09:41:46', 622, '211.56.250.37'),
(228, '0553', 'lux-R', 'lux-R', '0107', 0, '1', '2010-06-01 14:26:19', 332, '192.168.0.2'),
(229, '0554', 'lux-G', 'lux-G', '0104', 0, '0', '2006-09-19 09:42:37', 622, '211.56.250.37'),
(230, '0555', '라디에세', 'Radiessa', '0102', 0, '1', '2006-10-31 09:22:49', 332, '211.56.250.35'),
(231, '0556', '테오시알', 'Teosyal', '0102', 0, '1', '2007-01-23 13:20:14', 332, '211.56.250.35'),
(232, '0557', 'FAMI(자가지방이식술)', 'FAMI', '0108', 0, '0', '2010-02-11 11:30:18', 332, '192.168.0.30'),
(233, '0558', '안티락스', 'anti-lux', '0106', 0, '1', '2012-03-09 13:55:32', 332, '192.168.0.77'),
(234, '0559', '메트리덱스', 'Metridex', '0102', 0, '0', '2010-02-11 11:28:15', 332, '192.168.0.30'),
(235, '0560', '레스틸렌 서브큐', 'Restylene subQ', '0102', 0, '0', '2011-01-20 15:31:14', 332, '192.168.0.19'),
(236, '0561', '비절개종아리퇴축술', '', '0111', 0, '1', '2007-07-16 09:35:29', 332, '211.56.250.35'),
(237, '0562', '비만침', '', '0111', 0, '1', '2007-07-16 09:45:00', 332, '211.56.250.35'),
(238, '0563', '''스마트리포(액취,다한증)''', 'Smart Lipo', '0111', 0, '1', '2010-06-01 14:28:11', 332, '192.168.0.2'),
(239, '0564', '성장호르몬', '', '0111', 0, '1', '2007-10-22 14:02:51', 332, '211.56.250.35'),
(240, '0565', '마스터즈', '', '', 0, '0', '2010-02-11 11:26:07', 332, '192.168.0.30'),
(241, '0566', 'PDT', 'PDT', '0109', 0, '1', '2008-03-21 15:23:05', 332, '211.56.250.35'),
(242, '0567', 'MESO BOTOX', 'MESO BOTOX', '', 0, '1', '2008-03-21 15:41:38', 332, '211.56.250.35'),
(243, '0568', '멀티홀', 'multi-hole', '', 0, '0', '2010-02-09 11:33:55', 332, '192.168.0.6'),
(244, '0569', '심포니', 'symphony', '', 0, '1', '2008-03-21 15:49:05', 332, '211.56.250.35'),
(245, '0570', 'EZ lift', 'EZ lift', '', 0, '1', '2008-04-04 12:43:43', 332, '211.56.250.35'),
(246, '0571', 'Refine(프락셀3)', 'Refine', '', 0, '0', '2010-02-09 11:33:44', 332, '192.168.0.6'),
(247, '0572', '에블런스', '', '', 0, '0', '2010-02-11 11:29:50', 332, '192.168.0.30'),
(248, '0573', 'PPC주사', '', '0111', 0, '1', '2008-10-13 13:23:38', 332, '211.56.250.35'),
(249, '0574', 'PRP', 'PRP', '0112', 0, '1', '2010-02-11 11:25:13', 332, '192.168.0.30'),
(250, '0575', '드림 Lift', 'Dream lift', '', 0, '1', '2008-11-14 17:43:58', 332, '211.56.250.35'),
(251, '0576', 'PPP', 'PPP', '', 0, '0', '2010-02-11 11:14:29', 332, '192.168.0.30'),
(252, '0577', '항산화주사', '', '0112', 0, '1', '2009-07-24 18:14:55', 332, '211.56.250.35'),
(253, '0578', '쥬비덤', '', '0102', 0, '0', '2011-01-20 15:31:37', 332, '192.168.0.19'),
(254, '0579', '레이저토닝0', 'LT0', '0104', 0, '1', '2010-08-23 13:48:07', 332, '192.168.0.18'),
(255, '0580', '레이저토닝1', 'LT1', '0104', 0, '1', '2010-08-23 13:48:16', 332, '192.168.0.18'),
(256, '0581', '레이저토닝2', 'LT2', '0104', 0, '1', '2010-08-23 13:48:22', 332, '192.168.0.18'),
(257, '0582', '자궁경부암 백신 1차', '', '0112', 0, '1', '2010-02-12 11:17:42', 332, '192.168.0.30'),
(258, '0583', '자궁경부암 백신 2차', '', '0112', 0, '1', '2010-02-12 11:17:48', 332, '192.168.0.30'),
(259, '0584', '자궁경부암 백신 3차', '', '', 0, '1', '2010-02-12 11:17:54', 332, '192.168.0.30'),
(260, '0585', 'Td 백신', '', '', 0, '1', '2010-02-12 11:17:59', 332, '192.168.0.30'),
(261, '0586', '레이저토닝3', 'LT3', '', 0, '1', '2010-08-23 13:48:29', 332, '192.168.0.18'),
(262, '0587', 'S레이저토닝', 'SLT', '', 0, '1', '2010-08-23 13:48:44', 332, '192.168.0.18'),
(263, '0588', '필러', 'Filler', '', 0, '1', '2011-01-20 15:25:42', 332, '192.168.0.19'),
(264, '0589', 'A-Toning', 'A-Toning', '', 0, '1', '2011-06-30 18:11:22', 332, '192.168.0.77'),
(265, '0590', '듀얼토닝', 'DT', '', 0, '1', '2012-03-09 13:34:43', 332, '192.168.0.77'),
(266, '0591', 'FSL', '', '', 0, '1', '2012-03-09 14:04:17', 332, '192.168.0.77'),
(267, '0592', 'FSL케어', '', '', 0, '1', '2012-03-09 14:04:21', 332, '192.168.0.77'),
(268, '0593', 'Lux', '', '', 0, '1', '2012-03-09 14:04:56', 332, '192.168.0.77'),
(269, '0594', '하이드로리프팅', '', '', 0, '1', '2012-03-09 14:11:42', 332, '192.168.0.77'),
(270, '0595', '마이어스 주사', '', '', 0, '1', '2012-08-14 11:08:11', 332, '192.168.0.101'),
(271, '0596', 'BNT요법', '', '', 0, '1', '2012-08-14 11:08:20', 332, '192.168.0.101'),
(272, '0600', '보정류', '', '', 79, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(273, '0601', '레이저-보정', 'Laser-retouch', '0112', 80, '0', '2010-02-08 10:04:52', 332, '192.168.0.6'),
(274, '0602', '보톡스-보정', 'Botox-retouch', '0102', 81, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(275, '0603', '''레스틸렌, 펄레인-보정''', 'Restylene-retouch', '0102', 82, '0', '2007-01-25 09:10:29', 332, '211.56.250.35'),
(276, '0604', '영구제모-보정', 'Epilation-retouch', '0107', 83, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(277, '0605', '영구제모-사후처치', 'Epilation-A/S', '0107', 84, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(278, '0606', '반영구화장-보정', 'SPM-retouch', '0112', 85, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(279, '0607', '필링-사후관리', 'Post-peel care', '0110', 86, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(280, '0608', '알로덤-보정', 'Aloderm-retouch', '0102', 87, '0', '2007-01-25 09:10:27', 332, '211.56.250.35'),
(281, '0609', '메타크릴-보정', 'Metachril-retouch', '0102', 88, '0', '2007-01-25 09:10:37', 332, '211.56.250.35'),
(282, '0610', '상태확인', 'Condition Confirmation', '0112', 89, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(283, '0611', '안면거상술-보정', '', '0108', 0, '0', '2010-02-11 11:26:20', 332, '192.168.0.30'),
(284, '0612', '자가지방이식-보정', 'Fat injection-retouch', '0108', 0, '0', '2010-02-11 11:31:00', 332, '192.168.0.30'),
(285, '0613', '레이저박피-드레싱', 'Resurfacing-dressing', '0106', 0, '1', '2004-02-02 10:49:02', 332, '211.232.172.130'),
(286, '0614', '바이오플라스티-보정', 'Bioplasty-retouch', '0102', 0, '0', '2007-01-23 13:17:19', 332, '211.56.250.35'),
(287, '0615', '메트리듀어-보정', 'Matridur-retouch', '0102', 0, '0', '2007-01-25 09:10:10', 332, '211.56.250.35'),
(288, '0616', '아쿠아미드-보정', 'Aqua mid-retouch', '0102', 0, '0', '2007-01-23 13:16:36', 332, '211.56.250.35'),
(289, '0617', 'FAMI(자가지방이식)-보정', 'FAMI-dressing', '0108', 0, '1', '2006-11-13 13:22:41', 332, '211.56.250.35'),
(290, '0618', '필러-보정', 'Filler-retouch', '0112', 0, '1', '2007-01-23 13:36:35', 332, '211.56.250.35'),
(291, '0619', '보험', '', '0112', 0, '1', '2010-03-30 13:01:53', 948, '192.168.0.4'),
(292, '0620', 'FTL', '', '', 0, '1', '2012-03-09 14:12:25', 332, '192.168.0.77'),
(293, '0621', '스컬트라', '', '', 0, '1', '2012-03-09 14:14:25', 332, '192.168.0.77'),
(294, '0700', '예약류', '', '', 90, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(295, '0701', '처치예약', 'Clinic Reservation', '0113', 91, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(296, '0702', '관리예약', 'Aesthetic Reservation', '0113', 92, '0', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(297, '0703', '_진료비', '', '0112', 0, '1', '2006-08-01 18:39:53', 332, '211.56.250.35'),
(298, '0704', '_처방비', '', '0112', 0, '1', '2006-08-01 08:38:56', 332, '211.56.250.35'),
(299, '0800', '미등록코드류', '', '', 93, '1', '2003-08-01 00:00:00', 1, '0.0.0.0'),
(300, '0801', '미등록처치1명', 'Unregistered Clinic1 Code', '0113', 94, '1', '2008-10-30 06:05:13', 332, '211.56.250.35'),
(301, '0802', '미등록처치2명', 'Unregistered Clinic2 Code', '0113', 95, '1', '2008-10-30 06:05:37', 332, '211.56.250.35');";
//    $rc_hd = mysql_query($rc_sql);
?>