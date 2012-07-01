Description
----------------------

Codejab is a snippet CMS built using PHP, MYSQL, CSS, and
JavaScript with some jQuery tweaks.

This project started on wordpress with a stripped down admin,
and wp-syntax highlighting plugin.

It worked well for what I wanted,but I felt it was time to move it 
to its own lightweight CMS.

Installation
----------------------
1. Upload contents to the server
2. Visit your domain.com/install/ to Install the CMS
3. Edit Line 50 of /contact/contact.php if you wish to use the AJAX Contact form

Mods / Customizing
------------------------------------------
* Pages functionality is in app/controllers, templates are in app/views
* Pages derive from base classes in app/core/RGNK_Controller.php
* Database functionality is in app/models
* If you name a javascript the file the same as the path part of the URL it will auto load jQuery goodness (ie: code/c.js will auto load on the code by categories page), views are named in a similar fashion
* Styles are in css/main.css

CMS Features
------------------------------------------
* Easy Installer
* Clean and simple interface for posting code by upload or cut and paste
* Loading content via AJAX
* Syntax highlighting with geshi
* Ability for code to expire by date (with jQuery date picker)
* Ajax login/register process with status messages
* Favorite system for registered users
* User submission system (must be approved to submit code)
* Categories and Tags
* Tag cloud based on posts
* Pagination system for page(s) of snippets
* Auto complete search implementation
* Download of each snippet (.php for php, .pl for perl, etc)
* Download source code in .pdf format
* Download zip of code snippet
* Printing of source code with color highlighting
* Download zip archive of all source code
* Thumbs-up rating system
* Slide down share box for sharing code
* Embed code for external sites

Credits
------------------------------------------
* Icons for admin panel
   - http://www.famfamfam.com/lab/icons/silk/

* jQuery UI (auto complete and date picker)
   - http://jqueryui.com/

* Timer picker (add on for jQuery UI date picker)
   - http://trentrichardson.com/examples/timepicker/

* jQuery jqprint (printing source code)
   - http://www.recoding.it/?p=138

* jQuery Form (login/signup)
   - http://jquery.malsup.com/form/

* GeSHi Syntax Highlighter (for all source code snippets)
   - http://qbnz.com/highlighter/

* dompdf (for generating pdfs)
   - http://code.google.com/p/dompdf/
