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
 * Kunena Categories
 * Provides access to the #__kunena_categories table
 *
 * @since   Kunena 6.0
 */
class TableKunenaCategories extends KunenaTable
{
	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $parent_id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $name = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $alias = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $icon = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $icon_id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $locked = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $accesstype = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $access = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $pub_access = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $pub_recurse = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $admin_access = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $admin_recurse = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $ordering = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $published = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $channels = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $checked_out = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $checked_out_time = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $review = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $allow_anonymous = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $post_anonymous = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $hits = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $description = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $headerdesc = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $class_sfx = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $allow_polls = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $topic_ordering = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $iconset = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $numTopics = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $numPosts = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $last_topic_id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $last_post_id = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $last_post_time = null;

	/**
	 * @var null
	 * @since   Kunena 6.0
	 */
	public $params = null;

	/**
	 * @var null
	 * @since Kunena 5.1.0
	 */
	public $topictemplate = null;

	/**
	 * @param   JDatabaseDriver  $db  Database driver
	 *
	 * @since   Kunena 6.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__kunena_categories', 'id', $db);
	}

	/**
	 * @param   null  $id     id
	 * @param   bool  $reset  reset
	 *
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function load($id = null, $reset = true)
	{
		$this->_exists = false;
		$k             = $this->_tbl_key;

		// Get the id to load.
		if ($id !== null)
		{
			$this->$k = $id;
		}

		// Reset the table.
		if ($reset)
		{
			$this->reset();
		}

		// Check for a valid id to load.
		if ($this->$k === null || intval($this->$k) < 1)
		{
			$this->$k = 0;

			return false;
		}

		// Load the data.
		$query = $this->_db->getQuery(true)
			->select('*')
			->from($this->_db->quoteName('#__kunena_categories'))
			->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($this->$k));
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

		if (!$data)
		{
			$this->$k = 0;

			return false;
		}

		$this->_exists = true;

		// Bind the data to the table.
		$this->bind($data);

		return $this->_exists;
	}

	/**
	 * @param   mixed   $array   array
	 * @param   string  $ignore  ignore
	 *
	 * @return boolean
	 * @since   Kunena 6.0
	 */
	public function bind($array, $ignore = '')
	{
		if (is_object($array))
		{
			$array = get_object_vars($array);
		}

		if (isset($array['params']) && !is_string($array['params']))
		{
			if ($array['params'] instanceof Joomla\Registry\Registry)
			{
				$registry = $array['params'];
			}
			elseif (is_array($array['params']))
			{
				$registry = new Joomla\Registry\Registry;
				$registry->loadArray($array['params']);
			}
			else
			{
				$registry = new Joomla\Registry\Registry;
			}

			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function check()
	{
		if ($this->id && $this->parent_id)
		{
			if ($this->id == $this->parent_id)
			{
				throw new RuntimeException(Text::_('COM_KUNENA_FORUM_SAME_ERR'));
			}
			elseif ($this->isChild($this->parent_id))
			{
				throw new RuntimeException(Text::_('COM_KUNENA_FORUM_OWNCHILD_ERR'));
			}
		}

		$this->name = trim($this->name);

		if (!$this->name)
		{
			throw new UnexpectedValueException(Text::_('COM_KUNENA_LIB_TABLE_CATEGORIES_ERROR_NO_NAME'));
		}

		if ($this->params instanceof Joomla\Registry\Registry)
		{
			$this->params = $this->params->toString();
		}

		return true;
	}

	// Check if given forum is one of its own childs

	/**
	 * @param   integer  $id  id
	 *
	 * @return mixed
	 * @since Kunena
	 * @throws Exception
	 */
	public function isChild($id)
	{
		// FIXME: when we have category cache, replace this code
		if ($id > 0)
		{
			$query = $this->_db->getQuery(true)
				->select(array('id', 'parent_id'))
				->from($this->_db->quoteName('#__kunena_categories'));
			$this->_db->setQuery($query);

			try
			{
				$list = $this->_db->loadObjectList('id');
			}
			catch (ExecutionFailureException $e)
			{
				KunenaError::displayDatabaseError($e);

				return false;
			}

			$recurse = array();

			while ($id)
			{
				if (in_array($id, $recurse))
				{
					$this->setError(get_class($this) . Text::_('COM_KUNENA_RECURSION'));

					return 0;
				}

				$recurse [] = $id;

				if (!isset($list [$id]))
				{
					$this->setError(get_class($this) . Text::_('COM_KUNENA_LIB_TABLE_CATEGORIES_ERROR_INVALID'));

					return 0;
				}

				$id = $list [$id]->parent_id;

				if ($id != 0 && $id == $this->id)
				{
					return 1;
				}
			}
		}

		return 0;
	}

	/**
	 * @param   string  $where  where
	 *
	 * @return boolean|mixed
	 * @since   Kunena 6.0
	 */
	public function reorder($where = '')
	{
		if (!$where)
		{
			$query = $this->_db->getQuery(true)
				->select($this->_db->quoteName('parent_id'))
				->from($this->_db->quoteName('#__kunena_categories'))
				->group($this->_db->quoteName('parent_id'));
			$this->_db->setQuery($query);

			$parents = $this->_db->loadColumn();
			$success = true;

			foreach ($parents as $parent_id)
			{
				$success &= parent::reorder("parent_id={$this->_db->quote($parent_id)}");
			}

			return $success;
		}
		else
		{
			return parent::reorder($where);
		}
	}

	/**
	 * @param   bool  $updateNulls  update
	 *
	 * @return boolean
	 * @since Kunena
	 * @throws Exception
	 */
	public function store($updateNulls = false)
	{
		$ret = parent::store($updateNulls);

		return $ret;
	}
}
