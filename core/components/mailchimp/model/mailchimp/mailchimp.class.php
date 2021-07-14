<?php

/**
 * MailChimp
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

class MailChimp
{
    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @param modX $modx.
     * @param Array $config.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('mailchimp.core_path', $config, $this->modx->getOption('core_path') . 'components/mailchimp/');
        $assetsUrl  = $this->modx->getOption('mailchimp.assets_url', $config, $this->modx->getOption('assets_url') . 'components/mailchimp/');
        $assetsPath = $this->modx->getOption('mailchimp.assets_path', $config, $this->modx->getOption('assets_path') . 'components/mailchimp/');

        $this->config = array_merge([
            'namespace'             => 'mailchimp',
            'lexicons'              => ['mailchimp:default'],
            'base_path'             => $corePath,
            'core_path'             => $corePath,
            'model_path'            => $corePath . 'model/',
            'processors_path'       => $corePath . 'processors/',
            'elements_path'         => $corePath . 'elements/',
            'chunks_path'           => $corePath . 'elements/chunks/',
            'plugins_path'          => $corePath . 'elements/plugins/',
            'snippets_path'         => $corePath . 'elements/snippets/',
            'templates_path'        => $corePath . 'templates/',
            'assets_path'           => $assetsPath,
            'js_url'                => $assetsUrl . 'js/',
            'css_url'               => $assetsUrl . 'css/',
            'assets_url'            => $assetsUrl,
            'connector_url'         => $assetsUrl . 'connector.php',
            'version'               => '1.3.0',
            'api_endpoint'          => $this->modx->getOption('mailchimp.api_endpoint', null, 'https://<dc>.api.mailchimp.com/3.0/'),
            'api_key'               => $this->modx->getOption('mailchimp.api_key')
        ], $config);

        $this->modx->addPackage('mailchimp', $this->config['model_path']);

        if (is_array($this->config['lexicons'])) {
            foreach ($this->config['lexicons'] as $lexicon) {
                $this->modx->lexicon->load($lexicon);
            }
        } else {
            $this->modx->lexicon->load($this->config['lexicons']);
        }
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $options.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        if (isset($options[$key])) {
            return $options[$key];
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->modx->getOption($this->config['namespace'] . '.' . $key, $options, $default);
    }
}
