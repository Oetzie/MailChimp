<?php

	$output = '';
	
	$translations = array(
		'nl'		=> array(
			'api_endpoint'		=> 'MailChimp API URL',
			'api_endpoint_desc'	=> 'De URL van de MailChimp API.',
			'api_key'			=> 'MailChimp API sleutel',
			'api_key_desc'		=> 'De sleutel van de MailChimp API.'
		),
		'en'		=> array(
			'api_endpoint'		=> 'MailChimp API URL',
			'api_endpoint_desc'	=> 'The URL of the MailChimp API.',
			'api_key'			=> 'MailChimp API key',
			'api_key_desc'		=> 'The key of the MailChimp API.'
		)
	);
	
	$translations = $modx->getOption($modx->getOption('manager_language'), $translations, $translations['en']);
	
	$settings = array(
		'mailchimp.api_endpoint'	=> '',
		'mailchimp.api_key'			=> ''
	);

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
   		case xPDOTransport::ACTION_INSTALL:
   		case xPDOTransport::ACTION_UPGRADE:
   			foreach (array_keys($settings) as $key => $value) {
	   			if (null !== ($setting = $modx->getObject('modSystemSetting', $value))) {
		   			$settings[$value] = $setting->get('value');
	   			}
   			}

        	$output = '<div class="x-form-item">
				<label for="ext-comp-mailchimp1" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('api_endpoint', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="api_endpoint" id="ext-comp-mailchimp1" value="'.$modx->getOption('mailchimp.api_endpoint', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('api_endpoint_desc', $translations).'</label>
			<div class="x-form-item">
				<label for="ext-comp-mailchimp2" class="x-form-item-label" style="width: 150px;">'.$modx->getOption('api_key', $translations).'</label>
				<div class="x-form-element" style="padding-left: 155px">
					<input type="text" name="api_key" id="ext-comp-mailchimp2" value="'.$modx->getOption('mailchimp.api_key', $settings).'" class="x-form-text x-form-field" msgtarget="under" autocomplete="on" size="20" style="width: 350px;">
				</div>
				<div class="x-form-clear-left"></div>
			</div>
			<label class="desc-under" style="font-weight: normal;">'.$modx->getOption('api_key_desc', $translations).'</label>';
					
       		break;
	   	case xPDOTransport::ACTION_UNINSTALL:
        	break;
	}

	return $output;
	
?>