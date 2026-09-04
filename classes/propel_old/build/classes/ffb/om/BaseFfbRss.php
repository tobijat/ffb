<?php


/**
 * Base class that represents a row from the 'ffb_rss' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbRss extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbRssPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbRssPeer
	 */
	protected static $peer;

	/**
	 * The value for the ffb_rss_id field.
	 * @var        int
	 */
	protected $ffb_rss_id;

	/**
	 * The value for the ffb_rss_title field.
	 * @var        string
	 */
	protected $ffb_rss_title;

	/**
	 * The value for the ffb_rss_description field.
	 * @var        string
	 */
	protected $ffb_rss_description;

	/**
	 * The value for the ffb_rss_category field.
	 * @var        string
	 */
	protected $ffb_rss_category;

	/**
	 * The value for the ffb_rss_guid field.
	 * @var        string
	 */
	protected $ffb_rss_guid;

	/**
	 * The value for the ffb_rss_author field.
	 * @var        string
	 */
	protected $ffb_rss_author;

	/**
	 * The value for the ffb_rss_origin_id field.
	 * @var        int
	 */
	protected $ffb_rss_origin_id;

	/**
	 * The value for the ffb_rss_pubdate field.
	 * @var        string
	 */
	protected $ffb_rss_pubdate;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [ffb_rss_id] column value.
	 * 
	 * @return     int
	 */
	public function getFfbRssId()
	{
		return $this->ffb_rss_id;
	}

	/**
	 * Get the [ffb_rss_title] column value.
	 * 
	 * @return     string
	 */
	public function getFfbRssTitle()
	{
		return $this->ffb_rss_title;
	}

	/**
	 * Get the [ffb_rss_description] column value.
	 * 
	 * @return     string
	 */
	public function getFfbRssDescription()
	{
		return $this->ffb_rss_description;
	}

	/**
	 * Get the [ffb_rss_category] column value.
	 * 
	 * @return     string
	 */
	public function getFfbRssCategory()
	{
		return $this->ffb_rss_category;
	}

	/**
	 * Get the [ffb_rss_guid] column value.
	 * 
	 * @return     string
	 */
	public function getFfbRssGuid()
	{
		return $this->ffb_rss_guid;
	}

	/**
	 * Get the [ffb_rss_author] column value.
	 * 
	 * @return     string
	 */
	public function getFfbRssAuthor()
	{
		return $this->ffb_rss_author;
	}

	/**
	 * Get the [ffb_rss_origin_id] column value.
	 * 
	 * @return     int
	 */
	public function getFfbRssOriginId()
	{
		return $this->ffb_rss_origin_id;
	}

	/**
	 * Get the [optionally formatted] temporal [ffb_rss_pubdate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getFfbRssPubdate($format = 'Y-m-d H:i:s')
	{
		if ($this->ffb_rss_pubdate === null) {
			return null;
		}


		if ($this->ffb_rss_pubdate === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->ffb_rss_pubdate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->ffb_rss_pubdate, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [ffb_rss_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ffb_rss_id !== $v) {
			$this->ffb_rss_id = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_ID;
		}

		return $this;
	} // setFfbRssId()

	/**
	 * Set the value of [ffb_rss_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ffb_rss_title !== $v) {
			$this->ffb_rss_title = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_TITLE;
		}

		return $this;
	} // setFfbRssTitle()

	/**
	 * Set the value of [ffb_rss_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ffb_rss_description !== $v) {
			$this->ffb_rss_description = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_DESCRIPTION;
		}

		return $this;
	} // setFfbRssDescription()

	/**
	 * Set the value of [ffb_rss_category] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssCategory($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ffb_rss_category !== $v) {
			$this->ffb_rss_category = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_CATEGORY;
		}

		return $this;
	} // setFfbRssCategory()

	/**
	 * Set the value of [ffb_rss_guid] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssGuid($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ffb_rss_guid !== $v) {
			$this->ffb_rss_guid = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_GUID;
		}

		return $this;
	} // setFfbRssGuid()

	/**
	 * Set the value of [ffb_rss_author] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssAuthor($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ffb_rss_author !== $v) {
			$this->ffb_rss_author = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_AUTHOR;
		}

		return $this;
	} // setFfbRssAuthor()

	/**
	 * Set the value of [ffb_rss_origin_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssOriginId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ffb_rss_origin_id !== $v) {
			$this->ffb_rss_origin_id = $v;
			$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_ORIGIN_ID;
		}

		return $this;
	} // setFfbRssOriginId()

	/**
	 * Sets the value of [ffb_rss_pubdate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbRss The current object (for fluent API support)
	 */
	public function setFfbRssPubdate($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->ffb_rss_pubdate !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->ffb_rss_pubdate !== null && $tmpDt = new DateTime($this->ffb_rss_pubdate)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->ffb_rss_pubdate = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_PUBDATE;
			}
		} // if either are not null

		return $this;
	} // setFfbRssPubdate()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->ffb_rss_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->ffb_rss_title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->ffb_rss_description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->ffb_rss_category = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->ffb_rss_guid = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->ffb_rss_author = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->ffb_rss_origin_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->ffb_rss_pubdate = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 8; // 8 = FfbRssPeer::NUM_COLUMNS - FfbRssPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbRss object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbRssPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbRssPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbRssPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbRssQuery::create()
					->filterByPrimaryKey($this->getPrimaryKey())
					->delete($con);
				$this->postDelete($con);
				$con->commit();
				$this->setDeleted(true);
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbRssPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				FfbRssPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbRssPeer::FFB_RSS_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbRssPeer::FFB_RSS_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbRssPeer::FFB_RSS_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setFfbRssId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = FfbRssPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = FfbRssPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FfbRssPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getFfbRssId();
				break;
			case 1:
				return $this->getFfbRssTitle();
				break;
			case 2:
				return $this->getFfbRssDescription();
				break;
			case 3:
				return $this->getFfbRssCategory();
				break;
			case 4:
				return $this->getFfbRssGuid();
				break;
			case 5:
				return $this->getFfbRssAuthor();
				break;
			case 6:
				return $this->getFfbRssOriginId();
				break;
			case 7:
				return $this->getFfbRssPubdate();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                    Defaults to BasePeer::TYPE_PHPNAME.
	 * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = FfbRssPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getFfbRssId(),
			$keys[1] => $this->getFfbRssTitle(),
			$keys[2] => $this->getFfbRssDescription(),
			$keys[3] => $this->getFfbRssCategory(),
			$keys[4] => $this->getFfbRssGuid(),
			$keys[5] => $this->getFfbRssAuthor(),
			$keys[6] => $this->getFfbRssOriginId(),
			$keys[7] => $this->getFfbRssPubdate(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FfbRssPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setFfbRssId($value);
				break;
			case 1:
				$this->setFfbRssTitle($value);
				break;
			case 2:
				$this->setFfbRssDescription($value);
				break;
			case 3:
				$this->setFfbRssCategory($value);
				break;
			case 4:
				$this->setFfbRssGuid($value);
				break;
			case 5:
				$this->setFfbRssAuthor($value);
				break;
			case 6:
				$this->setFfbRssOriginId($value);
				break;
			case 7:
				$this->setFfbRssPubdate($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FfbRssPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setFfbRssId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFfbRssTitle($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setFfbRssDescription($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFfbRssCategory($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setFfbRssGuid($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setFfbRssAuthor($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setFfbRssOriginId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setFfbRssPubdate($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbRssPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_ID)) $criteria->add(FfbRssPeer::FFB_RSS_ID, $this->ffb_rss_id);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_TITLE)) $criteria->add(FfbRssPeer::FFB_RSS_TITLE, $this->ffb_rss_title);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_DESCRIPTION)) $criteria->add(FfbRssPeer::FFB_RSS_DESCRIPTION, $this->ffb_rss_description);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_CATEGORY)) $criteria->add(FfbRssPeer::FFB_RSS_CATEGORY, $this->ffb_rss_category);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_GUID)) $criteria->add(FfbRssPeer::FFB_RSS_GUID, $this->ffb_rss_guid);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_AUTHOR)) $criteria->add(FfbRssPeer::FFB_RSS_AUTHOR, $this->ffb_rss_author);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_ORIGIN_ID)) $criteria->add(FfbRssPeer::FFB_RSS_ORIGIN_ID, $this->ffb_rss_origin_id);
		if ($this->isColumnModified(FfbRssPeer::FFB_RSS_PUBDATE)) $criteria->add(FfbRssPeer::FFB_RSS_PUBDATE, $this->ffb_rss_pubdate);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(FfbRssPeer::DATABASE_NAME);
		$criteria->add(FfbRssPeer::FFB_RSS_ID, $this->ffb_rss_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getFfbRssId();
	}

	/**
	 * Generic method to set the primary key (ffb_rss_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setFfbRssId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getFfbRssId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbRss (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setFfbRssTitle($this->ffb_rss_title);
		$copyObj->setFfbRssDescription($this->ffb_rss_description);
		$copyObj->setFfbRssCategory($this->ffb_rss_category);
		$copyObj->setFfbRssGuid($this->ffb_rss_guid);
		$copyObj->setFfbRssAuthor($this->ffb_rss_author);
		$copyObj->setFfbRssOriginId($this->ffb_rss_origin_id);
		$copyObj->setFfbRssPubdate($this->ffb_rss_pubdate);

		$copyObj->setNew(true);
		$copyObj->setFfbRssId(NULL); // this is a auto-increment column, so set to default value
	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     FfbRss Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     FfbRssPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbRssPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->ffb_rss_id = null;
		$this->ffb_rss_title = null;
		$this->ffb_rss_description = null;
		$this->ffb_rss_category = null;
		$this->ffb_rss_guid = null;
		$this->ffb_rss_author = null;
		$this->ffb_rss_origin_id = null;
		$this->ffb_rss_pubdate = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
		$this->resetModified();
		$this->setNew(true);
		$this->setDeleted(false);
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

	}

	/**
	 * Catches calls to virtual methods
	 */
	public function __call($name, $params)
	{
		if (preg_match('/get(\w+)/', $name, $matches)) {
			$virtualColumn = $matches[1];
			if ($this->hasVirtualColumn($virtualColumn)) {
				return $this->getVirtualColumn($virtualColumn);
			}
			// no lcfirst in php<5.3...
			$virtualColumn[0] = strtolower($virtualColumn[0]);
			if ($this->hasVirtualColumn($virtualColumn)) {
				return $this->getVirtualColumn($virtualColumn);
			}
		}
		return parent::__call($name, $params);
	}

} // BaseFfbRss
