/**
 * Validates the chart form on the landing page
 * @returns {boolean} false if bad form input is detected
 */
function validateForm() {
    // first check: title needs to have at least one character
    var t = document.getElementById("chartTitle").value;
    if(t == null || t.trim() == "") {
        document.getElementById('clientErrorMessage').innerHTML =
            '<p>Error: please enter title with at least 1 non-whitespace character</p>';
        setTimeout(function(){
            document.getElementById('clientErrorMessage').innerHTML = '';
        }, 5000);
        return false;
    }

    // second check: make sure title is not too long
    var maxTitleLength = 100;
    if(t.length > maxTitleLength) {
        document.getElementById('clientErrorMessage').innerHTML =
            '<p>Error: title can only have up to ' + maxTitleLength + ' characters</p>';
        setTimeout(function(){
            document.getElementById('clientErrorMessage').innerHTML = '';
        }, 5000);
        return false;
    }

    // third check: make sure data is not completely empty (and has at least one comma)
    var d = document.getElementById("chartData").value;
    if(d == null || d.trim() == "" || d.indexOf(",") === -1 || d.trim().length < 3) {
        document.getElementById('clientErrorMessage').innerHTML =
            '<p>Error: please enter valid chart data with at least one row to graph</p>';
        setTimeout(function(){
            document.getElementById('clientErrorMessage').innerHTML = '';
        }, 5000);
        return false;
    }

    // third check: make sure data is not too long
    // 50 * 80 = 50 max rows * 80 max characters per line; + 100 = including return carriage and newline characters on each line
    var maxDataLength = (50 * 80) + 100;
    if(d.length > maxDataLength) {
        document.getElementById('clientErrorMessage').innerHTML =
            '<p>Error: data is too long. There should be a maximum of 50 data lines with up to 80 characters each</p>';
        setTimeout(function(){
            document.getElementById('clientErrorMessage').innerHTML = '';
        }, 5000);
        return false;
    }

    // let form go to server if the basic checks are met
    return true;
}
