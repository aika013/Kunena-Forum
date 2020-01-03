<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Framework
 * @subpackage    Tables
 *
 * @copyright     Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;
use Joomla\Database\Exception\ExecutionFailureException;

require_once __DIR__ . '/kunena.php';

/**
 * Kunena User Bans
 * Provides access to the #__kunena_users_banned table
 *
 * @since   Kunena 6.0
 */
class TableKunenaUserBans extends Joomla\CMS\Table\Table
{
	/**
	 * @since   Kunena 6.0
 */
	const ANY = 0;

	/**
	 * @since   Kunena 6.0
 */
	const ACTIVE = 1;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $userid = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $ip = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $blocked = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $expiration = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $created_by = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $created_time = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $reason_private = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $reason_public = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $modified_by = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $modified_time = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $comments = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $params = null;

	/**
	 * @param   JDatabaseDriver  $db  Database driver
	 *
	 * @since   Kunena 6.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__kunena_users_banned', 'id', $db);
	}

	/**
	 * @param   integer  $userid  userid
	 * @param   int      $mode    mode
	 *
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function loadByUserid($userid, $mode = self::ACTIVE)
	{
		// Reset the table.
		$k        = $this->_tbl_key;
		$this->$k = 0;
		$this->reset();

		// Check for a valid id to load.
		if ($userid === null || intval($userid) < 1)
		{
			return false;
		}

		$now      = new Joomla\CMS\Date\Date;
		$nullDate = $this->_db->getNullDate() ? $this->_db->quote($this->_db->getNullDate()) : 'NULL';

		// Load the user data.
		if ($mode == self::ACTIVE)
		{
			$where = ' AND (expiration = ' . $nullDate . ' OR expiration > ' . $this->_db->quote($now->toSql()) . ')';
		}
		else
		{
			$where = '';
		}

		$query = $this->_db->getQuery(true);
		$query->select('*')
			->from($this->_db->quoteName($this->_tbl))
			->where($this->_db->quoteName('userid') . ' = ' . $this->_db->quote($userid) . ' ' . $where)
			->order($this->_db->quoteName('id') . ' DESC');
		$query->setLimit(1);
		$this->_db->setQuery($query);

		try
		{
			$data = $this->_db->loadAssoc();
		}
		catch (ExecutionFailureException $e)
		{
			KunenaError::displayDatabaseError($e);

			return false;
		}

		if (empty($data))
		{
			$this->userid = $userid;

			return false;
		}

		// Bind the data to the table.
		$this->bind($data);

		return true;
	}

	/**
	 * @param   mixed  $data    data
	 * @param   array  $ignore  ignore
	 *
	 * @return void
	 * @since   Kunena 6.0
	 */
	public function bind($data, $ignore = array())
	{
		if (isset($data['comments']))
		{
			$data['comments'] = !is_string($data['comments']) ? json_encode($data['comments']) : $data['comments'];
		}

		if (isset($data['params']))
		{
			$data['params'] = !is_string($data['params']) ? json_encode($data['params']) : $data['params'];
		}

		parent::bind($data, $ignore);
	}

	/**
	 * @param   integer  $ip    ip
	 * @param   int      $mode  mode
	 *
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function loadByIP($ip, $mode = self::ACTIVE)
	{
		// Reset the table.
		$k        = $this->_tbl_key;
		$this->$k = 0;
		$this->reset();

		// Check for a valid id to load.
		if ($ip === null || !is_string($ip))
		{
			return false;
		}

		$now      = new Joomla\CMS\Date\Date;
		$nullDate = $this->_db->getNullDate() ? $this->_db->quote($this->_db->getNullDate()) : 'NULL';

		// Load the user data.

		if ($mode == self::ACTIVE)
		{
			$where = 'AND (expiration = ' . $nullDate . ' OR expiration > ' . $this->_db->quote($now->toSql()) . ')';
		}
		else
		{
			$where = '';
		}

		$query = $this->_db->getQuery(true);
		$query->select('*')
			->from($this->_db->quoteName($this->_tbl))
			->where($this->_db->quoteName('ip') . ' = ' . $this->_db->quote($ip) . ' ' . $where)
			->order($this->_db->quoteName('id') . ' DESC');
		$query->setLimit(1);
		$this->_db->setQuery($query);

		try
		{
			$data = $this->_db->loadAssoc();
		}
		catch (ExecutionFailureException $e)
		{
			KunenaError::displayDatabaseError($e);

			return false;
		}

		if (empty($data))
		{
			$this->ip = $ip;

			return false;
		}

		// Bind the data to the table.
		$this->bind($data);

		return true;
	}

	/**
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function check()
	{
		if (!$this->ip)
		{
			$user = KunenaUserHelper::get($this->userid);

			if (!$user->exists())
			{
				throw new RuntimeException(Text::sprintf('COM_KUNENA_LIB_TABLE_USERBANS_ERROR_USER_INVALID', (int) $user->userid));
			}
		}

		return true;
	}
}
