<?php
namespace cs174\hw4;

// Require Composer's autoload file (to autoload included vendors)
require_once("vendor/autoload.php");

// Implement autoloader for PasteChart's files
spl_autoload_register(function ($className) {
    // all files are in src or root folder, which makes it the prefixes
    $prefixes = ['src/', ''];

    foreach($prefixes as $prefix) {
        // then directory of class (since all classes in this website have namespaces
        //   cs174\hw4\...  in folders named ...) we do some string manipulation
        //   to extract the proper directory to jump to for requiring the class
        //   (replacing backslash with forward slash, and only include name of class
        //   starting after cs175\hw4\ in namespace to get file directory of the class)
        $dir = str_replace('\\', '/', substr($className, strpos($className, "\\hw4\\") + 5)) . '.php';

        // combine prefix and directory to pick out file to require
        if(file_exists("$prefix$dir")) {
            require_once("$prefix$dir");
        }
    }
});

/**
 * All links for the PasteChart site
 * go through this index.php
 */

// Make a new Controller and let it determine
//   what to do based on current values in PHP super globals
$controller = new \cs174\hw4\controllers\Controller();
$controller->processForms();

?>
