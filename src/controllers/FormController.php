<?php
namespace cs174\hw4\controllers;

/**
 * Class FormController
 * @package cs174\hw4\controllers
 *
 * Controller which handles taking in and
 * then accepting / rejecting user chart data
 * and then redirecting user to appropriate page
 */
class FormController extends Controller {

    /**
     * Handles form submitted through landing page
     * by analyzing $_REQUEST variable values and
     * then doing the appropriate action in response
     */
    public function handleChartForm() {
        print_r($_REQUEST);
    }
}
?>