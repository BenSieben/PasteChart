<?php
namespace cs174\hw4\controllers;
use cs174\hw4\views\LandingView as LandingView;

/**
 * Class LandingController
 * @package cs174\hw4\controllers
 *
 * Controller for landing view of PasteChart website
 */
class LandingController extends Controller {

    /**
     * Looks at current values in PHP super globals
     * such as $_REQUEST to set up data for the
     * landing View and perform necessary tasks
     * with Model(s), then calls the landing View
     * for rendering
     */
    public function callView() {
        $view = new LandingView();
        $view->render(null);
    }
}
?>