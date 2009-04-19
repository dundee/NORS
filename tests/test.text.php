<?php
$_SERVER = array();
$_SERVER['PHP_SELF'] = '/nors4/index.php';
$_SERVER['REQUEST_URI'] = '/nors4/post/';
$_SERVER['SCRIPT_NAME'] = '/nors4/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_GET['controller'] = 'post';


define('HIGH_PERFORMANCE', 0);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

define('APP_PATH', dirname(__FILE__) . '/..');
require_once('../Core/Functions.php');

setUrlPath();

Core_Config::singleton()->read(APP_PATH . '/config/config.yml.php');


$router   = Core_Router::factory();
$request  = Core_Request ::factory();

$router->decodeUrl($request, FALSE);

/* ************* TEST ************** */

$text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin lacus. Nam vel ante a mauris tempus sollicitudin. Praesent feugiat est et urna. Ut risus orci, iaculis sed, euismod a, iaculis ut, urna. Etiam leo risus, viverra eget, blandit quis, sollicitudin in, lacus. Pellentesque tempus massa at nunc. Sed interdum lectus ut neque. Praesent lobortis velit eget mi. Duis molestie, eros eu ultricies sollicitudin, leo justo condimentum tellus, non accumsan urna odio vitae augue. Duis sit amet nibh. Nunc viverra mattis eros.

Pellentesque ut urna. Nulla facilisi. Sed at leo. Duis vehicula arcu quis eros. Cras in massa. Vivamus eget orci a dolor suscipit eleifend. In imperdiet dui quis augue. Suspendisse justo. Aliquam commodo, ante at dapibus porta, nunc metus pretium lacus, quis viverra felis purus at est. Ut laoreet, enim sit amet lacinia gravida, quam dui bibendum risus, et aliquam massa mi non turpis. Ut rhoncus. Nulla quis turpis. Proin imperdiet ipsum vel enim. Suspendisse vehicula rhoncus elit. Pellentesque ac arcu a massa tempor accumsan. Nulla euismod, arcu eget convallis rhoncus, turpis quam dignissim lacus, sed volutpat nibh diam ut tortor. Suspendisse sollicitudin turpis id diam. Cras a mauris in metus pharetra congue.

Proin turpis lacus, tincidunt et, tincidunt eget, bibendum iaculis, libero. Etiam lobortis risus eu libero. Nullam volutpat enim at velit. Suspendisse sed lorem. Vivamus elit tortor, condimentum vitae, laoreet eget, rhoncus non, magna. Duis tempus est. Phasellus non tortor. Maecenas ac felis et mi aliquet scelerisque. Donec vitae nisl a mi imperdiet volutpat. Cras dignissim adipiscing tellus. Etiam lobortis lacinia sem. Maecenas nulla leo, fringilla pulvinar, pulvinar ut, luctus non, dui. Integer dignissim aliquam leo.

Mauris sollicitudin neque pretium lacus. Sed et tellus. Phasellus scelerisque, nibh et aliquam porttitor, purus lectus vehicula leo, sed porttitor magna nisi eu orci. Etiam ultricies egestas tellus. Vivamus sapien. Suspendisse potenti. Nulla malesuada eleifend velit. Donec pharetra tortor id est. Quisque auctor porttitor turpis. Nullam bibendum tincidunt ipsum. Suspendisse sagittis. In viverra mauris. Nam at augue.";

$name = "0-řešitel+§!_?&*%$#@~wer,.-/\"|:¨)[]cec";

$obj = new Core_Text();

echo "<p>" . $obj->getPerex(100, $text). ENDL . "</p>" . ENDL . ENDL;

for ($i = 79; $i < 84; $i++) {
	echo "<p>" . $obj->getWords($i, $text). ENDL . "</p>";
}
echo "<p>" . $obj->getParagraphs(2, $text). ENDL . "</p>" . ENDL . ENDL;
echo $obj->getWords(10, $text). ENDL . "<br />" . ENDL . ENDL;
echo $obj->urlEncode($name). ENDL . "<br />" . ENDL . ENDL;
echo $obj->crypt('pass', 'soil'). ENDL . "<br />" . ENDL . ENDL;
echo $obj->dateToTimeStamp("2008-12-13 21:32:55"). ENDL . "<br />" . ENDL . ENDL;
echo $obj->clearAmpersand("&  &amp; &copy;"). ENDL . "<br />" . ENDL . ENDL;
echo $obj->hideMail("mailto:info@milde.cz"). ENDL . "<br />" . ENDL . ENDL;
echo ENDL . "<br />";
$text = <<<END
<a href="#">kotva</a>aaa
bbb

dalsi odstavec

treti odstavec
s radkem
END;
echo $obj->format_comment($text). ENDL . "<br />" . ENDL . ENDL;
echo $obj->format_html($text). ENDL . "<br />" . ENDL . ENDL;

echo ENDL;
?>
