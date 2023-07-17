<?php
/**
 * PDO Database Class
 * Connect to database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */
class Database {
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

	private $dbh; //database handler
	private $stmt; //statement
	private $error;

	public function __construct()
	{
		// Set DSN
		$dsn = 'sqlsrv:Server=' . $this->host . ';Database=' . $this->dbname;

		// Create PDO instance
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass); //create PDO instance
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			echo $this->error;
		}
	}

	// Prepare statement with query
	public function query($sql)
	{
		$this->stmt = $this->dbh->prepare($sql);
	}

	// Bind values
	public function bind($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) { //check type of value
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;	
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;	
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type); //bind value
	}

	// Execute the prepared statement
	public function execute()
	{
		return $this->stmt->execute();
	}

	// Get result set as array of objects
	public function resultSet()
	{
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ); //fetch all data as object
	}

	// Get single record as object
	public function single()
	{
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ); //fetch single data as object
	}

	// Get row count
	public function rowCount()
	{
		return $this->stmt->rowCount();
	}

	// Get last insert id
	public function lastInsertId()
	{
		return $this->dbh->lastInsertId();
	}

	// Begin transaction
	public function beginTransaction()
	{
		return $this->dbh->beginTransaction();
	}

	// End transaction
	public function endTransaction()
	{
		return $this->dbh->commit();
	}

	// Cancel transaction
	public function cancelTransaction()
	{
		return $this->dbh->rollBack();
	}

	// Debug dump params
	public function debugDumpParams()
	{
		return $this->stmt->debugDumpParams();
	}
}