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
     * @param Array $data Array<String> array of data to show in the view
     */
    function render($data) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>PasteChart</title>
    <meta charset="utf-8" />
</head>
<body>
    <h1>PasteChart</h1>
    <p>Share your data in charts!</p>
</body>
</html>
<?php
    }

}
?>