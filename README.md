# MODX MailChimp
![MailChimp version](https://img.shields.io/badge/version-1.2.0-blue.svg) ![MODX Extra by Oetzie.nl](https://img.shields.io/badge/checked%20by-oetzie-blue.svg) ![MODX version requirements](https://img.shields.io/badge/modx%20version%20requirement-2.4%2B-brightgreen.svg)

MailChimp is a snippet to handle MailChimp subscriptions in MODx. This is a plugin for the Form extra https://github.com/Oetzie/Form. 

**Example:**
```
{'!Form' | snippet : [
    'plugins'               => [
        'mailChimpForm'         => [
            'type'                  => 'subscribe',
            'list'                  => 'cec22053db'
        ]
    ]
]}
```