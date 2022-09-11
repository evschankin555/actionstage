<?php
echo aui()->alert(array(
		'type'=> 'warning',
		'content'=> __('Sorry, no results were found.', 'directory-starter')
	)
);
get_search_form();
?>