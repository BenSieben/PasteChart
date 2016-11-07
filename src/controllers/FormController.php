<?php
namespace cs174\hw4\controllers;
use \cs174\hw4\configs\Config as Config;
use \cs174\hw4\models\ChartModel as ChartModel;

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
            // TODO do actual checking of data instead of jumping straight to chart view
            $title = $_REQUEST['title'];
            $data = str_replace("\r", "", $_REQUEST['chartData']); // remove return carriages from chart data if they exist
            $md5 = hash("md5", $_REQUEST['chartData']);
            // insert data into database
            $cm = new ChartModel();
            $result = $cm->insertChartEntry($md5, $title, $data);
            if(!$result) { // if insert failed, send user back to landing page
                header("Location: " . Config::BASE_URL . "?c=landing&t=" . urlencode($_REQUEST['title']) . "&d=" .
                    urlencode($_REQUEST['chartData']) . "&err=" . urlencode("Error: unable to save chart data to server. " .
                    "Please try again."));
                exit();
            }
            header("Location: " . Config::BASE_URL . "/?c=chart&a=show&arg1=LineGraph&arg2=$md5");
        }
        else { // if title / chartData not entered, send user back to landing page
            if(isset($_REQUEST['title'])) {
                $t = urlencode($_REQUEST['title']);
            }
            else {
                $t = "";
            }

            if(isset($_REQUEST['chartData'])) {
                $d = urlencode($_REQUEST['chartData']);
            }
            else {
                $d = "";
            }

            header("Location: " . Config::BASE_URL . "?c=landing&t=$t&d=$d&err=" . urlencode("Error: title and / or " .
                "chart data was not filled in"));
        }
    }
}
?>