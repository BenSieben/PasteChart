<?php
namespace cs174\hw4\views\elements;

/**
 * Class Element
 * @package cs174\hw4\views\elements
 *
 * Superclass for any Elements used
 * in the PasteChart website.
 * Elements are used for Views
 * in reusable assets
 */
class Element {

    public $view; // reference to view the element is currently on

    /**
     * Creates a new Element
     * @param $view \cs174\hw4\views\View reference to the view which is using this element
     */
    public function __construct($view) {
        $this->view = $view;
    }
}
?>