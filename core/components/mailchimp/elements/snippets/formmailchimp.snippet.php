<?php
/**
 * MailChimp
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$class = $modx->loadClass('MailChimpSnippetFormMailChimp', $modx->getOption('mailchimp.core_path', null, $modx->getOption('core_path') . 'components/mailchimp/') . 'model/mailchimp/snippets/', false, true);

if ($class) {
    $instance = new $class($modx);

    if ($instance instanceof MailChimpSnippets) {
        return $instance->run($event, $properties, $form);
    }
}

return '';