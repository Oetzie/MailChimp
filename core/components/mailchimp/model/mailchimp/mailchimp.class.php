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

	class MailChimp {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $config = array();

		/**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		 */
		public function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;

			$corePath 		= $this->modx->getOption('mailchimp.core_path', $config, $this->modx->getOption('core_path').'components/mailchimp/');
			$assetsUrl 		= $this->modx->getOption('mailchimp.assets_url', $config, $this->modx->getOption('assets_url').'components/mailchimp/');
			$assetsPath 	= $this->modx->getOption('mailchimp.assets_path', $config, $this->modx->getOption('assets_path').'components/mailchimp/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'mailchimp'),
				'helpurl'				=> $this->modx->getOption('namespace', $config, 'mailchimp'),
				'language'				=> 'mailchimp:default',
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'api_key'				=> $this->modx->getOption('mailchimp.api_key', null, ''),
				'api_endpoint'			=> $this->modx->getOption('mailchimp.api_endpoint', null, '')
			), $config);
			
			$this->modx->addPackage('mailchimp', $this->config['model_path']);
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function subscribe($properties = array()) {
			if (false !== ($values = $this->modx->getOption('values', $properties, false))) {
				$params = array(
					'merge_vars' => array()
				);
				
				if (!empty($email = $this->modx->getOption('email', $values, ''))) {
					$params['email'] = array(
						'email' => $email
					);
				}
				
				if ($this->modx->getOption('name', $values)) {
					$params['merge_vars']['FNAME'] = $this->modx->getOption('name', $values);
				}
				
				if ($this->modx->getOption('lastname', $values)) {
					$params['merge_vars']['LNAME'] = $this->modx->getOption('lastname', $values);
				}
		
				if ($list = $this->modx->getOption('list', $values)) {
					$params['id'] = $list;
				} else if (isset($properties['list'])) {
					$params['id'] = $properties['list'];
				}
				
				$output = $this->callApi('lists/subscribe', array_merge(array(
					'email_type'		=> $this->modx->getOption('email_type', $properties, 'html'),
					'double_optin'      => (boolean) $this->modx->getOption('double_optin', $properties, false),
					'update_existing'   => (boolean) $this->modx->getOption('update_existing', $properties, true),
					'replace_interests' => (boolean) $this->modx->getOption('replace_interests', $properties, false),
					'send_welcome'      => (boolean) $this->modx->getOption('send_welcome', $properties, false),
					'apikey' 			=> $this->config['api_key']
				), $params));
				
				if (false !== $output) {
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces public.
		 * @param Array $properties.
		 * @return Boolean.
		 */
		public function unsubscribe($properties = array()) {
			if (false !== ($values = $this->modx->getOption('values', $properties, false))) {
				$params = array();
				
				if (!empty($email = $this->modx->getOption('email', $values, ''))) {
					$params['email'] = array(
						'email' => $email
					);
				}

				if ($list = $this->modx->getOption('list', $values)) {
					$params['id'] = $list;
				} else if (isset($properties['list'])) {
					$params['id'] = $properties['list'];
				}
				
				$output = $this->callApi('lists/unsubscribe', array_merge(array(
					'delete_member'     => (boolean) $this->modx->getOption('delete_member', $properties, true),
					'send_goodbye'      => (boolean) $this->modx->getOption('send_goodbye', $properties, false),
					'send_notify '		=> (boolean) $this->modx->getOption('send_notify', $properties, false),
					'apikey' 			=> $this->config['api_key']
				), $params));
				
				if (false !== $output) {
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * @acces protected.
		 * @param String $method.
		 * @param Array $params.
		 * @param String $type.
		 * @return String.
		 */
	    protected function callApi($method, $params = array(), $type = 'POST') {
		    list($key, $datacentre) = explode('-', $this->config['api_key']);
		    
		    $url = rtrim(str_replace('<dc>', $datacentre, $this->config['api_endpoint']), '/');
		    
		    $curl = curl_init();
		    
		    $response = false;
		    
			switch (strtoupper($type)) {
				case 'POST':
					curl_setopt_array($curl, array(
						CURLOPT_URL 			=> $url.'/'.$method.'.json',
						CURLOPT_HTTPHEADER		=> array('Content-Type: application/json'),
						CURLOPT_RETURNTRANSFER	=> true,
						CURLOPT_CONNECTTIMEOUT	=> 10,
						CURLOPT_POSTFIELDS		=> json_encode($params)
					));
					
					$response 	= curl_exec($curl);
					$info		= curl_getinfo($curl);

					if (!isset($info['http_code']) || '200' != $info['http_code']) {
						return false;
					}
					
					break;
				default:
					curl_setopt_array($curl, array(
						CURLOPT_URL 			=> $url.'/'.$method.'.json?'.http_build_query($params),
						CURLOPT_HTTPHEADER		=> array('Content-Type: application/json'),
						CURLOPT_RETURNTRANSFER	=> true,
						CURLOPT_CONNECTTIMEOUT	=> 10
					));
					
					$response 	= curl_exec($curl);
					$info		= curl_getinfo($curl);
				
					if (!isset($info['http_code']) || '200' != $info['http_code']) {
						return false;
					}
					
					break;
			}

			curl_close($curl);
			
			return $response;
		}
	}

?>