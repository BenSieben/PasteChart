Paste Chart Website

Website which lets users enter data to visually graph out / create
XML for (based on chart.dtd DTD file) / create JSON for /
create a sample JSONP call for

This project relies on Composer. Here is how to set it up
to make sure that the website works properly:

[1] Download and install Composer from their website

[2] Open terminal / command line in root directory of project (i.e., where this Readme.txt is)

[3] Run command "composer install" in terminal / command line to let Composer download necessary files for the project

[4] The website should work properly now if Composer installed successfully


This project also makes use of SimpleTest. Specifically, SimpleTest's UnitTestCase
class is inherited by ChartDataTester to test a string of chart data for validity.
It is used by the FormController class to run tests on user-submitted graph data.
SimpleTest should be installed via Composer (see above steps on using Composer).
To use this ChartDataTester to test any given string of proposed chart data, follow
these steps:

    [1] Create a PHP script where you would like to make use of ChartDataTester
    
    [2] Make sure that Composer's autoload function and ChartDataTester are
        imported to the script
        
    [3] Create a new ChartDataTester with the following code (note that $data
        is the string that will be tested for validity). If you do not specify
        $data to test, it will be defaulted to some sample data:
            # $cdt = new \cs174\hw4\tests\ChartDataTester($data)
        
    [4] After creating the ChartDataTester object, depending on how you would
        like the results to appear, you will call the run function in different
        ways (might need backslash in front of reporter class names depending where you
        are running the code from):
            # $result = $cdt->run(new TextReporter()) // prints to standard output results of test in plain text
            # $result = $cdt->run(new SimpleReporter()) // prints nothing
            # $result = $cdt->run(new HtmlReporter()) // prints to standard output HTML document code for displaying results
        
    [5] The run method will return true if all tests pass. This can be used to
        determine in code whether or not all the tests passed when the ChartDataTester
        was run. Here is one simple example of doing this ($result = what was returned
        by calling the run function)
            # if($result === true) {
            #     echo("All tests passed!");
            # }
            # else {
            #     echo("Not all tests passed!");
            # }
        
    [6] In addition to checking the result of calling the run function, after using run ChartDataTester
        will have an array called $results, which has multiple internal arrays which indicate problematic
        data line(s) for certain checks. To see what keys are used by $results, use
            # $resultsKeys = $cdt->getResultsKeys();
        To get the actual $results array back (AFTER running the test function on data), use
            # $results = $cdt->getResults();
        This will allow for simpler checking of which data line(s) caused the testing to fail
