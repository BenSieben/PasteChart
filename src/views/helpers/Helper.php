<?php
namespace cs174\hw3\views\helpers;

/**
 * Class Helper
 * @package cs174\hw3\views\helpers
 *
 * Abstract superclass for any Helpers in PasteChart website.
 * Helpers assist View of PasteChart website by
 * looping through data to display in the website
 */
abstract class Helper {

    /**
     * Method which takes data (usually array) and renders
     * it (to be implemented in subclasses)
     * @param $data Array the item(s) to render
     * @return String HTML code to render given data
     */
    public abstract function render($data);
}
?>