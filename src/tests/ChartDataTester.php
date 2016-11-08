<?php
namespace cs174\hw4\tests;
use cs174\hw4\configs\Config;

/**
 * Class DataTester
 * @package cs174\hw4\tests\DataTester
 *
 * A SimpleTest UnitTestCase which performs multiple
 * checks on data (used to construct the object) to make
 * sure that it looks valid
 */
class ChartDataTester extends \UnitTestCase {

    private $data; // the chart data to test

    /**
     * Constructs a new DataTester
     * @param $data String the chart data to run unit tests on
     */
    public function __construct($data) {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * This unit test checks that the number of lines in the data does not
     * exceed the maximum number of allowed lines
     */
    public function testNumDataLines() {
        $this->assertTrue(substr_count($this->data, "\n") < Config::MAX_DATA_LINES);
    }

    /**
     * This unit test checks that the number of values per line is consistent
     * for all lines in the data
     */
    public function testNumValuesPerLine() {
        $split_lines = explode("\n", $this->data);
        $first_line_comma_count = substr_count($split_lines[0], ","); // count number of commas in first line
        $all_lines_match = true;
        foreach($split_lines as $line) { // make sure each line has same number of commas
            if(substr_count($line, ",") !== $first_line_comma_count) {
                // if a line does not match same number of values as first line, we have to reject the data
                $all_lines_match = false;
                break;
            }
        }
        $this->assertTrue($all_lines_match);
    }

}
?>