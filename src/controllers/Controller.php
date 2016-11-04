<?php
namespace cs174\hw4\controllers;
use cs174\hw4\configs\Config as Config;

/**
 * Class Controller
 * @package cs174\hw4\controllers
 *
 * Superclass for any class used
 * as a Controller for the PasteChart
 * website
 */
class Controller {

    /**
     * This function will look at the current
     * values in PHP super globals such as $_REQUEST
     * to determine which Controller subclass to call
     * to handle the forms
     */
    public function processForms() {
        if(isset($_REQUEST['c'])) { // c is name of Controller to call

        }
        else { // if $_REQUEST['c'] is not set, then show default landing page
            header("Location: " . Config::BASE_URL . "?c=landing");
        }
    }
}
?>