<?php

	return array(
		array(
	        'name' 		=> 'doubleOptin',
	        'desc' 		=> 'mailchimpsubscribe_snippet_doubleoptin_desc',
	        'type' 		=> 'combo-boolean',
	        'options' 	=> '',
	        'value'		=> '0',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'emailType',
	        'desc' 		=> 'mailchimpsubscribe_snippet_emailtype_desc',
	        'type' 		=> 'list',
	        'options' 	=> array(
		        array(
					'text'	=> 'Text',
					'value'	=> 'text',
					'name'	=> 'Text'
				),
				array(
					'text'	=> 'HTML',
					'value'	=> 'html',
					'name'	=> 'HTML'
				)
	        ),
	        'value'		=> 'html',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'replaceInterests',
	        'desc' 		=> 'mailchimpsubscribe_snippet_replaceinterests_desc',
	        'type' 		=> 'combo-boolean',
	        'options' 	=> '',
	        'value'		=> '0',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'sendWelcome',
	        'desc' 		=> 'mailchimpsubscribe_snippet_sendwelcome_desc',
	        'type' 		=> 'combo-boolean',
	        'options' 	=> '',
	        'value'		=> '0',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    ),
	    array(
	        'name' 		=> 'updateExisting',
	        'desc' 		=> 'mailchimpsubscribe_snippet_updateexisting_desc',
	        'type' 		=> 'combo-boolean',
	        'options' 	=> '',
	        'value'		=> '1',
	        'area'		=> PKG_NAME_LOWER,
	        'lexicon' 	=> PKG_NAME_LOWER.':default'
	    )
	);

?>