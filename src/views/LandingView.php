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
        // create a variable to hold the text area placeholder because the explanation is very long
        $textAreaPlaceholder = 'Input chart data here!';
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
    <div id="clientErrorMessage"></div> <!-- This div gets filled by validateForm() in landingFormCheck.js if error is detected -->
    <form name="chartForm" action="?c=form" onsubmit="return validateForm()" method="post">
        <label>Chart Title
            <br />
        <input type="text" name="title" value="" />
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