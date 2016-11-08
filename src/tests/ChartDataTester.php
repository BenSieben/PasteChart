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
    private $results; // an array which holds results of all the tests

    /**
     * Constructs a new DataTester
     * @param $data String the chart data to run unit tests on
     */
    public function __construct($data) {
        parent::__construct();
        $this->data = $data;
        $this->results = array();
    }

    /**
     * This unit test checks that the number of lines in the data does not
     * exceed the maximum number of allowed lines
     */
    public function testNumDataLines() {
        $this->results['has_acceptable_num_lines'] = substr_count($this->data, "\n") < Config::MAX_DATA_LINES;
        $this->assertTrue($this->results['has_acceptable_num_lines'], "There are too many data lines!");
    }

    /**
     * This unit test checks that the number of values per line is consistent
     * for all lines in the data. It also checks that the number of values per
     * line does not exceed the maximum allowed amount
     */
    public function testNumValuesPerDataLine() {
        $split_lines = explode("\n", $this->data);
        $first_line_comma_count = substr_count($split_lines[0], ","); // count number of commas in first line
        $this->results['all_lines_have_same_num_values'] = true;
        $max_comma_count = $first_line_comma_count;
        foreach($split_lines as $line) { // make sure each line has same number of commas
            $line_comma_count = substr_count($line, ",");
            if($line_comma_count !== $first_line_comma_count) {
                // if a line does not match same number of values as first line, we know all lines are not matching
                $this->results['all_lines_have_same_num_values'] = false;
            }
            if($max_comma_count < $line_comma_count) {
                // update max comma count if we found a line with more commas than currently in max comma count
                $max_comma_count = $line_comma_count;
            }
        }
        // assert that all lines have matching length and that the maximum comma count (i.e., the maximum
        //   value count between all the data lines) is within maximum specification
        $this->assertTrue($this->results['all_lines_have_same_num_values'], "Not all lines match in number of values in data!");

        $this->results['no_line_exceeds_max_count'] = $max_comma_count < Config::MAX_VALUES_PER_LINE;
        $this->results['each_line_has_at_least_one_value'] = $max_comma_count > 0;
        $this->assertTrue($this->results['no_line_exceeds_max_count'], "Not all lines are within maximum number of allowed values!");
        $this->assertTrue($this->results['each_line_has_at_least_one_value'], "There must be at least one data value per line!");
    }

    /**
     * This unit test checks that the length of all lines of data does not
     * exceed the maximum allotted number of characters
     */
    public function testDataLineLength() {
        $split_lines = explode("\n", $this->data);
        $this->results['all_lines_acceptable_length'] = true;
        foreach($split_lines as $line) { // make sure each line has same number of commas
            if(strlen($line) > Config::MAX_DATA_LINE_LENGTH) {
                // if a line exceeds maximum data line length in Config, this test fails
                $this->results['all_lines_acceptable_length'] = false;
                break;
            }
        }
        $this->assertTrue($this->results['all_lines_acceptable_length'], "Not all lines stay under the maximum number of allowed characters!");
    }

    /**
     * This unit test checks that all the values on all the lines of the
     * data are valid numbers
     */
    public function testDataLineValues() {
        $double_regex = '/[-+]?[0-9]*\.?[0-9]+/'; // matches any number value the user can input
        $split_lines = explode("\n", $this->data);
        $this->results['all_values_double'] = true;
        for($i = 0; $i < count($split_lines); $i++) {
            $line = $split_lines[$i];
            // for each line, extract all the values and test all the non x-value
            //   entries for being an accepted number
            $line_contents = explode(",", $line);
            for($j = 1; $j < count($line_contents); $j++) { // start at 1 because 0 index is just the x-label
                if($i === 0) { // empty values are not acceptable for first line
                    if(preg_match($double_regex, $line_contents[$j]) !== 1) {
                        // we found something that should be a value to graph that is not
                        //   a number if we get here, so report this
                        $this->results['all_values_double'] = false;
                        break;
                    }
                }
                else { // empty values are acceptable for non-first lines, so we must check that as well
                    if(strlen($line_contents[$j]) !== 0 && preg_match($double_regex, $line_contents[$j]) !== 1) {
                        // we found something that should be a value to graph that is not
                        //   a number if we get here, so report this
                        $this->results['all_values_double'] = false;
                        break;
                    }
                }

            }
        }
        $this->assertTrue($this->results['all_values_double'], "Not all given data values are valid numbers!");
    }

    /**
     * This unit test checks that each line of code has something
     * for a a x-value (i.e., the label for the values on the line)
     */
    public function testDataXValues() {
        $split_lines = explode("\n", $this->data);
        $this->results['all_lines_have_labels'] = true;
        foreach($split_lines as $line) {
            $label = explode(",", $line)[0];
            if(strlen($label) === 0) { // if label is 0 characters long, that means there is no label on this line
                $this->results['all_lines_have_labels'] = false;
            }
        }
        $this->assertTrue($this->results['all_lines_have_labels'], "Not all lines have labels for the value(s)!");
    }

    /**
     * Returns the results that were obtained from all the tests
     * Use this after calling the run function to see specifically
     * which tests failed in code
     * @return Array<boolean> results from all the tests
     */
    public function getResults() {
        return $this->results;
    }

    /**
     * Returns a string array of all keys used in ChartDataTester's
     * $result array
     * @return Array<String> all keys in the $result array
     */
    public function getResultsKeys() {
        return ['has_acceptable_num_lines',
            'all_lines_have_same_num_values',
            'no_line_exceeds_max_count',
            'each_line_has_at_least_one_value',
            'all_lines_acceptable_length',
            'all_values_double',
            'all_lines_have_labels'];
    }

}
?>