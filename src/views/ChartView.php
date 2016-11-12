<?php
namespace cs174\hw4\views;
use cs174\hw4\views\elements\ChartLinksElement;

/**
 * Class ChartView
 * @package cs174\hw4\views
 *
 * View for rendering a chart and
 * links to see other kinds of charts
 * for the same data on PasteChart website
 */
class ChartView extends View {

    /**
     * Uses HTMl to draw the chart page for the PasteChart website
     * @param $data Array<String> array of data to show in the view
     */
    public function render($data)
    {
        ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $data['hash'] ?> <?= $data['chartType'] ?> - PasteChart</title>
    <meta charset="utf-8"/>
    <link rel="icon" href="./src/resources/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="./src/styles/stylesheet.css"/>
    <script src="./src/resources/chart.js"></script>
</head>
<body>
    <h1><?= $data['hash'] ?> <?= $data['chartType'] ?> - <a href="?c=landing">PasteChart</a></h1>
<?php
        if (isset($data['noDBEntry'])) { // if this index is set, that means link is not valid
?>
    <div id="serverErrorMessage"><?= $data['noDBEntry'] ?></div>
<?php
        } else {
            if (in_array($data['chartType'], $data['drawChartTypes'])) { // check if we should use chart.js
?>
    <div id="chart"></div>
    <script type="text/javascript">
        graph = new Chart('chart',
            <?= $data['chartDataJSObjectText'] ?>,
            {'title': '<?= $data['title'] ?>', 'type': '<?= $data['chartType'] ?>'});
        graph.draw();
    </script>
<?php
            }
            else { // otherwise, we do not use chart.js
        ?>
    <pre id="code"><?= $data['code'] ?></pre>
<?php
            }
            // render the chart links, with all the data provided by chart controller
            $cle = new ChartLinksElement($this);
            echo($cle->render($data));
        }?>
    <br/>
    <br/>
</body>
</html>
<?php
    }
}
?>