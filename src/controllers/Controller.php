<?php
namespace cs174\hw4\controllers;
use cs174\hw4\configs\Config as Config;

/**
 * Class Controller
 * @package cs174\hw4\controllers
 *
 * Superclass for any class used
 * as a Controller for the PasteChart
 * website
 */
class Controller {

    /**
     * This function will look at the current
     * values in PHP super globals such as $_REQUEST
     * to determine which Controller subclass to call
     * to handle the forms
     */
    public function processForms() {
        if(isset($_REQUEST['c'])) { // c is name of Controller to call
            if(strcmp($_REQUEST['c'], 'landing') === 0) { // use LandingController
                $lc = new LandingController();
                $lc->callView();
            }
            else if(strcmp($_REQUEST['c'], 'form') === 0) { // use FormController
                $fc = new FormController();
                $fc->handleChartForm();
            }
            else if (strcmp($_REQUEST['c'], 'chart') === 0) { // use ChartController
                if(isset($_REQUEST['a']) && isset($_REQUEST['arg1']) && isset($_REQUEST['arg2'])) {
                    $cc = new ChartController();
                    if(strcmp($_REQUEST['a'], 'show') === 0) { // make sure action chosen is 'show'
                        if (isset($_REQUEST['arg3'])) { // if arg3 is set, be sure to pass it to show function as well
                            $cc->show($_REQUEST['arg1'], $_REQUEST['arg2'], $_REQUEST['arg3']);
                        } else { // if arg3 is not set, do not pass it to show function
                            $cc->show($_REQUEST['arg1'], $_REQUEST['arg2']);
                        }
                    }
                    else { // if $_REQUEST['a'] is not a known value, then show default landing page
                        header("Location: " . Config::BASE_URL . "?c=landing");
                    }
                }
                else { // if $_REQUEST['a'] or $_REQUEST['arg1'] or $_REQUEST['arg2'] is not set, then show default landing page
                    header("Location: " . Config::BASE_URL . "?c=landing");
                }
            }
        }
        else { // if $_REQUEST['c'] is not set, then show default landing page
            header("Location: " . Config::BASE_URL . "?c=landing");
        }
    }
}
?>