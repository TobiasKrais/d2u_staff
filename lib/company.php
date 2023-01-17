<?php

namespace D2U_Staff;

/**
 * Staff company
 */
class Company {
	/**
	 * @var int Database ID
	 */
	var int $company_id = 0;
	
	/**
	 * @var string ame
	 */
	var string $name = "";
	
	/**
	 * @var string Website URL
	 */
	var string $url = "";
	
	/**
	 * @var string Logo
	 */
	var string $logo = "";

	/**
	 * Constructor. Reads the object stored in database.
	 * @param int $company_id Company ID.
	 */
	 public function __construct($company_id) {
		$query = "SELECT * FROM ". \rex::getTablePrefix() ."d2u_staff_company "
				."WHERE company_id = ". $company_id;
		$result = \rex_sql::factory();
		$result->setQuery($query);
		$num_rows = $result->getRows();

		if ($num_rows > 0) {
			$this->company_id = $result->getValue("company_id");
			$this->name = $result->getValue("name");
			$this->url = $result->getValue("url");
			$this->logo = $result->getValue("logo");
		}
	}
	
	/**
	 * Deletes the object.
	 */
	public function delete():void {
		$query = "DELETE FROM ". \rex::getTablePrefix() ."d2u_staff_company "
			."WHERE company_id = ". $this->company_id;
		$result = \rex_sql::factory();
		$result->setQuery($query);
	}

	/**
	 * Get all objects.
	 * @return Company[] Array with Company objects.
	 */
	public static function getAll() {
		$query = "SELECT company_id FROM ". \rex::getTablePrefix() ."d2u_staff_company ORDER BY name";
		$result = \rex_sql::factory();
		$result->setQuery($query);
		
		$companies = [];
		for($i = 0; $i < $result->getRows(); $i++) {
			$companies[] = new Company($result->getValue("company_id"));
			$result->next();
		}
		return $companies;
	}
	
	/**
	 * Gets the companies staff.
	 * @return Staff[] Staff members
	 */
	public function getStaff() {
		$query = "SELECT staff_id FROM ". \rex::getTablePrefix() ."d2u_staff "
			."WHERE company_id = ". $this->company_id ." "
			.'ORDER BY name ASC';
		$result = \rex_sql::factory();
		$result->setQuery($query);
		
		$staff = [];
		for($i = 0; $i < $result->getRows(); $i++) {
			$staff[] = new Staff($result->getValue("staff_id"), \rex_clang::getCurrentId());
			$result->next();
		}
		return $staff;
	}
	
	/**
	 * Updates or inserts the object into database.
	 * @return in error code if error occurs
	 */
	public function save() {
		$error = 0;

		$query = \rex::getTablePrefix() ."d2u_staff_company SET "
				."name = '". $this->name ."', "
				."url = '". $this->url ."', "
				."logo = '". $this->logo ."' ";

		if($this->company_id === 0) {
			$query = "INSERT INTO ". $query;
		}
		else {
			$query = "UPDATE ". $query ." WHERE company_id = ". $this->company_id;
		}

		$result = \rex_sql::factory();
		$result->setQuery($query);
		if($this->company_id === 0) {
			$this->company_id = intval($result->getLastId());
			$error = $result->hasError();
		}
		
		return $error;
	}
}