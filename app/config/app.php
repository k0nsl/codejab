<?php
$config = array();

// title stuff 
define('SITE_NAME', 'codejab');
define('DEFAULT_PAGE_TITLE', 'Get your code on');
define('TITLE_DELIMITER', ' - ');
define('ALLCODE_ZIP_NAME', 'codejab-all-code');
define('EMBED_TAGLINE', 'Code From ' . SITE_NAME);
// email settings
define('FROM_NAME', SITE_NAME);
define('FROM_EMAIL', 'noreply@fris.net');
define('VERIFY_EMAIL_SUBJECT', 'Verify your account');
 
 // dont edit these
define('ADMIN_ROLE', 1);
define('USER_ROLE', 0);

// items per page
define('ITEMS_PER_PAGE', 18);
define('ADMIN_ITEMS_PER_PAGE', 20);

// max length of descriptions in list
define('MAX_DESC_LENGTH', 70);

// number of paging links to show
define('RGNK_PAGING_LINKS', 15);

// min and max tag size
define('RGNK_MAX_TAG_SIZE', 20);
define('RGNK_MIN_TAG_SIZE', 12);

// template to use for thumbs up
define('THUMBSUP_TPL', 'mini_thumbs');

// db tables
define('CATEGORIES_DBTABLE', 'categories');
define('CODE_DBTABLE', 'code');
define('FAVORITES_DBTABLE', 'favorites');
define('REFTYPES_DBTABLE', 'ref_types');
define('TAGS_DBTABLE', 'tags');
define('USERS_DBTABLE', 'users');
?>
