<?php
namespace cs174\hw4\controllers;
use cs174\hw4\configs\Config;
use cs174\hw4\views\LandingView as LandingView;

/**
 * Class LandingController
 * @package cs174\hw4\controllers
 *
 * Controller for landing view of PasteChart website
 */
class LandingController extends Controller {

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST to set up data for the
     * landing View and perform necessary tasks
     * with Model(s), then calls the landing View
     * for rendering
     */
    public function callView() {
        $view = new LandingView();
        $data = $this->getData();
        $view->render($data);
    }

    /**
     * Prepares $data array to pass onto the landing
     * view, including content for text area
     * @return Array<String> data that the landing view needs
     */
    private function getData() {
        // create a variable to hold the text area placeholder because the explanation is very long
        $data['dataPlaceholder'] = "Data should conform to the following rules: " .
            "[1] Each line represents a single x-value in the graph, with up to " . Config::MAX_VALUES_PER_LINE .
            " y-value data (after x-title value) per line. Each value must be comma-separated {Example: a line could " .
            "look like 'Jan,10,20', which would indicate a x-value line titled 'Jan' which has two y-values to graph: " .
            "10 and 20}. [2] Each line can go up to a maximum of " . Config::MAX_DATA_LINE_LENGTH ." characters " .
            "(including comma separators). [3] There is a maximum of " . Config::MAX_DATA_LINES . "lines of data to " .
            "plot. [4] The first line must specify values for all y-values, but subsequent lines can leave y-values " .
            "blank (simply use empty string as placeholder).";

        // set up values to automatically place in title / data fields
        if(isset($_REQUEST['t'])) {
            $data['title'] = $_REQUEST['t'];
        }
        else {
            $data['title'] = "";
        }

        if(isset($_REQUEST['d'])) {
            $data['chartData'] = $_REQUEST['d'];
        }
        else {
            $data['chartData'] = "";
        }

        // set up server reject message
        if(isset($_REQUEST['err'])) {
            $data['serverError'] = "<p>" . $_REQUEST['err'] . "</p>";
        }
        else {
            $data['serverError'] = "";
        }

        return $data;
    }
}
?>