<?php


/**
 * Base class that represents a query for the 'web_mail' table.
 *
 * 
 *
 * @method     WebMailQuery orderByMailId($order = Criteria::ASC) Order by the mail_id column
 * @method     WebMailQuery orderByMailDate($order = Criteria::ASC) Order by the mail_date column
 * @method     WebMailQuery orderByMailSender($order = Criteria::ASC) Order by the mail_sender column
 * @method     WebMailQuery orderByMailTo($order = Criteria::ASC) Order by the mail_to column
 * @method     WebMailQuery orderByMailCc($order = Criteria::ASC) Order by the mail_cc column
 * @method     WebMailQuery orderByMailBc($order = Criteria::ASC) Order by the mail_bc column
 * @method     WebMailQuery orderByMailSubject($order = Criteria::ASC) Order by the mail_subject column
 * @method     WebMailQuery orderByMailText($order = Criteria::ASC) Order by the mail_text column
 * @method     WebMailQuery orderByMailNumReciepients($order = Criteria::ASC) Order by the mail_num_reciepients column
 * @method     WebMailQuery orderByMailCriteria($order = Criteria::ASC) Order by the mail_criteria column
 *
 * @method     WebMailQuery groupByMailId() Group by the mail_id column
 * @method     WebMailQuery groupByMailDate() Group by the mail_date column
 * @method     WebMailQuery groupByMailSender() Group by the mail_sender column
 * @method     WebMailQuery groupByMailTo() Group by the mail_to column
 * @method     WebMailQuery groupByMailCc() Group by the mail_cc column
 * @method     WebMailQuery groupByMailBc() Group by the mail_bc column
 * @method     WebMailQuery groupByMailSubject() Group by the mail_subject column
 * @method     WebMailQuery groupByMailText() Group by the mail_text column
 * @method     WebMailQuery groupByMailNumReciepients() Group by the mail_num_reciepients column
 * @method     WebMailQuery groupByMailCriteria() Group by the mail_criteria column
 *
 * @method     WebMailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebMailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebMailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebMail findOne(PropelPDO $con = null) Return the first WebMail matching the query
 * @method     WebMail findOneOrCreate(PropelPDO $con = null) Return the first WebMail matching the query, or a new WebMail object populated from the query conditions when no match is found
 *
 * @method     WebMail findOneByMailId(int $mail_id) Return the first WebMail filtered by the mail_id column
 * @method     WebMail findOneByMailDate(string $mail_date) Return the first WebMail filtered by the mail_date column
 * @method     WebMail findOneByMailSender(string $mail_sender) Return the first WebMail filtered by the mail_sender column
 * @method     WebMail findOneByMailTo(string $mail_to) Return the first WebMail filtered by the mail_to column
 * @method     WebMail findOneByMailCc(string $mail_cc) Return the first WebMail filtered by the mail_cc column
 * @method     WebMail findOneByMailBc(string $mail_bc) Return the first WebMail filtered by the mail_bc column
 * @method     WebMail findOneByMailSubject(string $mail_subject) Return the first WebMail filtered by the mail_subject column
 * @method     WebMail findOneByMailText(string $mail_text) Return the first WebMail filtered by the mail_text column
 * @method     WebMail findOneByMailNumReciepients(int $mail_num_reciepients) Return the first WebMail filtered by the mail_num_reciepients column
 * @method     WebMail findOneByMailCriteria(string $mail_criteria) Return the first WebMail filtered by the mail_criteria column
 *
 * @method     array findByMailId(int $mail_id) Return WebMail objects filtered by the mail_id column
 * @method     array findByMailDate(string $mail_date) Return WebMail objects filtered by the mail_date column
 * @method     array findByMailSender(string $mail_sender) Return WebMail objects filtered by the mail_sender column
 * @method     array findByMailTo(string $mail_to) Return WebMail objects filtered by the mail_to column
 * @method     array findByMailCc(string $mail_cc) Return WebMail objects filtered by the mail_cc column
 * @method     array findByMailBc(string $mail_bc) Return WebMail objects filtered by the mail_bc column
 * @method     array findByMailSubject(string $mail_subject) Return WebMail objects filtered by the mail_subject column
 * @method     array findByMailText(string $mail_text) Return WebMail objects filtered by the mail_text column
 * @method     array findByMailNumReciepients(int $mail_num_reciepients) Return WebMail objects filtered by the mail_num_reciepients column
 * @method     array findByMailCriteria(string $mail_criteria) Return WebMail objects filtered by the mail_criteria column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebMailQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebMailQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebMail', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebMailQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebMailQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebMailQuery) {
			return $criteria;
		}
		$query = new WebMailQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    WebMail|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebMailPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebMailPeer::MAIL_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebMailPeer::MAIL_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the mail_id column
	 * 
	 * @param     int|array $mailId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailId($mailId = null, $comparison = null)
	{
		if (is_array($mailId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_ID, $mailId, $comparison);
	}

	/**
	 * Filter the query on the mail_date column
	 * 
	 * @param     string|array $mailDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailDate($mailDate = null, $comparison = null)
	{
		if (is_array($mailDate)) {
			$useMinMax = false;
			if (isset($mailDate['min'])) {
				$this->addUsingAlias(WebMailPeer::MAIL_DATE, $mailDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($mailDate['max'])) {
				$this->addUsingAlias(WebMailPeer::MAIL_DATE, $mailDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_DATE, $mailDate, $comparison);
	}

	/**
	 * Filter the query on the mail_sender column
	 * 
	 * @param     string $mailSender The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailSender($mailSender = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailSender)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailSender)) {
				$mailSender = str_replace('*', '%', $mailSender);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_SENDER, $mailSender, $comparison);
	}

	/**
	 * Filter the query on the mail_to column
	 * 
	 * @param     string $mailTo The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailTo($mailTo = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailTo)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailTo)) {
				$mailTo = str_replace('*', '%', $mailTo);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_TO, $mailTo, $comparison);
	}

	/**
	 * Filter the query on the mail_cc column
	 * 
	 * @param     string $mailCc The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailCc($mailCc = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailCc)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailCc)) {
				$mailCc = str_replace('*', '%', $mailCc);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_CC, $mailCc, $comparison);
	}

	/**
	 * Filter the query on the mail_bc column
	 * 
	 * @param     string $mailBc The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailBc($mailBc = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailBc)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailBc)) {
				$mailBc = str_replace('*', '%', $mailBc);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_BC, $mailBc, $comparison);
	}

	/**
	 * Filter the query on the mail_subject column
	 * 
	 * @param     string $mailSubject The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailSubject($mailSubject = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailSubject)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailSubject)) {
				$mailSubject = str_replace('*', '%', $mailSubject);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_SUBJECT, $mailSubject, $comparison);
	}

	/**
	 * Filter the query on the mail_text column
	 * 
	 * @param     string $mailText The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailText($mailText = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailText)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailText)) {
				$mailText = str_replace('*', '%', $mailText);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_TEXT, $mailText, $comparison);
	}

	/**
	 * Filter the query on the mail_num_reciepients column
	 * 
	 * @param     int|array $mailNumReciepients The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailNumReciepients($mailNumReciepients = null, $comparison = null)
	{
		if (is_array($mailNumReciepients)) {
			$useMinMax = false;
			if (isset($mailNumReciepients['min'])) {
				$this->addUsingAlias(WebMailPeer::MAIL_NUM_RECIEPIENTS, $mailNumReciepients['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($mailNumReciepients['max'])) {
				$this->addUsingAlias(WebMailPeer::MAIL_NUM_RECIEPIENTS, $mailNumReciepients['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_NUM_RECIEPIENTS, $mailNumReciepients, $comparison);
	}

	/**
	 * Filter the query on the mail_criteria column
	 * 
	 * @param     string $mailCriteria The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function filterByMailCriteria($mailCriteria = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($mailCriteria)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $mailCriteria)) {
				$mailCriteria = str_replace('*', '%', $mailCriteria);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebMailPeer::MAIL_CRITERIA, $mailCriteria, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     WebMail $webMail Object to remove from the list of results
	 *
	 * @return    WebMailQuery The current query, for fluid interface
	 */
	public function prune($webMail = null)
	{
		if ($webMail) {
			$this->addUsingAlias(WebMailPeer::MAIL_ID, $webMail->getMailId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebMailQuery
