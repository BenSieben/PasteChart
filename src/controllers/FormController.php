<?php
namespace cs174\hw4\controllers;
use \cs174\hw4\configs\Config as Config;
use \cs174\hw4\models\ChartModel as ChartModel;
use \cs174\hw4\tests\ChartDataTester as ChartDataTester;

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
            $title = $_REQUEST['title'];
            $data = str_replace("\r", "", $_REQUEST['chartData']); // remove return carriages from chart data if they exist
            $data = preg_replace("/\n+/", "\n", $data); // remove extra newlines between lines
            $md5 = hash("md5", $data);

            // TODO do actual checking of data instead of jumping straight to chart view
            $this->verifyChartForm($title, $data);

            // insert data into database
            $cm = new ChartModel();
            $result = $cm->insertChartEntry($md5, $title, $data);
            if(!$result) { // if insert failed, send user back to landing page
                header("Location: " . Config::BASE_URL . "/?c=landing&t=" . urlencode($_REQUEST['title']) . "&d=" .
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

            header("Location: " . Config::BASE_URL . "/?c=landing&t=$t&d=$d&err=" . urlencode("Error: title and / or " .
                "chart data was not filled in"));
        }
    }

    /**
     * Checks that the given $title and $data conform to expected standards
     * @param $title String title of chart to submit to database
     * @param $data String data of chart to submit to database
     */
    private function verifyChartForm($title, $data){
        //TODO be sure to use at least 2 SimpleTest unit tests
        // 1 to check number of items in tuple
        // 2 to check number of lines and line length constraints

        // this will keep track of all errors found during the check, and will be used
        //   later to determine if the form should be rejected or not
        $errors = [];

        $newTitle = $title; // $newTitle = title to give user if their form gets rejected

        // Step 1: check the title for validity
        if(strlen($title) === 0) {
            array_push($errors, "Error: title needs to have at least one character<br />");
        }
        else if(strlen($title) > Config::MAX_TITLE_LENGTH) {
            array_push($errors, "Error: title exceeds maximum character length of " . Config::MAX_TITLE_LENGTH .
                "Excess characters have been trimmed off.<br />");
            $newTitle = substr($title, 0, Config::MAX_TITLE_LENGTH);
        }

        // Step 2: use the ChartDataTester to run some SimpleTest unit tests on the data
        $cdt = new ChartDataTester($data);
        $cdt->run(new \SimpleReporter());
        $cdt_results = $cdt->getResults();

        // check cdt_results to see if any problematic lines were found
        // if so, first find out which problem(s) occurred
        // also record which lines caused problems
        $problem_keys = []; // will hold the key(s) from cdt_results that indicate a problem with data line(s)
        $problem_lines = [];
        foreach($cdt_results as $key => $value) {

        }

        // make new data which does not have all the problematic lines to
        //   create $newData, which will be used when redirecting the user
        //   back to the landing page

        // Step 3: determine if any errors were detected in testing, and if so, redirect
        //   user back to landing page with cleaned form values (and error messages)
    }

    /**
     * This private helper function generates an error message for the
     * given key (a key which is in ChartDataTester's key list)
     * @param $key
     * @return String the error message associated with the given key,
     * or the empty string if an unknown key is given
     */
    private function getErrorString($key) {
        switch($key) {
            case 'lines_after_maximum_allowed_lines':
                return "Error: maximum number of lines exceeded. Excess lines have been removed<br />";
            case 'lines_with_non_matching_values':
                return "Error: lines with number of values not matching the first line have been " .
                    "detected. These lines have been removed. <br />";
            case 'lines_with_too_many_values':
                return "Error: lines with too many values (more than " . Config::MAX_VALUES_PER_LINE .
                    ") detected. These lines have been removed<br />";
            case 'lines_with_too_few_values':
                return "Error: lines with no values detected. These lines have been removed <br />";
            case 'lines_with_too_many_characters':
                return "Error: lines exceeding " . Config::MAX_DATA_LINE_LENGTH . " characters have been " .
                    "detected. These lines have been removed<br />";
            case 'lines_with_non_numeric_values':
                return "Error: lines with non-numeric values have been detected. These lines have been removed<br />";
            case 'lines_with_invalid_labeling':
                return "Error: lines missing a label have been detected. These lines have been removed<br />";
            default:
                return "";
        }
    }
}
?>