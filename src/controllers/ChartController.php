<?php
namespace cs174\hw4\controllers;

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

        }
        else if (strcmp($chartType, 'PointGraph') === 0) { // show PointGraph

        }
        else if (strcmp($chartType, 'Histogram') === 0) { // show Histogram

        }
        else if (strcmp($chartType, 'xml') === 0) { // show xml

        }
        else if (strcmp($chartType, 'json') === 0) { // show json

        }
        else if (strcmp($chartType, 'jsonp') === 0) { // show jsonp

        }
        else { // bad chartType given
            echo("Bad chartType given ($chartType) for show function in ChartController!");
        }
    }
}
?>