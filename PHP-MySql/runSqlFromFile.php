<?php

class DatabaseHelpers {


    /**
    * Constructor: given a host and db name it will create a DbPrime object
    * connected to the dbName
    *
    * @param string $dbConnectName, One of the DB_* constants.
    * @return DbPrime
    */
    public function __construct($hostName, $user, $password, $dbName) {
    
        $this->dbName = $dbName;
        $this->hostname = $hostName;
        $this->dbConnection = NULL;
       
        $this->dbConnection = new mysqli($hostName, $user, $password, $dbName);

        $this->dbConnection = DbUtil::GetDBConnection($dbConnectName);


    }

    /**
    * Check to see if $fileName is a SQL file or not
    *
    * @param string $fileName, file name to be checked
    * @return bool, TRUE if the file name has .sql at then end, FALSE otherwise
    */
    private function is_sql($fileName) {

        $matchRes = preg_match('/\.sql$/i', $fileName);
        return ($matchRes <= 0 || $matchRes === FALSE) ? FALSE : TRUE;
    }
    
	/**
    * Given a database name, run the use SQL command on it
    * 
    * @param string $dbName
    * @return array|bool|mixed|null
    */
    public function UseDatabase($dbName = null) {
               
        //Create the use SQL statement
        if(empty($dbName)) {
			$useDbStmt = 'use ' . $this->dbName;
		} else {
			$useDbStmt = 'use ' . $dbName;
        }    
        //execute 
        return $this->RunSqlStmt($useDbStmt);
    }

	/**
     * Runs the given SQL statement using the exisitng DB connection
     *
     * @param string     $sqlStmt
     *
     * @return array|bool|mixed|null $recordset, or FALSE if error occurs
     */
    public function RunSqlStmt($sqlStmt,$multi=false,$transpose=false) {

        //initialize
        $recordset = NULL;

        //if the sqlStmt not set, set it so it can be executed
        if (!empty($sqlStmt)) {

            //check for db connection existence
            if (!$this->dbConnection) {

            }

            //execute the statement
            $recordset = $this->dbConnection->query($sqlStmt);

            //error occured?
            if ($this->dbConnection->m_clDbConn->errno) {
                
                // Return the error number returned
            }
        } else {
            
            // Return empty statement error

        }

        return $recordset;
    }
  
  
    /**
    * Execute $fileName if its a SQL file
    *
	* @param string $fileName, name of the file to execute
	*
    * @return mixed $result, or FALSE if an error occurs
    */
    public function RunScriptFromFile($fileName) {
        $result = NULL;

        //if the file is a sql file, then execute it
        if ($this->is_sql($fileName)) {
            
            //create the file path
            $filePath = $fileName;
            $fp = fopen($filePath,'r');
            if(!$fp) {
                echo "Can't open the file!";
                return FALSE;        
            }
            
            //use the current dbName as exeuting database for sql scripts
            $this->UseDatabase($this->dbName);
            $sql = '';
            $waitForDelim = false;
            while(true) {
                $line = fgets($fp); //Get one line from the file at a time to avoid max_packet_size issue
                if($line === false) {
                    echo "The line is empty!";
                    break;    
                }
                
                if(preg_match('/^\s*$/',$line) || preg_match('/^\s*#.*/',$line) || preg_match('/^\s*--.*/',$line)) {
                    //Skipping comments and empty lines.
                    continue;        
                }
                
                                                   
                //Here we handle scripts that cahnge the delimiter (like stored procs)
                if(preg_match('/^DELIMITER/i',$line)) {   //If this line contains a DELIMITER command.
                    if($waitForDelim) { //We have seen the first delimiter, this line is changing it back to ';'
                        if(!empty($sql)) {
                            $result = $this->RunSqlStmt($sql);
                        }
                        if($result === FALSE) return FALSE;
                        
                        $sql = '';
                        $waitForDelim = false;
                    } else {
                        $waitForDelim = true;        
                    }
                } else {
                    if(preg_match('/^\s*DROP.+\$\$\s*$/',$line)) { //Run drop statement by itself.
                        $sql = str_replace('$$',';',$line);
                        if(!empty($sql)) {
                            $result = $this->RunSqlStmt($sql);
                        }
                        if($result === FALSE) return FALSE;
                        
                        $sql = ''; 
                        continue;
                    }
                    if(preg_match('/^\s*END\s*\$\$\s*$/',$line)) {
                        $line = str_replace('$$',';',$line);
                    }
                    
                    $sql .= $line;
                
                    if(!$waitForDelim && preg_match('/;\s*$/',$line)) { 
					//If we are not in the midst of a stored proc definition and the line ends with a semicolon, run the SQL
                        if(!empty($sql)) {
                            $result = $this->RunSqlStmt($sql);
                        }
                        if($result === FALSE) return FALSE;
                        
                        $sql = '';
                    }
                }
            }
            
        } else {
            echo 'RunScriptFromFile: input file is not a SQL file (fileName)';
            return FALSE;
        }

        return $result;
    }

}

	?>