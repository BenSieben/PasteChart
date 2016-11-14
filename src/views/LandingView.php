<?php
namespace cs174\hw4\views;

/**
 * Class LandingView
 * @package cs174\hw4\views
 *
 * The View that displays the landing
 * page for the PasteChart website
 */
class LandingView extends View {

    /**
     * Uses HTMl to draw the landing page for the PasteChart website
     * @param $data Array<String> array of data to show in the view
     */
    function render($data) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>PasteChart</title>
    <meta charset="utf-8" />
    <link rel="icon" href="./src/resources/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="./src/styles/stylesheet.css" />
    <script src="./src/resources/landingFormCheck.js"></script>
</head>
<body>
    <h1>PasteChart</h1>
    <h2>Share your data in charts!</h2>
    <div id="serverErrorMessage"><?= $data['serverError'] ?></div>
    <form name="chartForm" action="?c=form" onsubmit="return validateForm()" method="post">
        <label>Chart Title
            <br />
        <input type="text" id="chartTitle" name="title" value="<?= $data['title'] ?>" />
        </label>
        <br />
        <br />
        <label>Chart Data
            <br />
            <textarea id="chartData" name="chartData" rows="25" cols="80" placeholder="<?= $data['dataPlaceholder'] ?>"><?= $data['chartData'] ?></textarea>
        </label>
        <br />
        <br />
        <input type="submit" value="Share" />
    </form>
    <div id="clientErrorMessage"></div>
</body>
</html>
<?php
    }

}
?>