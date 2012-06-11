<?php
/**
 *  --------------------------------------------------------------   
 *  Export Import MYSQL Database 0.1.1
 *  Released    : August 2009
 *  Last Update : Feb 2010
 *  ---------------------------------------------------------------
 *  Run on PHP 5
 *  ---------------------------------------------------------------
 *  Developed by: Reazaul Karim - Rubel
 *  URI: http://reazulk.wordpress.com
 *  Contact: reazulk@gmail.com
 *  Call: +8801717403818
 *  ---------------------------------------------------------------
 *  License text http://www.opensource.org/licenses/mit-license.php 
 *  About MIT license <http://en.wikipedia.org/wiki/MIT_License/>
 *  ---------------------------------------------------------------
 */

class dbimexport
{
    // Database configuration
    private $db_config = Array();

    // Databse object
    private $link = NULL;

    // Download flag
    private $download = false;

    // Path and Extension
    private $download_path = "";
    private $file_name = NULL;
    private $file_ext = ".xml";
    private $import_path = "";

    /**
     * Constract dbimexport constactor
     *
     */
    public function __construct() { }

    /**
     * Add Common values
     */ 
    public function addValue( $key = NULL ,$val = NULL )
    {
        if( !is_null( $key ) )
        {
            $this->$key = $val;
        }

        // Return Base referance
        return $this;
    }

    /**
     * Set dabase connection
     * Simple single tone functin
     *
     * @access private
     * @return null
     */
    private function setconnection()
    {
       if( !isset( $this->link))
       {
            // Create Database connection
            $this->link = mysql_connect($this->db_config['host'], $this->db_config['user'], $this->db_config['password']);

            if (!$this->link) 
            {
                die('Not connected : ' . mysql_error());
            }

            // Select databse
            $db_selected = mysql_select_db($this->db_config['database'], $this->link);

            if (!$db_selected) 
            {
                die ("Can't use {$db_config['database']} : " . mysql_error());
            }
        }
    }
    
    /**
     * Execute SQL comments
     *
     * @prameter sql string
     * @return object
     */
    private function execute( $sql )
    {
      return mysql_query( $sql );
    }

    /**
     * Export data
     *
     * @return null
     */
    public function export()
    {
        
        $dom = new DOMDocument ( '1.0' );
        $database_name = $this->db_config['database'];
        $this->setconnection();

        // Create Database node
        $database = $dom->createElement ( 'database' );
        $database = $dom->appendChild ( $database );
        $database->setAttribute ( 'name', $database_name);

        //create schema node
        $schema = $dom->createElement ( 'schema' );
        $schema = $dom->appendChild ( $schema );
        
        /* ---- CREATE SCHEMA ---- */
        // Fetch table informaton 
        $tableQuery = $this->execute ( "SHOW TABLES FROM {$this->db_config['database']}" );        

        while ( $tableRow = mysql_fetch_row ( $tableQuery ) )
        {
            //Table Node
            $table = $dom->createElement ( 'table' );
            $table = $dom->appendChild ( $table );
            $table->setAttribute ( 'name', $tableRow [ 0 ] );
            
            //Fetch table description
            $fieldQuery = $this->execute ( "DESCRIBE $tableRow[0]" );
            
            while ( $fieldRow = mysql_fetch_assoc ( $fieldQuery ) )
            {
                //Create Field node
                $field = $dom->createElement ( 'field' );
                $field = $dom->appendChild ( $field );
                $field->setAttribute ( 'name', $fieldRow [ 'Field' ] );
                $field->setAttribute ( 'name', $fieldRow [ 'Field' ] );
                $field->setAttribute ( 'type', $fieldRow [ 'Type' ]);
                $field->setAttribute ( 'null', strtolower ( $fieldRow [ 'Null' ] ) );
                
                //set the default
                if ( $fieldRow [ 'Default' ] != '' )
                {
                    $field->setAttribute ( 'default', strtolower ( $fieldRow [ 'Default' ] ) );
                }

                //set the key
                if ( $fieldRow [ 'Key' ] != '' )
                {
                    $field->setAttribute ( 'key', strtolower ( $fieldRow [ 'Key' ] ) );
                }

                //set the value/length attribute
                if ( $fieldRow [ 'Extra' ] != '' )
                {
                    $field->setAttribute ( 'extra', strtolower ( $fieldRow [ 'Extra' ] ) );
                }
                
                //put the field inside of the table
                $table->appendChild ( $field );
            }
            
            //put the table inside of the schema
            $schema->appendChild ( $table );
        }
        
        // Add Scma to database
        $database->appendChild ( $schema );
    
        
        /* ------- Populate Data ------ */
        $tableQuery = $this->execute ( "SHOW TABLES FROM {$this->db_config['database']}" );
        
        // Create Data node
        $data = $dom->createElement ( 'data' );
        $data = $dom->appendChild ( $data );
        $dom->appendChild ( $data );

        while ( $tableRow = mysql_fetch_row ( $tableQuery ) )
        {
            // Read Table Scma again
            $descQuery = $this->execute ( "DESCRIBE {$tableRow[0]}" );
            $schema = Array();
            while ( $row = mysql_fetch_assoc ( $descQuery ) )
            {
               $schema[$row['Field']] = array
                                        (
                                            "Type" =>$row['Type'],
                                            "Null" =>$row['Null'],
                                            "Key" =>$row['Key'],
                                            "Default" =>$row['Default'],
                                            "Extra" =>$row['Extra']
                                        );
            }

            $rows = $this->execute ( "SELECT * FROM {$tableRow[0]}" );            
            $table = $dom->createElement ($tableRow[0]);
            $table = $dom->appendChild ( $table );

            $data->appendChild ( $table );

            while ( $row = mysql_fetch_assoc ( $rows ) )
            {
                //Create Row node
                $data_row = $dom->createElement ( 'row' );
                $data_row = $dom->appendChild ( $data_row );
                $table ->appendChild (  $data_row );
                
                // Create Row Node
                foreach( $row as $key => $val )
                {
                    if( strstr($schema[$key]['Type'], 'int') || strstr($schema[$key]['Type'], 'float') || strstr($schema[$key]['Type'], 'date') || strstr($schema[$key]['Type'], 'time') )
                    {
                        $field = $dom->createElement ($key,$val);
                        $field = $dom->appendChild ( $field );
                        $data_row->appendChild ( $field );

                    }
                    else
                    {
                        $field = $dom->createElement ($key);
                        $field = $dom->appendChild ( $field );
                        $data_row->appendChild ( $field );
                        $cdataNode = $dom->createCDATASection($this->encode($val));
                        $cdataNode = $dom->appendChild ( $cdataNode );
                        $field->appendChild ( $cdataNode );
                    }                  
                }
            }
        }
        
        // Add Data to root node
        $database->appendChild ( $data );

         $database_name = ( isset($this->file_name) ) ? $this->file_name : $database_name;

        // Write XML
        $dom->formatOutput = true;
        $dom->saveXML ();
        
        // Download file
        if( $this->download )
        {
            $filename =  time() . $this->file_ext ;
            $xml = $dom->save ( $filename );

            header('Content-type: text/appdb');
            header('Content-Disposition: attachment; filename="' . $database_name);
            readfile($filename);
            @unlink($filename);
            exit;
        }
        else
        {   
            $xml = $dom->save ( $this->download_path );
        }
    }

    /**
     * Import Databse
     *
     * @return null
     */
    public function import()
    {
        // Set Database connection
        $this->setconnection();
      
        if( $this->import_path == "" || !file_exists($this->import_path))
        {
            die("Database file not exists");
        }    
        
        $dom = new DOMDocument();
        $dom->load($this->import_path);

        // Read Schema
        $schema = $dom->getElementsByTagName('schema');
        $tables = $schema->item(0)->getElementsByTagName( "table" );
        

        foreach( $tables as $table)
        {
            // Get Table Name
            $name = $table->getAttribute('name');
            $fields = $table->getElementsByTagName( "field" );

            // Get table data
            $dable_data = $dom->getElementsByTagName($name);
            $rows = $dable_data->item(0)->getElementsByTagName( "row" );

            $sqlbody = "";
            foreach( $rows as $row )
            {
                $tmp_body = "";
                $tmp_head = "";
                foreach( $fields as $field )
                {
                    $field_name = $field->getAttribute('name');
                    $field_type = $field->getAttribute('type');
                    $entry = $row->getElementsByTagName($field_name);
                    $field_value = $this->decode($entry->item(0)->nodeValue);
                    $field_value = $this->quote_smart($field_value);

                    $tmp_body .= ($tmp_body == "" ) ? $field_value : ",{$field_value}";

                    if( $tmp_body != "" ) $tmp_head .= ($tmp_head == "" ) ? "`{$field_name}`" : ",`{$field_name}`";
                }
               
                 $sqlbody .=  ($sqlbody == "") ?  "($tmp_body)\n" :  ",($tmp_body)\n";
            }
            $this->execute("TRUNCATE TABLE `{$name}` ");
            $query = "INSERT INTO `{$name}` ({$tmp_head}) VALUES {$sqlbody}";
            $this->execute($query);
        }
    }

    public function quote_smart($value)
	{
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Quote if not integer
		if (!is_numeric($value)) {
			$value = "'" . mysql_real_escape_string($value) . "'";
		}
		return $value;
	}
    
    function encode($str = "")
    {
        return utf8_encode($str);
    }

    function decode($str = "")
    {
        return utf8_decode($str);
    }
}