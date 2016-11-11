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
            // handle LineGraph
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            if(!isset($data['noDBEntry'])) {
                $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            }
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'PointGraph') === 0) { // show PointGraph
            // handle PointGraph
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            if(!isset($data['noDBEntry'])) {
                $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            }
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'Histogram') === 0) { // show Histogram
            // handle Histogram
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            if(!isset($data['noDBEntry'])) {
                $data['chartDataJSObjectText'] = $this->getChartJSObjectText($data['data']);
            }
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'xml') === 0) { // show xml
            // handle xml
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $data['code'] = htmlspecialchars($this->generateXMLCode($data['title'], $data['data']));
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'json') === 0) { // show json
            // handle json
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $data['code'] = htmlspecialchars($this->generateJSONCode($data['title'], $data['data']));
            $this->launchView($data);
        }
        else if (strcmp($chartType, 'jsonp') === 0) { // show jsonp
            // handle jsonp
            //   for jsonp, the user specifies a function (as $javascript_callback) and all we do is show
            //   this callback using the JSON of the chart data as its argument
            $data = $this->setUpBasicData($chartType, $dbEntryHash);
            $jsonCode = $this->generateJSONCode($data['title'], $data['data']);
            $data['code'] = htmlspecialchars($javascript_callback . "(" . $jsonCode . ");");
            $this->launchView($data);
        }
        else { // bad chartType given
            // send back to landing page when given bad chartType
            header("Location: " . Config::BASE_URL . "/?c=landing");
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
            $data['noDBEntry'] = "<p>Error: given hash &quot;" . htmlspecialchars($data['hash']) . "&quot; does not " .
                "exist in the database. Please double-check that the hash is correct</p>";
        }
        else {
            foreach($result as $row) { // use foreach to easily get result of query (only expect a single $row)
                $data['title'] = htmlspecialchars($row['title']); // use htmlspecialchars to safely display title
                $data['data'] = $row['data'];
            }
        }
        // array ot chartTypes which use chart.js (so view knows whether to use chart.js or not)
        $data['drawChartTypes'] = ['LineGraph', 'PointGraph', 'Histogram'];

        // header for the text above all the chart view links
        $data['chartHeader'] = "Share your chart and data at the URLs below:";

        // get array groups of chart type name, chart type link, and text to display for link
        $data['chartLink'][0] = ["As a LineGraph:", "?c=chart&a=show&arg1=LineGraph&arg2=" . $data['hash'],
                $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=LineGraph&amp;arg2=" . $data['hash']];
        $data['chartLink'][1] = ["As a PointGraph:", "?c=chart&a=show&arg1=PointGraph&arg2=" . $data['hash'],
            $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=PointGraph&amp;arg2=" . $data['hash']];
        $data['chartLink'][2] = ["As a Histogram:", "?c=chart&a=show&arg1=Histogram&arg2=" . $data['hash'],
            $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=Histogram&amp;arg2=" . $data['hash']];
        $data['chartLink'][3] = ["As XML data:", "?c=chart&a=show&arg1=xml&arg2=" . $data['hash'],
            $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=xml&amp;arg2=" . $data['hash']];
        $data['chartLink'][4] = ["As JSON data:", "?c=chart&a=show&arg1=json&arg2=" . $data['hash'],
            $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=json&amp;arg2=" . $data['hash']];
        $data['chartLink'][5] = ["As JSONP data:", "?c=chart&a=show&arg1=jsonp&arg2=" . $data['hash'] . "&arg3=javascript_callback",
            $data['baseURL'] . "/?c=chart&amp;a=show&amp;arg1=jsonp&amp;arg2=" . $data['hash'] . "&amp;arg3=javascript_callback"];

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

        foreach($arr as $row) {
            $rowArr = explode(",", $row);
            $valuesArr = [$rowArr[0]]; // will hold all values in the row in an array
            for($i = 1; $i < count($rowArr); $i++) {
                if(strcmp($rowArr[$i], "") === 0) { // if the value is blank, give this entry a special value
                    array_push($valuesArr, null);
                }
                else {
                    array_push($valuesArr, doubleval($rowArr[1]));
                }
            }
            array_push($obj, $valuesArr);
        }
        // json_encode the new obj array
        $encode = json_encode($obj, JSON_PRETTY_PRINT);

        return $encode;
    }

    /**
     * Creates XML code for the chart "object", which has
     * a title and chart data, according to chart.dtd
     * @param $title string title to convert to XML
     * @param $dataString string data to convert to XML
     * @return string XML code for $dataString
     */
    private function generateXMLCode($title, $dataString) {
        // split data back into array form by exploding on newline
        $arr = explode("\n", $dataString);

        // the string which keeps getting XML code added to it
        $xml = "";

        // first set up the chart amd data elements at the top of the XML
        $xml .= "<?xml version=\"1.0\" encoding = \"UTF-8\"?>\n";
        $xml .= "<!DOCTYPE chart SYSTEM \"chart.dtd\" >\n";
        $xml .= "<chart title=\"$title\">\n";
        $xml .= "    <data>\n";

        // now loop through array of data to add rows to the chart data in the XML
        foreach($arr as $row) {
            $rowArr = explode(",", $row);

            $xml .= "        <xVal label=\"" . $rowArr[0] . "\">\n"; // add row with given label
            // place all value(s) in this row
            for($i = 1; $i < count($rowArr); $i++) {
                $xml .= "            <yVal>" . $rowArr[$i] . "</yVal>\n";
            }
            $xml .= "        </xVal>\n";
        }

        // close chart and data elements
        $xml .= "    </data>\n";
        $xml .= "</chart>\n";

        return $xml;
    }

    /**
     * Creates JSON code for the chart "object", which has
     * a title and chart data
     * @param $title string title to convert to JSON
     * @param $dataString string data to convert to JSON
     * @return string JSON code for $dataString
     */
    private function generateJSONCode($title, $dataString) {
        // split data back into array form by exploding on newline
        $arr = explode("\n", $dataString);

        $obj = []; // our chart "object"
        $chartData = []; // holds all chart data lines

        array_push($obj, array("chartTitle" => $title)); // add the chart title to the object, calling it "chartTitle"

        // set up all rows of data to add to the obj array (one element per row)
        foreach($arr as $row) {
            $rowArr = explode(",", $row);
            $valuesArr = [$rowArr[0]]; // will hold all values in the row in an array
            for($i = 1; $i < count($rowArr); $i++) {
                array_push($valuesArr, doubleval($rowArr[$i]));
            }
            array_push($chartData, $valuesArr); // push array into chartData
        }

        array_push($obj, array("chartData" => $chartData)); // add the chart data to the object, calling it "chartData"

        // json_encode the new obj array (our "object")
        $encode = json_encode(array("chart" => $obj), JSON_PRETTY_PRINT); // JSON encode the object, calling it "chart"

        return $encode;
    }
}
?>