# moodle-block_obu_forms
OBU Forms Block

Displays all submitted forms that are awaiting authorisation by the current user.

<h2>USAGE</h2>

The forms block should be added 'site-wide' by an administrator and be promoted near to the top.

It displays in the block the dates of all submitted forms that are awaiting authorisation by the current user (in ascending order). If any of these authorisations are overdue (see settings) then their dates are shown in bold.  A link to the form to authorise is also displayed.

The settings of this block will set the number of days to use to determine if an authorisation is overdue (defaults to 7 days).

<h2>INSTALLATION</h2>

This block is dependant on the moodle-local_obu_forms plugin.

This should be installed in the blocks directory of the Moodle instance.
