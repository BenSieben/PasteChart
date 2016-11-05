<?php
namespace cs174\hw4\controllers;
use \cs174\hw4\configs\Config as Config;

/**
 * Class FormController
 * @package cs174\hw4\controllers
 *
 * Controller which handles taking in and
 * then accepting / rejecting user chart data
 * and then redirecting user to appropriate page
 */
class FormController extends Controller {

    /**
     * Handles form submitted through landing page
     * by analyzing $_REQUEST variable values and
     * then doing the appropriate action in response
     */
    public function handleChartForm() {
        if(isset($_REQUEST['title']) && strcmp($_REQUEST['title'], '') !== 0
        && isset($_REQUEST['chartData']) && strcmp($_REQUEST['chartData'], '') !== 0) { // if title / chartData entered, check the values
            //TODO do actual checking of data instead of jumping straight to chart view
            header("Location: " . Config::BASE_URL . "/?c=chart&a=show&arg1=LineGraph&arg2=HASHHERE");
        }
        else { // if title / chartData not entered, send user back to landing page
            header("Location: " . Config::BASE_URL . "?c=landing");
        }
    }
}
?>