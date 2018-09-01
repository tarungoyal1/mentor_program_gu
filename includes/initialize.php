<?php
// defined('DS') ? null : define("DS", DIRECTORY_SEPARATOR);
// defined('SITE_ROOT') ? null : define("SITE_ROOT", "C:".DS."wamp".DS."www".DS."photo_gallery");
// defined('LIB_PATH') ? null : define("LIB_PATH", SITE_ROOT.DS."includes");

require_once ('config.php');
require_once ('functions.php');
require_once ('session.php');
require_once ('database.php');
//require core-objects at the end
require_once ('mentor.php');
require_once ('assign.php');
require_once ('program.php');
require_once ('sem.php');
require_once ('year.php');
require_once ('student.php');
require_once ('messagebyfac.php');
require_once ('messagetocr.php');
require_once ('messagebycr.php');

$crguid = "GU0113810484";
$crname = "Dr. Pallavi M. Goel";



?>