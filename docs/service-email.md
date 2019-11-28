# Send Service Email To User

A feature exists to send a "service email" to every user on the site that has not been Closed.

Users can not opt out of these emails, so they should only be used for very important announcements.

To send:

1. Edit the content of the emails in core/theme/default/templates/email/serviceEmailToUserAccount.html.twig 
and core/theme/default/templates/email/serviceEmailToUserAccount.txt.twig

2. Edit the subject in core/cli/sendServiceEmailToUserAccount.php

3. Test, commit and deploy to the server

4. On the server run:

    core/cli/sendServiceEmailToUserAccount.php FROM TO WAIT
    
* FROM - user ID to start sending from, inclusive
* TO - user ID to end sending at, inclusive
* WAIT - pause between emails in seconds

For example:

    core/cli/sendServiceEmailToUserAccount.php 1 500 15
    core/cli/sendServiceEmailToUserAccount.php 501 1000 15

This way, you can send emails in several different blocks.

## Testing Email Sending

This can also be used to test email sending on a server, as the feature will work even if the site is in Read Only mode.

Simply look up the ID of your own user account, and use the command to send an email to your account only.

For example, if your user account is 723, run:

    core/cli/sendServiceEmailToUserAccount.php 723 723 15

Then see if you got the email.
