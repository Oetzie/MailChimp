<?php

	/**
	 * MailChimp
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of MailChimp, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * MailChimp is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * MailChimp is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * MailChimp; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

    $mailChimp = $modx->getService('mailchimp', 'MailChimp', $modx->getOption('mailchimp.core_path', null, $modx->getOption('core_path').'components/mailchimp/').'model/mailchimp/');

	$subscribe = false; 

	switch($prefix) {
	    case 'After':
			if ($form->isValid()) {
				$properties = array(
					'values'		=> $form->getValues(),
					'list'			=> $modx->getOption('mailChimpList', $form->extensionScriptProperties),
					'welcome'       => $modx->getOption('mailChimpWelcome', $form->extensionScriptProperties)
				);

				if (false === ($subscribe = $mailChimp->subscribe($properties))) {
					$form->getValidator()->setBulkError('extension_mailchimp_subscribe');
				}
			}

			break;
	}

	return $subscribe;
	
?>