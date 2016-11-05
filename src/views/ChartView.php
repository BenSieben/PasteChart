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
    <title>PasteChart</title>
    <meta charset="utf-8" />
    <link rel="icon" href="src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="src/styles/stylesheet.css" />
</head>
<body>
<h1>PasteChart</h1>
<h2>Share your data in charts!</h2>
<form name="chartForm" method="post" action="?c=form">
    <label>Chart Title
        <br />
        <input type="text" name="title" />
    </label>
    <br />
    <br />
    <label>Chart Data
        <br />
        <textarea name="chartData" rows="25" cols="80" placeholder="<?= $textAreaPlaceholder ?>"></textarea>
    </label>
    <br />
    <br />
    <input type="submit" value="Share" />
</form>
</body>
</html>
<?php
    }
}
?>