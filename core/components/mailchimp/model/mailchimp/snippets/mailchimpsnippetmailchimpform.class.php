<?php

/**
 * MailChimp
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

require_once dirname(__DIR__) . '/mailchimpsnippets.class.php';

class MailChimpSnippetMailChimpForm extends MailChimpSnippets
{
    /**
     * @access public.
     * @var Array.
     */
    public $properties = [
        'type'          => '',
        'list'          => '',
        'emailType'     => 'html',
        'mergeFields'   => [
            'FNAME'         => 'firstname',
            'LNAME'         => 'lastname',
            'PHONE'         => 'phone'
        ],
        'aliasFields'   => [
            'email'         => 'email',
            'email_type'    => 'email_type'
        ],
        'optin'         => true,
        'optinField'    => false
    ];

    /**
     * @access public.
     * @param String $event.
     * @param Array $properties.
     * @param Object $form.
     * @return Boolean.
     */
    public function run($event, array $properties = [], $form)
    {
        if ($event === FormEvents::VALIDATE_SUCCESS) {
            $this->setProperties($this->getFormattedProperties($properties));

            $aliasFields = $this->getProperty('aliasFields');

            if ($this->getProperty('type') === 'subscribe') {
                $email = $form->getCollection()->getValue($aliasFields['email'] ?: 'email');
                $optin = $form->getCollection()->getValue($this->getProperty('optinField'));

                if (!empty($email) && (!empty($optin) || empty($this->getProperty('optinField')))) {
                    $parameters = [
                        'status'        => 'subscribed',
                        'list_id'       => $this->getProperty('list'),
                        'id'            => md5(strtolower($email)),
                        'email_address' => $email,
                        'email_type'    => $this->getProperty('emailType'),
                        'merge_fields'  => []
                    ];

                    if ((bool) $this->getProperty('optin')) {
                        $parameters['status'] = 'pending';
                    }

                    foreach ((array) $this->getProperty('mergeFields') as $key => $field) {
                        $value = $form->getCollection()->getValue($field);

                        if ($value) {
                            $parameters['merge_fields'][strtoupper($key)] = $value;
                        }
                    }

                    foreach ((array) $aliasFields as $key => $field) {
                        $value = $form->getCollection()->getValue($field);

                        if ($value) {
                            $parameters[$key] = $value;
                        }
                    }

                    $results = $this->makeRequest('lists/' . $parameters['list_id'] . '/members/' . $parameters['id'], $parameters);

                    if ((int) $results['code'] === 200) {
                        return true;
                    }

                    $form->getValidator()->setError('error_message', $this->modx->lexicon('mailchimp.subscribe_failed'));

                    return false;
                }
            }

            if ($this->getProperty('type') === 'unsubscribe') {
                $email = $form->getCollection()->getValue($aliasFields['email'] ?: 'email');
                $optin = $form->getCollection()->getValue($this->getProperty('optinField'));

                if (!empty($email) && (!empty($optin) || empty($this->getProperty('optinField')))) {
                    $parameters = [
                        'status'        => 'unsubscribed',
                        'list_id'       => $this->getProperty('list'),
                        'id'            => md5(strtolower($email)),
                        'email_address' => $email
                    ];

                    $results = $this->makeRequest('lists/' . $parameters['list_id'] . '/members/' . $parameters['id'], $parameters);

                    if ((int) $results['code'] === 200) {
                        return true;
                    }

                    $form->getValidator()->setError('error_message', $this->modx->lexicon('mailchimp.unsubscribe_failed'));

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @access public.
     * @param Integer $code.
     * @param Array|String $data.
     * @return Array.
     */
    public function getResponse($code, $data)
    {
        if ((int) $code === 200) {
            return [
                'code'      => (int) $code,
                'data'      => $data
            ];
        }

        return [
            'code'      => (int) $code,
            'message'   => $data
        ];
    }

    /**
     * @access public.
     * @param String $endpoint.
     * @param Array $parameters.
     * @param String $method.
     * @return Array.
     */
    public function makeRequest($endpoint, array $parameters = [], $method = 'PUT')
    {
        list($key, $datacentre) = explode('-', $this->getOption('api_key'));

        $url = str_replace('<dc>', $datacentre, $this->getOption('api_endpoint'));

        $options = [
            CURLOPT_HEADER          => false,
            CURLOPT_USERAGENT       => 'MailChimp 1.0',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_USERPWD         => 'user:' . $this->getOption('api_key')
        ];

        if (strtoupper($method) === 'POST') {
            $options = [
                CURLOPT_URL             => rtrim($url, '/') . '/' . trim($endpoint, '/') . '/',
                CURLOPT_POSTFIELDS      => json_encode($parameters)
            ] + $options;
        } else if (strtoupper($method) === 'PUT') {
            $options = [
                CURLOPT_CUSTOMREQUEST   => 'PUT',
                CURLOPT_URL             => rtrim($url, '/') . '/' . trim($endpoint, '/') . '/',
                CURLOPT_POSTFIELDS      => json_encode($parameters)
            ] + $options;
        } else {
            $options = [
                CURLOPT_URL             => rtrim($url, '/') . '/' . trim($endpoint, '/') . '/' . http_build_query($parameters)
            ] + $options;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response       = curl_exec($curl);
        $responseInfo   = curl_getinfo($curl);

        curl_close($curl);

        if (!isset($responseInfo['http_code']) || (int) $responseInfo['http_code'] !== 200) {
            $reponseError = json_decode($response, true);

            if (isset($reponseError['detail'])) {
                return $this->getResponse($responseInfo['http_code'], $reponseError['detail']);
            }

            return $this->getResponse($responseInfo['http_code'], 'API returned incorrect HTTP code.');
        }

        return $this->getResponse(200, json_decode($response, true));
    }
}
