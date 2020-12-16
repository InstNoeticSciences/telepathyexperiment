<?php
class config_file {
    private $path;
    private $values;

    /**
     * create a configuration file object
     * @param   string  $path   file name and path
     */
    function config_file($path) {
        $this->path = $path;
        $this->values = array();
    } // end config_file()

    /**
     * append a configuration key/value pair to the file
     * @param   string  $key    key (first value in define())
     * @param   string  $value  value (second value in define())
     */
    function add_value($key, $value) {
        if(!array_key_exists($key, $this->values)) {
            $this->values[$key] = $value;
        } // end if
    } // end add_value()

    /**
     * remove a key/value pair from the file
     * @param   string  $key    key (first value in define())
     */
    function remove_value($key) {
        if(isset($this->values[$key])) {
            unset($this->value[$key]);
        } // end if
    } // end remove_value()

    /**
     * return the value for a given key
     * @param   string  $key    key (first value in define())
     * @return  string  value for the given key
     */
    function get_value($key) {
        if(isset($this->values[$key])) {
            return(str_replace("\"", "", $this->values[$key]));
        } // end if

        return null;
    } // end get_value()

    /**
     * change the value for a given key
     * @param   string  $key    key (first value in define())
     * @param   string  $value  value (second value in define())
     * @return  boolean true|false
     */
    function set_value($key, $value) {
        if(array_key_exists($key, $this->values)) {
            $this->values[$key] = $value;
            return true;
        } // end if

        return false;
    } // end set_value()

    /**
     * return the values array
     * @return  array   the array of configuration key/value pairs
     */
    function get_values() {
        return $this->values;
    } // end get_values()
    
    /**
     * reset the key/value array
     */
    function reset_values() {
        if(isset($this->values)) {
            unset($this->values);
        } // end if
    } // end reset_values()

    /**
     * write the configuration values to the given file
     * @return  boolean true|false
     */
    function write_file() {
        if(isset($this->path) && isset($this->values)) {
            // always overwrite the file with the latest values
            $handle = fopen($this->path, "w");

            // exit if the file was not opened
            if(!isset($handle)) {
                return false;
            } // end if

            // write the header
            fwrite($handle, "<?php\n");
            
            // write the values
            foreach($this->values as $key => $value) {
                // remove undesirable characters from the value
                $value = str_replace("\"", "", $value);
                $value = str_replace("\n", "", $value);

                // remove undesirable characters from the key
                $key = str_replace("<?php", "", $key);
                $key = str_replace("?>", "", $key);

                // write whatever remains
                if(!empty($key) && !empty($value)) {
                    fwrite($handle, "define(\"$key\",\"$value\");\n");
                } // end if
            } // end foreach

            // write the footer
            fwrite($handle, "?>");
            
            // close the file
            fclose($handle);

            return true;
        } // end if

        return false;
    } // end write_file()

    /**
     * read the configuration file
     * @return  boolean true|false
     */
    function read_file() {
        $contents = '';

        if(isset($this->path)) {
            // remove existing configuration values if required
            if(isset($this->values)) {
                unset($this->values);
            } // end if

            $handle = fopen($this->path, "rb");

            // exit if the file could not be opened
            if(!isset($handle)) {
                return false;
            } // end if

            while(!feof($handle)) {
                $contents .= fgets($handle, 4096);
            }

            fclose($handle);
        } // end if

        // parse the contents into the values array
        if(strlen($contents) > 0) {
            $this->parse_file($contents);
        } // end if
    } // end read_file()

    /**
     * parse the file content into the values array
     * @param   string  $contents   the contents of the config. file
     * @return  boolean true|false
     */
    private function parse_file($contents) {
        if(isset($contents)) {
            $array_con = explode('define(', $contents);
            $size = count($array_con);

            // split the file into key/value pairs
            for($i = 0; $i < $size; $i++) {
                // remove unwanted values from the key/value pair
                $key_value = explode(",", str_replace(");", "", $array_con[$i]));
                $key_value[0] = str_replace("\"", "", $key_value[0]);
                $key_value[1] = str_replace("?>", "", $key_value[1]);

                // do the split
                $this->values[$key_value[0]] = $key_value[1];
            } // end for

            return true;
        } // end if

        return false;
    } // end parse_file()

    /**
     * move the file to a given location
     * @return  boolean true|false
     */
    function move_file() {

    } // end move_file()

    /**
     * delete the file from its current location
     * @return  boolean true|false
     */
    function delete_file() {

    } // end delete_file()

    /**
     * set the path value
     * @param   string  $path   the path and filename
     */
    function set_path($path) {
        $this->path = $path;
    } // end set_path()

    /**
     * get the path value
     * @return  string  the path and filename
     */
    function get_path() {
        return $this->path;
    } // end get_path()
} // end config_file
?>
