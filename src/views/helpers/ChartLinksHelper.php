<?php
namespace cs174\hw4\views\helpers;

/**
 * Class ChartLinksHelper
 * @package cs174\hw4\views\helpers
 *
 * Takes array of chart links to display
 * and outputs the corresponding HTML
 * code
 */
class ChartLinksHelper extends Helper {

    /**
     * Creates HTML code
     * @param $data Array<String> chart link information to render
     *      index 'chartLink' = array of  arrays of chart type name, link to chart, and text to show for link
     *          for each kind of chart
     * @return String HTML code for all chart links
     */
    public function render($data) {
        $output = "";
        foreach($data['chartLink'] as $link) {
            $output .= "    <p>" . $link[0] . "</p>\n";
            $output .= "    <p><a href=\"" . $link[1] . "\">\n";
            $output .= "        " . $link[2] . "\n";
            $output .= "    </a></p>\n";
        }
        return $output;
    }

}
?>