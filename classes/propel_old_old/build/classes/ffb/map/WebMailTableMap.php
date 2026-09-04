<?php


/**
 * This class defines the structure of the 'web_mail' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    ffb.map
 */
class WebMailTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebMailTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('web_mail');
		$this->setPhpName('WebMail');
		$this->setClassname('WebMail');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('MAIL_ID', 'MailId', 'INTEGER', true, null, null);
		$this->addColumn('MAIL_DATE', 'MailDate', 'TIMESTAMP', true, null, null);
		$this->addColumn('MAIL_SENDER', 'MailSender', 'VARCHAR', true, 255, null);
		$this->addColumn('MAIL_TO', 'MailTo', 'LONGVARCHAR', true, null, null);
		$this->addColumn('MAIL_CC', 'MailCc', 'LONGVARCHAR', true, null, null);
		$this->addColumn('MAIL_BC', 'MailBc', 'LONGVARCHAR', true, null, null);
		$this->addColumn('MAIL_SUBJECT', 'MailSubject', 'VARCHAR', true, 255, null);
		$this->addColumn('MAIL_TEXT', 'MailText', 'LONGVARCHAR', true, null, null);
		$this->addColumn('MAIL_NUM_RECIEPIENTS', 'MailNumReciepients', 'INTEGER', true, null, null);
		$this->addColumn('MAIL_CRITERIA', 'MailCriteria', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

} // WebMailTableMap
