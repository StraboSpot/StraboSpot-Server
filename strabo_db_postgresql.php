<?php
/**
 * File: strabo_db_postgresql.php
 * Description: StraboDbPostgreSQL class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

	global $ezsql_postgresql_str;

	$ezsql_postgresql_str = array
	(
		1 => 'Require $dbuser and $dbpassword to connect to a database server',
		2 => 'Error establishing PostgreSQL database connection. Correct user/password? Correct hostname? Database server running?',
		3 => 'Require $dbname to select a database',
		4 => 'mySQL database connection is not active',
		5 => 'Unexpected error while trying to select database'
	);

	/**********************************************************************
	*  ezSQL Database specific class - PostgreSQL
	*/

	if ( ! function_exists ('pg_connect') ) die('<b>Fatal Error:</b> StraboDbPostgreSQL requires PostgreSQL Lib to be compiled and or linked in to the PHP engine');
	if ( ! class_exists ('StraboDbCore') ) die('<b>Fatal Error:</b> StraboDbPostgreSQL requires StraboDbCore (strabo_db_core.php) to be included/loaded before it can be used');

	class StraboDbPostgreSQL extends StraboDbCore
	{

		var $dbuser = false;
		var $dbpassword = false;
		var $dbname = false;
		var $dbhost = false;
		var $rows_affected = false;

		/**********************************************************************
		*  Constructor - allow the user to perform a quick connect at the
		*  same time as initialising the StraboDbPostgreSQL class
		*/

		function StraboDbPostgreSQL($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $port='5432')
		{
			$this->dbuser = $dbuser;
			$this->dbpassword = $dbpassword;
			$this->dbname = $dbname;
			$this->dbhost = $dbhost;
			$this->port = $port;
		}

		/**********************************************************************
		*  In the case of PostgreSQL quick_connect is not really needed
		*  because std. connect already does what quick connect does -
		*  but for the sake of consistency it has been included
		*/

		function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $port='5432')
		{
			$return_val = false;
			if ( ! $this->connect($dbuser, $dbpassword, $dbname, $dbhost, $port) ) ;
			else if ( ! $this->select($dbname) ) ;
			else $return_val = true;
			return $return_val;
		}

		/**********************************************************************
		*  Try to connect to mySQL database server
		*/

		function connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $port='5432')
		{
			global $ezsql_postgresql_str; $return_val = false;

			// Must have a user and a password
			if ( ! $dbuser )
			{
				$this->register_error($ezsql_postgresql_str[1].' in '.__FILE__.' on line '.__LINE__);
				$this->show_errors ? trigger_error($ezsql_postgresql_str[1],E_USER_WARNING) : null;
			}
			// Try to establish the server database handle
			else if ( ! $this->dbh = @pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$dbpassword", true) )
			{
				$this->register_error($ezsql_postgresql_str[2].' in '.__FILE__.' on line '.__LINE__);
				$this->show_errors ? trigger_error($ezsql_postgresql_str[2],E_USER_WARNING) : null;
			}
			else
			{
				$this->dbuser = $dbuser;
				$this->dbpassword = $dbpassword;
				$this->dbhost = $dbhost;
				$this->dbname = $dbname;
				$this->port = $port;
				$return_val = true;
			}

			return $return_val;
		}

		/**********************************************************************
		*  No real equivalent of mySQL select in PostgreSQL
		*  once again, function included for the sake of consistency
		*/

		function select($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $port='5432')
		{
			$return_val = false;
			if ( ! $this->connect($dbuser, $dbpassword, $dbname, $dbhost,true) ) ;
			else if ( ! $this->select($dbname) ) ;
			else $return_val = true;
			return $return_val;
		}

		/**********************************************************************
		*  Format a mySQL string correctly for safe mySQL insert
		*  (no mater if magic quotes are on or not)
		*/

		function escape($str)
		{
			return pg_escape_string(stripslashes($str));
		}

		/**********************************************************************
		*  Return PostgreSQL specific system date syntax
		*  i.e. Oracle: SYSDATE Mysql: NOW()
		*/

		function sysdate()
		{
			return 'NOW()';
		}

		/**********************************************************************
		*  Return PostgreSQL specific values
		*/

		function showTables()
		{
			return "table_name FROM information_schema.tables WHERE table_schema = '$this->dbname' and table_type='BASE TABLE'";
		}

		function descTable($tbl_name)
		{
			return "ordinal_position, column_name, data_type, column_default, is_nullable, character_maximum_length, numeric_precision FROM information_schema.columns WHERE table_name = '$tbl_name' AND table_schema='$this->dbname' ORDER BY ordinal_position";
		}

		function showDatabases()
		{
			return "datname from pg_database WHERE datname NOT IN ('template0', 'template1') ORDER BY 1";
		}

		/**********************************************************************
		*  Perform PostgreSQL query and try to detirmin result value
		*/

		function query($query)
		{

			// Initialise return
			$return_val = 0;

			// Flush cached values..
			$this->flush();

			// For reg expressions
			$query = trim($query);

			$this->func_call = "\$db->query(\"$query\")";

			// Keep track of the last query for debug..
			$this->last_query = $query;

			// Count how many queries there have been
			$this->num_queries++;

			// Use core file cache function
			if ( $cache = $this->get_cache($query) )
			{
				return $cache;
			}

			// If there is no existing database connection then try to connect
			if ( ! isset($this->dbh) || ! $this->dbh )
			{
				$this->connect($this->dbuser, $this->dbpassword, $this->dbname, $this->dbhost, $this->port);
			}

			// Perform the query via std postgresql_query function..
			$this->result = @pg_query($this->dbh, $query);

			// If there is an error then take note of it..
			if ( $str = @pg_last_error($this->dbh) )
			{
				$this->register_error($str);
				$this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
				return false;
			}
			// Query was an insert, delete, update, replace
			$is_insert = false;
			if ( preg_match("/^(insert|delete|update|replace)\s+/i",$query) )
			{
				$is_insert = true;
				$this->rows_affected = @pg_affected_rows($this->result);

				// Take note of the insert_id
				if ( preg_match("/^(insert|replace)\s+/i",$query) )
				{

					// Thx. Rafael Bernal
					$insert_query = pg_query("SELECT lastval();");
					$insert_row = pg_fetch_row($insert_query);
					$this->insert_id = $insert_row[0];
				}

				// Return number fo rows affected
				$return_val = $this->rows_affected;
			}
			// Query was a select
			else
			{               $num_rows=0;

							// Take note of column info

							$i=0;
							while ($i < @pg_num_fields($this->result))
							{
									$this->col_info[$i] = new stdClass();
									$this->col_info[$i]->name = pg_field_name($this->result,$i);
									$this->col_info[$i]->type = pg_field_type($this->result,$i);
									$this->col_info[$i]->size = pg_field_size($this->result,$i);
								$i++;
							}

							// Store Query Results

							while ( $row = @pg_fetch_object($this->result) )
							{
								// Store results as an objects within main array
								$this->last_result[$num_rows] = $row ;
								$num_rows++;
							}

								@pg_free_result($this->result);
				// Log number of rows the query returned
				$this->num_rows = $num_rows;

				// Return number of rows selected
				$return_val = $this->num_rows;

			}

			// disk caching of queries
			$this->store_cache($query,$is_insert);

			// If debug ALL queries
			$this->trace || $this->debug_all ? $this->debug() : null ;

			return $return_val;

		}

		/**
		* Close the database connection
		*/

		function disconnect()
		{
			if ( $this->dbh )
			{
				@pg_close($this->dbh);
			}
		}

		/**********************************************************************
		* SECURE PREPARED STATEMENT METHODS
		* Added for SQL injection protection
		* Use these methods instead of query() for user input
		***********************************************************************/

		/**
		* Execute a prepared statement securely
		* @param string $query SQL query with $1, $2, etc. placeholders
		* @param array $params Array of parameters to bind
		* @return mixed Result or false on error
		*/
		function prepare_query($query, $params = array())
		{
			// Initialize return
			$return_val = 0;

			// Flush cached values
			$this->flush();

			// For reg expressions
			$query = trim($query);

			// Keep track of the last query for debug
			$this->last_query = $query;

			// Count how many queries there have been
			$this->num_queries++;

			// If there is no existing database connection then try to connect
			if ( ! isset($this->dbh) || ! $this->dbh )
			{
				$this->connect($this->dbuser, $this->dbpassword, $this->dbname, $this->dbhost, $this->port);
			}

			// Generate unique statement name
			$stmt_name = 'stmt_' . md5($query . microtime(true));

			// Prepare the statement
			$prep_result = @pg_prepare($this->dbh, $stmt_name, $query);

			if (!$prep_result)
			{
				$this->register_error('Error preparing statement: ' . pg_last_error($this->dbh));
				$this->show_errors ? trigger_error('Error preparing statement', E_USER_WARNING) : null;
				return false;
			}

			// Execute the prepared statement
			$this->result = @pg_execute($this->dbh, $stmt_name, $params);

			// If there is an error then take note of it
			if ( !$this->result )
			{
				$this->register_error('Error executing prepared statement: ' . pg_last_error($this->dbh));
				$this->show_errors ? trigger_error('Error executing prepared statement', E_USER_WARNING) : null;
				return false;
			}

			// Query was an insert, delete, update, replace
			$is_insert = preg_match("/^(insert|delete|update|replace)\s+/i",$query);

			if ( $is_insert )
			{
				$this->rows_affected = @pg_affected_rows($this->result);
				$return_val = $this->rows_affected;
			}
			else
			{
				$num_rows = 0;

				// Take note of column info
				$i = 0;
				while ($i < @pg_num_fields($this->result))
				{
					$this->col_info[$i] = new stdClass();
					$this->col_info[$i]->name = pg_field_name($this->result,$i);
					$this->col_info[$i]->type = pg_field_type($this->result,$i);
					$this->col_info[$i]->size = pg_field_size($this->result,$i);
					$i++;
				}

				// Store Query Results
				while ( $row = @pg_fetch_object($this->result) )
				{
					$this->last_result[$num_rows] = $row;
					$num_rows++;
				}

				@pg_free_result($this->result);

				// Log number of rows the query returned
				$this->num_rows = $num_rows;

				// Return number of rows selected
				$return_val = $this->num_rows;
			}

			return $return_val;
		}

		/**
		* Get a single row using prepared statement
		* @param string $query SQL query with $1, $2, etc. placeholders
		* @param array $params Array of parameters to bind
		* @return object|null Single row object or null
		*/
		function get_row_prepared($query, $params = array())
		{
			$this->prepare_query($query, $params);

			if ( $this->last_result && isset($this->last_result[0]) )
			{
				return $this->last_result[0];
			}

			return null;
		}

		/**
		* Get a single variable using prepared statement
		* @param string $query SQL query with $1, $2, etc. placeholders
		* @param array $params Array of parameters to bind
		* @return mixed Single value or null
		*/
		function get_var_prepared($query, $params = array())
		{
			$this->prepare_query($query, $params);

			if ( $this->last_result && isset($this->last_result[0]) )
			{
				$values = array_values(get_object_vars($this->last_result[0]));
				return $values[0];
			}

			return null;
		}

		/**
		* Get results using prepared statement
		* @param string $query SQL query with $1, $2, etc. placeholders
		* @param array $params Array of parameters to bind
		* @return array Array of result objects
		*/
		function get_results_prepared($query, $params = array())
		{
			$this->prepare_query($query, $params);
			return $this->last_result;
		}

	}
