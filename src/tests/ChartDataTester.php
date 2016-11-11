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
     * Adds the following to the results array:
     *      ['lines_after_maximum_allowed_lines']: array of line(s) that come after the cutoff for maximum lines
     */
    public function testNumDataLines() {
        // results array will be updated with this during the test
        $this->results['lines_after_maximum_allowed_lines'] = [];

        $has_acceptable_num_lines = (substr_count($this->data, "\n") < Config::MAX_DATA_LINES);
        $this->assertTrue($has_acceptable_num_lines, "There are too many data lines!");

        // now mark excess line(s) in results array
        // start looping at line number MAX_DATA_LINES to capture everything starting from first excess line
        $split_lines = explode("\n", $this->data);
        for($i = Config::MAX_DATA_LINES; $i < count($split_lines); $i++) {
            array_push($this->results['lines_after_maximum_allowed_lines'], $i);
        }
    }

    /**
     * This unit test checks that the number of values per line is consistent
     * for all lines in the data. It also checks that the number of values per
     * line does not exceed the maximum allowed amount
     * Adds the following to the results array:
     *      ['lines_with_non_matching_values']: array of line(s) in data that do not have a matching
     *          number of values as the first line
     *      ['lines_with_too_many_values']: array of line(s) with too many values
     *      ['lines_with_too_few_values']: array of line(s) with too few (i.e., zero) values
     */
    public function testNumValuesPerDataLine() {
        // results array will be updated at these indexes during this test
        $this->results['lines_with_non_matching_values'] = []; // keeps track of lines with num values not matching first line
        $this->results['lines_with_too_many_values'] = []; // keeps track of lines with too many values
        $this->results['lines_with_too_few_values'] = []; // keeps track of lines with too few values

        $split_lines = explode("\n", $this->data);
        $first_line_comma_count = substr_count($split_lines[0], ","); // count number of commas in first line

        // check if first line has too many commas or not (too many values)
        if($first_line_comma_count <= Config::MAX_VALUES_PER_LINE) {
            $this->results['lines_with_too_many_values'] = []; // acceptable number of values
        }
        else {
            $this->results['lines_with_too_many_values'] = [0]; // unacceptable number of values, so mark line 0 as bad
        }

        $all_lines_have_same_num_values = true;
        $max_comma_count = $first_line_comma_count;
        for($i = 1; $i < count($split_lines); $i++) { // make sure each non-first line has same number of values as first line
            $line = $split_lines[$i];
            $line_comma_count = substr_count($line, ",");

            if($line_comma_count !== $first_line_comma_count) {
                // if a line does not match same number of values as first line, we know all lines are not matching
                //   also mark it as problematic in results array
                array_push($this->results['lines_with_non_matching_values'], $i);
                $all_lines_have_same_num_values = false;
            }

            if($line_comma_count > Config::MAX_VALUES_PER_LINE) {
                // if a line has too many values, mark it as bad in results array
                array_push($this->results['lines_with_too_many_values'], $i);
            }

            if($line_comma_count > $max_comma_count){
                // update max comma count if we found a line with more commas than currently in max comma count
                $max_comma_count = $line_comma_count;
            }

            if($line_comma_count < 1) {
                // if a line has less than 1 commas, it has no values, so mark it in results array
                array_push($this->results['lines_with_too_few_values'], $i);
            }
        }
        // assert that all lines have matching length and that the maximum comma count (i.e., the maximum
        //   value count between all the data lines) is within maximum specification
        $this->assertTrue($all_lines_have_same_num_values, "Not all lines match in number of values in data!");

        $no_line_exceeds_max_count = $max_comma_count <= Config::MAX_VALUES_PER_LINE;
        $no_line_has_no_values = count($this->results['lines_with_too_few_values']) === 0;
        $this->assertTrue($no_line_exceeds_max_count, "Not all lines are within maximum number of allowed values!");
        $this->assertTrue($no_line_has_no_values, "There must be at least one data value per line!");
    }

    /**
     * This unit test checks that the length of all lines of data does not
     * exceed the maximum allotted number of characters
     * Adds the following to the results array:
     *      ['lines_with_too_many_characters']: array of line(s) that exceed maximum allowed number of characters
     */
    public function testDataLineLength() {
        // results array will be updated with this during the test
        $this->results['lines_with_too_many_characters'] = [];

        $split_lines = explode("\n", $this->data);
        $all_lines_acceptable_length = true;
        for($i = 0; $i < count($split_lines); $i++) { // make sure each line is not too long
            $line = $split_lines[$i];
            if(strlen($line) > Config::MAX_DATA_LINE_LENGTH) {
                // if a line exceeds maximum data line length in Config, this test fails
                // also update results to indicate this line as an unacceptable length
                $all_lines_acceptable_length = false;
                array_push($this->results['lines_with_too_many_characters'], $i);
            }
        }
        $this->assertTrue($all_lines_acceptable_length, "Not all lines stay under the maximum number of allowed characters!");
    }

    /**
     * This unit test checks that all the values on all the lines of the
     * data are valid numbers
     * Adds the following to the results array:
     *      ['lines_with_non_numeric_values']: array of line(s) that have values which are not numeric
     */
    public function testDataLineValues() {
        // results array will be updated with this during the test
        $this->results['lines_with_non_numeric_values'] = [];

        $double_regex = '/^[-+]?[0-9]*\.?[0-9]+$/'; // matches any number value the user can input
        $split_lines = explode("\n", $this->data);
        $all_values_double = true;
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
                        // also add the line to the result array as having a non-numeric value
                        $all_values_double = false;
                        array_push($this->results['lines_with_non_numeric_values'], $i);
                    }
                }
                else { // empty values are acceptable for non-first lines, so we must check that as well
                    if(strlen($line_contents[$j]) !== 0 && preg_match($double_regex, $line_contents[$j]) !== 1) {
                        // we found something that should be a value to graph that is not
                        //   a number if we get here, so report this
                        // also add the line to the result array as having a non-numeric value
                        $all_values_double = false;
                        array_push($this->results['lines_with_non_numeric_values'], $i);
                    }
                }

            }
        }
        $this->assertTrue($all_values_double, "Not all given data values are valid numbers!");
    }

    /**
     * This unit test checks that each line of code has something
     * for a a x-value (i.e., the label for the values on the line)
     * Adds the following to the results array:
     *      ['lines_with_invalid_labeling']: array of line(s) that do not have a valid label
     */
    public function testDataXValues() {
        // results array will be updated with this during the test
        $this->results['lines_with_invalid_labeling'] = [];

        $split_lines = explode("\n", $this->data);
        $all_lines_have_labels = true;
        for($i = 0; $i < count($split_lines); $i++) {
            $line = $split_lines[$i];
            $label = explode(",", $line)[0];
            if(strlen($label) === 0) { // if label is 0 characters long, that means there is no label on this line
                $all_lines_have_labels = false;
                // also add this line to the results array of lines with invalid labeling
                array_push($this->results['lines_with_invalid_labeling'], $i);
            }
        }
        $this->assertTrue($all_lines_have_labels, "Not all lines have labels for the value(s)!");
    }

    /**
     * This unit test checks that each row of data
     * has a unique label, as this is required for chart.js
     * to render correctly
     * Adds the following to the results array:
     *      ['lines_with_non_unique_label']: array of line(s) that use a non-unique label
     */
    public function testXLabels() {
        // results array will be updated with this during the test
        $this->results['lines_with_non_unique_label'] = [];

        $split_lines = explode("\n", $this->data);
        $all_unique_labels = true;
        $found_labels = []; // this will accumulate each new label
        for($i = 0; $i < count($split_lines); $i++) {
            $line = $split_lines[$i];
            $label = explode(",", $line)[0];
            if(in_array($label, $found_labels)) { // if true, label is a repeated label
                $all_unique_labels = false;
                // also add this line to the results array of lines with non-unique labeling
                array_push($this->results['lines_with_non_unique_label'], $i);
            }
            else { // label is not a repeated label, so we must add it to found_labels
                array_push($found_labels, $label);
            }
        }
        $this->assertTrue($all_unique_labels, "Not all lines have unique labels!");
    }

    /**
     * Returns the results that were obtained from all the tests
     * Use this after calling the run function to see specifically
     * which tests failed in code. For each key, an array of
     * line numbers will be given (starting at line 0) to indicate
     * problematic lines
     * @return Array<Array<int>> results from all the tests, indicating
     * which lines (if any) are problematic
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
        return ['lines_after_maximum_allowed_lines',
            'lines_with_non_matching_values',
            'lines_with_too_many_values',
            'lines_with_too_few_values',
            'lines_with_too_many_characters',
            'lines_with_non_numeric_values',
            'lines_with_invalid_labeling',
            'lines_with_non_unique_label'
            ];
    }

}
?>