<?php
namespace cs174\hw4\controllers;
use \cs174\hw4\configs\Config as Config;
use \cs174\hw4\models\ChartModel as ChartModel;
use \cs174\hw4\views\ChartView as ChartView;

/**
 * Class ChartController
 * @package cs174\hw4\controllers
 *
 * Controller which handles showing user chart
 * data depending on which form they would like
 * to see the data in by setting up appropriate
 * view based on PHP super global values
 */
class ChartController extends Controller {

    /**
     * Pulls data from database and picks a view depending
     * on values of given arguments
     * @param $chartType String type of chart to display (LineGraph, PointGraph, etc.)
     * @param $dbEntryHash String md5 hash of the chart data we would like to pull from the database
     * @param null $javascript_callback (for jsonp chart in particular) which specifies callback function to use
     */
    public function show($chartType, $dbEntryHash, $javascript_callback = null) {
        if(strcmp($chartType, 'LineGraph') === 0) { // show LineGraph
            //TODO handle LineGraph
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'PointGraph') === 0) { // show PointGraph
            //TODO handle PointGraph
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'Histogram') === 0) { // show Histogram
            //TODO handle Histogram
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'xml') === 0) { // show xml
            //TODO handle xml
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'json') === 0) { // show json
            //TODO handle json
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'jsonp') === 0) { // show jsonp
            //TODO handle jsonp
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $this->launchView($data);
        }
        else { // bad chartType given
            // send back to landing page when given bad chartType
            header("Location: " . Config::BASE_URL . "?c=landing");
            exit();
        }
    }

    /**
     * Gets the view to render
     * @param $data Array of data the view needs for rendering
     */
    private function launchView($data) {
        $view = new ChartView();
        $view->render($data);
    }

    /**
     * Sets up basic components of data and launches view
     * @param $chartType String type of chart to display (LineGraph, PointGraph, etc.)
     * @param $dbEntryHash String md5 hash of the chart data we would like to pull from the database
     */
    private function setUpBasicData($chartType, $dbEntryHash) {
        // set up $data array to send to ChartView's render, and then call render with prepared #data
        $data['baseURL'] = Config::BASE_URL;
        $data['chartType'] = $chartType;
        $data['hash'] = $dbEntryHash;
        // now load up data from database based on hash (to get chart title and date)
        $cm = new ChartModel();
        $result = $cm->getChartEntry($dbEntryHash);
        if(!$result) {
            // if hash is not found in DB, we alert view to this so we can show an error page
            $data['noDBEntry'] = "Error: given hash &quot;" . htmlspecialchars($data['hash']) . "&quot; does not " .
                "exist in database. Please double-check that the hash is correct";
        }
        else {
            foreach($result as $row) { // use foreach to easily get result of query (only expect a single $row)
                $data['title'] = htmlspecialchars($row['title']); // use htmlspecialchars to safely display title
                $data['data'] = $row['data'];
            }
        }
        // array ot chartTypes which use chart.js (so view knows whether to use chart.js or not)
        $data['drawChartTypes'] = ['LineGraph', 'PointGraph', 'Histogram'];
        return $data;
    }

    /**
     * Converts a given dataString into the JavaScript object format,
     * which is needed when using chart.js
     * @param $dataString String original data of the chart
     * @return String JavaScript object format of the chart data
     */
    private function getChartJSObjectText($dataString) {
        // split data back into array form by exploding on newline
        $arr = explode("\n", $dataString);

        // pull data from array of lines into new obj array (doing better formatting)
        $obj = [];

        // TODO handle adding multiple values (up to 5) rather than just the first value per line
        foreach($arr as $row) {
            $rowArr = explode(",", $row);
            $objEntry = array($rowArr[0] => doubleval($rowArr[1]));
            array_push($obj, $objEntry);
        }
        // json_encode the new obj array
        $encode = json_encode($obj);

        // fix formatting of json_encode to exactly match what chart.js expects
        $fixedFormatEncode = str_replace("{", "", $encode);
        $fixedFormatEncode = str_replace("}", "", $fixedFormatEncode);
        $fixedFormatEncode = str_replace("[", "{", $fixedFormatEncode);
        $fixedFormatEncode = str_replace("]", "}", $fixedFormatEncode);

        return $fixedFormatEncode;
    }
}
?>