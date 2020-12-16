<?php
class results_grid {
    private $grid;
    private $num_pages;
    private $num_records;
    private $return_page;

    /**
     * construct a grid
     */
    function __construct() {
        $this->num_records = $this->count_all_records();
    } // end __construct()

    /*
     * destroy a grid
     */
    function __destruct() {

    } // end __destruct()

    /**
     * read a page of results and save it to the grid
     * @param
     */
    public function read_page($page) {
        $db = new database();
        $db->dblink();

        // build the query and execute it
        $q = $this->create_subpage_query("SELECT * FROM TRIALS", $page);
        $recs = $db->fselect($q);

        // generate the xml
        $num_recs = count($recs);

        for($i = 0; $i < $num_recs; $i++) {
            $this->grid .= "<row>";

            foreach($recs[$i] as $key => $value) {
                $this->grid .= "<$key>".htmlentities($value)."</$key>";
            } // end foreach
            
            $this->grid .= "</row>";
        } // end for
    } // end read_page()

    /**
     * modifies a trial selection query to return only a page of results
     * @param   string  $q  the query string
     * @return  string  the modified query string
     */
    private function create_subpage_query($q, $page) {
        $new_q = '';

        if($this->num_records <= ROWS_PER_VIEW) {
            $page_num = 1;
            $this->num_pages = 1;
        } else {
            $this->num_pages = ceil($this->num_records / ROWS_PER_VIEW);
            $start_page = ($page_num - 1) * ROWS_PER_VIEW;
            $new_q = $q .= " LIMIT ".$start_page.",".ROWS_PER_VIEW;
        } // end if

        // save the number of the returned page
        $this->return_page = $page_num;

        // return the new query
        return $new_q;
    } // end create_subpage_query()

    /**
     * count the total number of records for the grid
     * @return  int number of records
     */
    private function count_all_records() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("trials", "trial_id");
        return $db->count_recs($result);
    } // end count_all_records()

    /**
     * returns data about the current request
     * @return  string  current request data in xml format
     */
    public function get_params_xml() {
        $next_page = '';
        $prev_page = '';

        // calculate the previous page number
        if($this->return_page != 1) {
            $prev_page = $this->return_page - 1;
        } // end if

        // calculate the next page number
        if($this->num_pages != $this->return_page) {
            $next_page = $this->return_page + 1;
        } // end if

        // return the parameters
        return "<params>".
               "<returned_page>".$this->return_page."</returned_page>".
               "<total_pages>".$this->num_pages."</total_pages>".
               "<items_count>".$this->num_records."</items_count>".
               "<previous_page>".$prev_page."</previous_page>".
               "<next_page>".$next_page."</next_page>".
               "</params>";
    } // end get_params_xml()

    /**
     * returns the entire xml grid
     * @return  string  the grid
     */
    public function get_grid_xml() {
        return "<grid>".$this->grid."</grid>";
    } // end get_grid_xml()

    /**
     * update a grid record
     * @param   array   $params array of update parameters
     */
    public function update_record($params) {
        return true;
    } // end update_record()
} // end class grid
?>