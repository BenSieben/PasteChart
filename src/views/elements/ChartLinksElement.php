<?php
namespace cs174\hw4\views\elements;
use cs174\hw4\views\helpers\ChartLinksHelper as ChartLinksHelper;

/**
 * Class ChartLinksElement
 * @package cs174\hw4\views\elements
 *
 * Creates HTML code for list of chart data
 * view types, given appropriate data along
 * with a header above the links
 */
class ChartLinksElement extends Element {

    /**
     * Returns HTML code for list of chart data view types,
     * as well as a header above all the links
     * @param $data Array<String> information to render
     *      index 'chartHeader' = title for links
     *      index 'chartLink' = array of  arrays of chart type name, link to chart, and text to show for link
     *          for each kind of chart
     * @return String HTML code for the list of chart data view types
     */
    public function render($data) {
        $output = ("    <h3>" . $data['chartHeader'] . "</h3>\n"); // render header
        $clh = new ChartLinksHelper();
        $output .= $clh->render($data); // render chart links
        return $output;
    }
}
?>