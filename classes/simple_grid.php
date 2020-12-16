<?php
class simple_grid {
    private $source;
    private $header = array();
    private $data = array();

    private $filter;
    private $page_rows;
    private $page_count;
    private $return_page;
    
    /**
     * construct a simple grid object
     * @param   string  $source     a string specifying the source table.
     * @param   int     $page_rows  the number of rows per page
     * @param   string  $filter     an sql where clause to filter the data
     */
    function simple_grid($source, $page_rows, $filter) {
        $this->source = $source;
        $this->filter = $filter;
        $this->page_rows = 10000; /** ($page_rows <= 0) ? 6 : $page_rows; */

        $db = new database();
        $db->dblink();

        $result = $db->count($source, $filter);
        $recs = $db->fetch_objects($result);
        $this->page_count = ceil($recs[0]->number_of_records / $page_rows);
    } // end simple_grid()

    /**
     * populate the data table with data for a given page
     * @param   int $page   the page number to read
     * @return  boolean true|false
     */
    function populate_data($page) {
        if(isset($this->source)) {
            // reset the data and header arrays
            unset($this->header);
            unset($this->data);

            // connect to the database
            $db = new database();
            $db->dblink();
                        
            // calculate the starting row
            if($page == 1) {
                $start_row = 0;
            } else {
                $start_row = $this->page_rows * ($page - 1);
            } // end if

            // read the raw data
            $result = $db->get_recs($this->source, "*", 
                                    $this->filter, "",
                                    $start_row,
                                    $this->page_rows);

            $recs = $db->fetch_objects($result);

            if(is_array($recs)) {
                // populate the default column headers
                $i = 0;
                foreach($recs[0] as $key => $value) {
                    $this->header[$i] = $key;
                    $i++;
                } // end foreach

                // populate the data rows
                $n = count($recs);
                for($r = 0; $r < $n; $r++) {
                    $c = 0;
                    foreach($recs[$r] as $key => $value) {
                        $this->data[$r][$c] = $value;
                        $c++;
                    } // end foreach
                } // end for

                // set the current page
                $this->return_page = $page;

                // sort the data
                sort($this->data);
                
                // everything OK
                return true;
            } // end if
        } // end if

        // no data read
        return false;
    } // end populate_date()

    /**
     * get the source table name
     * @return  string  the source table name
     */
    function get_source() {
        return $this->source;
    } // end get_source()

    /**
     * set the source table name
     * @param   string  $source the source table name
     */
    function set_source($source) {
        $this->source = $source;
    } // end set_source()

    /**
     * get a column heading value
     * @param   int $c  the column number
     * @return  string  the heading value or null
     */
    function get_heading($c) {
        if(is_array($this->header)) {
            return isset($this->header[$c]) ? $this->header[$c] : null;
        } // end if

        // no headings
        return null;
    } // end get_heading()

    /**
     * set a column heading value
     * @param   int     $c    the column number
     * @param   string  $v    the column heading value
     */
    function set_heading($c, $v) {
        $this->header[$c] = $v;
    } // end set_heading()

    /**
     * get the value in a cell
     * @param   $r  int the row number
     * @param   $r  int the column number
     * @return  string  the value in the cell or null
     */
    function get_cell($r, $c) {
        if(is_array($this->data)) {
            return isset($this->data[$r][$c]) ? $this->data[$r][$c] : null;
        } // end if

        // empty data set
        return null;
    } // end get_cell()

    /**
     * set the value in a cell
     * @param   $r  int     the row number
     * @param   $c  int     the column number
     * @param   $v  string  the value for the cell
     * @return  boolean true|false
     */
    function set_cell($r, $c, $v) {
        if(isset($this->data[$r][$c])) {
            $this->data[$r][$c] = $v;
            return true;
        } // end if

        return false;
    } // end set_cell()

    /**
     * get the number of rows per page
     * @return  int number of rows per page
     */
    function get_page_rows() {
        return $this->page_rows;
    } // end get_page_rows()

    /**
     * get the number of pages required for the data
     * @return  int  number of pages required
     */
    function get_page_count() {
        return $this->page_count;
    } // end get_page_count()

    /**
     * parse a page of the grid into xml format
     * @param   int     $page   the page to parse
     * @return  string  xml-formatted data
     */
    function data_xml($page) {
        $cols = count($this->header);
        $rows = count($this->data);

        $xml = "<grid>";

        for($r = 0; $r < $rows; $r++) {
            $xml .= "<row>";

            for($c = 0; $c < $cols; $c++) {
                $xml .= "<".$this->header[$c].">";
                $xml .= htmlentities($this->data[$r][$c]);
                $xml .= "</".$this->header[$c].">";
            } // end for

            $xml .= "</row>";
        } // end for
        
        $xml .= "</grid>";

        return $xml;
    } // end data_xml()

    /**
     * parse the grid parameters into xml format
     * @return  string  xml-formatted parameters
     */
    function params_xml() {
        $next_page = 0;
        $prev_page = 0;

        // calculate the next page
        if($this->return_page < $this->page_count) {
            $next_page = $this->return_page + 1;
        }

        // calculate the previous page
        if($this->return_page > 1) {
            $prev_page = $this->return_page - 1;
        }

        // generate the xml
        $xml = '<params>';
        $xml .= '<return_page>'.$this->return_page.'</return_page>';
        $xml .= '<total_pages>'.$this->page_count.'</total_pages>';
        $xml .= '<next_page>'.$next_page.'</next_page>';
        $xml .= '<prev_page>'.$prev_page.'</prev_page>';
        $xml .= '</params>';

        return $xml;
    } // end params_xml()
} // end simple_grid
?>
