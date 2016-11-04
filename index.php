<?php
namespace cs174\hw4;

require_once("vendor/autoload.php");

/**
 * All links for the PasteChart site
 * go through this index.php
 */

// Make a new Controller and let it determine
//   what to do based on current values in PHP super globals
$controller = new \cs174\hw4\controllers\Controller();
$controller->processForms();

?>
