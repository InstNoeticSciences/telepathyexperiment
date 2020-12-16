<?php
    require_once('lib/util.php');
    require_once('lib/error_handler.php');
    
    require_once('inc/config.php');
    require_once('classes/database.php');
    require_once('classes/simple_grid.php');

    // evaluate the action parameter
    if (!isset($_GET['action'])) {
        echo 'Server error: client command missing.';
        exit;
    } else {
        // store the action to be performed
        $action = $_GET['action'];
    } // end if

    // evaluate the source parameter
    if (!isset($_GET['source'])) {
        echo 'Server error: data source missing.';
        exit;
    } else {
        // store the data source to be read
        $source = $_GET['source'];
    } // end if

    // evaluate the filter parameter
    if (!isset($_GET['filter'])) {
        echo 'Server error: data filter missing.';
        exit;
    } else {
        // store the filter to be used
        $filter = $_GET['filter'];
    } // end if

    // create simple grid instance
    $grid = new simple_grid($source, 6, $filter);
    
    // valid action values are FEED_GRID_PAGE and UPDATE_ROW
    if ($action == 'FEED_GRID_PAGE') {
        // retrieve the page number
        $page = $_GET['page'];

        // read the data from the page
        if(!$grid->populate_data($page)) {
            echo 'Server error: failed to read data';
        } // end if
    } else {
        echo 'Server error: client command unrecognized.';
    } // end if

    // clear the output
    if(ob_get_length()) {
        ob_clean();
    } // end if

    // headers are sent to prevent browsers from caching
    header('Expires: Fri, 25 Dec 1980 00:00:00 GMT'); // time in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Content-Type: text/xml');

    // generate the output in XML format
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
    echo '<data>';
    echo '<action>' . $action . '</action>';
    echo $grid->params_xml();
    echo $grid->data_xml($page);
    echo '</data>';
?>

