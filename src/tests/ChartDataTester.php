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
        $this->assertTrue(substr_count($this->data, "\n") < Config::MAX_DATA_LINES, "There are too many data lines!");
    }

    /**
     * This unit test checks that the number of values per line is consistent
     * for all lines in the data. It also checks that the number of values per
     * line does not exceed the maximum allowed amount
     */
    public function testNumValuesPerDataLine() {
        $split_lines = explode("\n", $this->data);
        $first_line_comma_count = substr_count($split_lines[0], ","); // count number of commas in first line
        $all_lines_match = true;
        $max_comma_count = $first_line_comma_count;
        foreach($split_lines as $line) { // make sure each line has same number of commas
            $line_comma_count = substr_count($line, ",");
            if($line_comma_count !== $first_line_comma_count) {
                // if a line does not match same number of values as first line, we know all lines are not matching
                $all_lines_match = false;
            }
            if($max_comma_count < $line_comma_count) {
                // update max comma count if we found a line with more commas than currently in max comma count
                $max_comma_count = $line_comma_count;
            }
        }
        // assert that all lines have matching length and that the maximum comma count (i.e., the maximum
        //   value count between all the data lines) is within maximum specification
        $this->assertTrue($all_lines_match, "Not all lines match in number of values in data!");
        $this->assertTrue($max_comma_count < Config::MAX_VALUES_PER_LINE, "Not all lines are within maximum number of allowed values!");
        $this->assertTrue($max_comma_count > 0, "There must be at least one data value per line!");
    }

    /**
     * This unit test checks that the length of all lines of data does not
     * exceed the maximum allotted number of characters
     */
    public function testDataLineLength() {
        $split_lines = explode("\n", $this->data);
        $all_lines_acceptable_length = true;
        foreach($split_lines as $line) { // make sure each line has same number of commas
            if(strlen($line) > Config::MAX_DATA_LINE_LENGTH) {
                // if a line exceeds maximum data line length in Config, this test fails
                $all_lines_acceptable_length = false;
                break;
            }
        }
        $this->assertTrue($all_lines_acceptable_length, "Not all lines stay under the maximum number of allowed characters!");
    }

    /**
     * This unit test checks that all the values on all the lines of the
     * data are valid numbers
     */
    public function testDataLineValues() {
        $double_regex = '/[-+]?[0-9]*\.?[0-9]+/'; // matches any number value the user can input
        $split_lines = explode("\n", $this->data);
        $all_values_double = true;
        for($i = 0; $i < count($split_lines); $i++) {
            $line = $split_lines[$i];
            // for each line, extract all the values and test all the non x-value
            //   entries for being an accepted number
            $line_contents = explode(",", $line);
            for($j = 1; $j < count($line_contents); $j++) {
                if($i === 0) { // empty values are not acceptable for first line
                    if(preg_match($double_regex, $line_contents[$j]) !== 1) {
                        // we found something that should be a value to graph that is not
                        //   a number if we get here, so report this
                        $all_values_double = false;
                        break;
                    }
                }
                else { // empty values are acceptable for non-first lines, so we must check that as well
                    if(strlen($line_contents[$j]) !== 0 && preg_match($double_regex, $line_contents[$j]) !== 1) {
                        // we found something that should be a value to graph that is not
                        //   a number if we get here, so report this
                        $all_values_double = false;
                        break;
                    }
                }

            }
        }
        $this->assertTrue($all_values_double, "Not all given data values are valid numbers!");
    }

}
?>