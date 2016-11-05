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
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else if (strcmp($chartType, 'PointGraph') === 0) { // show PointGraph
            //TODO handle PointGraph
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else if (strcmp($chartType, 'Histogram') === 0) { // show Histogram
            //TODO handle Histogram
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else if (strcmp($chartType, 'xml') === 0) { // show xml
            //TODO handle xml
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else if (strcmp($chartType, 'json') === 0) { // show json
            //TODO handle json
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else if (strcmp($chartType, 'jsonp') === 0) { // show jsonp
            //TODO handle jsonp
            $this->setUpBasicData($chartType, $dbEntryHash);
        }
        else { // bad chartType given
            // send back to landing page when given bad chartType
            header("Location: " . Config::BASE_URL . "?c=landing");
            exit();
        }
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
            // TODO react to tuple get failure
            echo("<!-- Failed to load result -->");
        }
        else {
            foreach($result as $row) { // use foreach to easily get result of query (only expect a single $row)
                $data['title'] = $row['title'];
                $data['data'] = $row['data'];
            }
        }
        $view = new ChartView();
        $view->render($data);
    }
}
?>