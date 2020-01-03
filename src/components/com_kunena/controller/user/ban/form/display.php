<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Site
 * @subpackage      Controller.User
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Class ComponentKunenaControllerUserBanFormDisplay
 *
 * @since  K4.0
 */
class ComponentKunenaControllerUserBanFormDisplay extends KunenaControllerDisplay
{
	/**
	 * @var string
	 * @since   Kunena 6.0
	 */
	protected $name = 'User/Ban/Form';

	/**
	 * @var KunenaUser
	 * @since   Kunena 6.0
	 */
	public $profile;

	/**
	 * @var KunenaUserBan
	 * @since   Kunena 6.0
	 */
	public $banInfo;

	/**
	 * @var
	 * @since   Kunena 6.0
	 */
	public $headerText;

	/**
	 * Prepare ban form.
	 *
	 * @return void
	 *
	 * @since Kunena
	 * @throws null
	 */
	protected function before()
	{
		parent::before();

		$userid = $this->input->getInt('userid');

		$this->profile = KunenaUserHelper::get($userid);
		$this->profile->tryAuthorise('ban');

		$this->banInfo = KunenaUserBan::getInstanceByUserid($userid, true);

		$this->headerText = $this->banInfo->exists() ? Text::_('COM_KUNENA_BAN_EDIT') : Text::_('COM_KUNENA_BAN_NEW');
	}

	/**
	 * Prepare document.
	 *
	 * @return void
	 * @since Kunena
	 * @throws Exception
	 */
	protected function prepareDocument()
	{
		$menu_item = $this->app->getMenu()->getActive();

		if ($menu_item)
		{
			$params             = $menu_item->getParams();
			$params_title       = $params->get('page_title');
			$params_keywords    = $params->get('menu-meta_keywords');
			$params_description = $params->get('menu-meta_description');

			if (!empty($params_title))
			{
				$title = $params->get('page_title');
				$this->setTitle($title);
			}
			else
			{
				$this->setTitle($this->headerText);
			}

			if (!empty($params_keywords))
			{
				$keywords = $params->get('menu-meta_keywords');
				$this->setKeywords($keywords);
			}
			else
			{
				$this->setKeywords($this->headerText);
			}

			if (!empty($params_description))
			{
				$description = $params->get('menu-meta_description');
				$this->setDescription($description);
			}
			else
			{
				$this->setDescription($this->headerText);
			}
		}
	}
}
