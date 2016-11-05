<?php
namespace cs174\hw4\views;

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
    public function render($data) {
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $data['hash'] ?> <?= $data['chartType'] ?> - PasteChart</title>
    <meta charset="utf-8" />
    <link rel="icon" href="./src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="./src/styles/stylesheet.css" />
</head>
<body>
    <h1><?= $data['hash'] ?> <?= $data['chartType'] ?> - <a href="?c=landing">PasteChart</a></h1>
    <h2>Chart goes here</h2>
    <h3>Share your chart and data at the URLs below:</h3>
    <p>As a LineGraph:</p>
    <p><a href="?c=chart&a=show&arg1=LineGraph&arg2=<?= $data['hash'] ?>">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=LineGraph&amp;arg2=<?= $data['hash'] ?>
    </a></p>
    <p>As a PointGraph:</p>
    <p><a href="?c=chart&a=show&arg1=PointGraph&arg2=<?= $data['hash'] ?>">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=PointGraph&amp;arg2=<?= $data['hash'] ?>
    </a></p>
    <p>As a Histogram:</p>
    <p><a href="?c=chart&a=show&arg1=Histogram&arg2=<?= $data['hash'] ?>">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=Histogram&amp;arg2=<?= $data['hash'] ?>
    </a></p>
    <p>As XML data:</p>
    <p><a href="?c=chart&a=show&arg1=xml&arg2=<?= $data['hash'] ?>">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=xml&amp;arg2=<?= $data['hash'] ?>
    </a></p>
    <p>As JSON data:</p>
    <p><a href="?c=chart&a=show&arg1=json&arg2=<?= $data['hash'] ?>">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=json&amp;arg2=<?= $data['hash'] ?>
    </a></p>
    <p>As JSONP data:</p>
    <p><a href="?c=chart&a=show&arg1=jsonp&arg2=<?= $data['hash'] ?>&arg3=javascript_callback">
            <?= $data['baseURL'] ?>/?c=chart&amp;a=show&amp;arg1=jsonp&amp;arg2=<?= $data['hash'] ?>&amp;arg3=javascript_callback
    </a></p>
</body>
</html>
<?php
    }
}
?>