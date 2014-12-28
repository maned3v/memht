<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2012 by Miltenovikj Manojlo <dev@miltenovik.com>
// http://www.memht.com
// 
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your opinion) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License along
// with this program; if not, see <http://www.gnu.org/licenses/> (GPLv2)
// or write to the Free Software Foundation, Inc., 51 Franklin Street,
// Fifth Floor, Boston, MA02110-1301, USA.
//========================================================================

/**
 * @author      Miltenovikj Manojlo <dev@miltenovik.com>
 * @author		Paulo Ferreira <sisnox@gmail.com>
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

global $config_sys,$memht_lang;

//Language direction
$memht_lang[$config_sys['language']]['LANGDIR'] = 'ltr';
$memht_lang[$config_sys['language']]['LANGID'] = 'en_US';
$memht_lang[$config_sys['language']]['LOCALE'] = array(LC_ALL,'en_US');

//Common
$memht_lang[$config_sys['language']]['INTERROR'] = 'Internal error: %s. Please contact the site administrator if this problem persists';
$memht_lang[$config_sys['language']]['WRONG_DATA_X'] = 'Wrong data (%s)';
$memht_lang[$config_sys['language']]['ADD'] = 'Add';
$memht_lang[$config_sys['language']]['SAVE'] = 'Save';
$memht_lang[$config_sys['language']]['PUBLISH'] = 'Publish';
$memht_lang[$config_sys['language']]['PUBLISHED'] = 'Published';
$memht_lang[$config_sys['language']]['DRAFT'] = 'Draft';
$memht_lang[$config_sys['language']]['ACTIVE'] = 'Active';
$memht_lang[$config_sys['language']]['INACTIVE'] = 'Inactive';
$memht_lang[$config_sys['language']]['ACPONLY'] = 'Administration CP only';
$memht_lang[$config_sys['language']]['DELETED'] = 'Deleted';
$memht_lang[$config_sys['language']]['EDIT'] = 'Edit';
$memht_lang[$config_sys['language']]['EDIT_THIS_X'] = 'Edit this %s';
$memht_lang[$config_sys['language']]['EDIT_X'] = 'Edit %s';
$memht_lang[$config_sys['language']]['LAST_UPDATED_ON_X'] = 'Last updated on %s';
$memht_lang[$config_sys['language']]['LIST_EMPTY'] = 'Empty';
$memht_lang[$config_sys['language']]['ARTICLE'] = 'Article';
$memht_lang[$config_sys['language']]['ARTICLES'] = 'Articles';
$memht_lang[$config_sys['language']]['POST'] = 'Post';
$memht_lang[$config_sys['language']]['POSTS'] = 'Posts';
$memht_lang[$config_sys['language']]['READ_MORE'] = 'Read more';
$memht_lang[$config_sys['language']]['TAGS'] = 'Tags';
$memht_lang[$config_sys['language']]['NO_TAGS'] = 'There are no tags';
$memht_lang[$config_sys['language']]['WRITTEN_IN_X_BY_Y_ON_Z'] = 'Written in %s by %s on %s';
$memht_lang[$config_sys['language']]['WRITTEN_BY_X_ON_Y'] = 'Written by %s on %s';
$memht_lang[$config_sys['language']]['ADDED_IN_X_BY_Y_ON_Z'] = 'Added in %s by %s on %s';
$memht_lang[$config_sys['language']]['ADDED_BY_X_ON_Y'] = 'Added by %s on %s';
$memht_lang[$config_sys['language']]['X_NOT_FOUND_OR_INACTIVE'] = '%s not found or inactive';
$memht_lang[$config_sys['language']]['X_NOT_FOUND'] = '%s not found';
$memht_lang[$config_sys['language']]['PRINT'] = 'Print';
$memht_lang[$config_sys['language']]['CATEGORY'] = 'Category';
$memht_lang[$config_sys['language']]['CATEGORIES'] = 'Categories';
$memht_lang[$config_sys['language']]['RELATED_X'] = 'Related %s';
$memht_lang[$config_sys['language']]['COMMENT'] = 'Comment';
$memht_lang[$config_sys['language']]['COMMENTS'] = 'Comments';
$memht_lang[$config_sys['language']]['RECENT_COMMENTS'] = 'Recent comments';
$memht_lang[$config_sys['language']]['X_COMMENTS'] = '%d Comments';
$memht_lang[$config_sys['language']]['RATING'] = 'Rating';
$memht_lang[$config_sys['language']]['YOURE_IN'] = 'You\'re in';
$memht_lang[$config_sys['language']]['FILE_NOT_FOUND'] = 'File not found';
$memht_lang[$config_sys['language']]['NAVIGATOR'] = 'Navigator';
$memht_lang[$config_sys['language']]['PDF'] = 'Pdf';
$memht_lang[$config_sys['language']]['RSS'] = 'Rss';
$memht_lang[$config_sys['language']]['CREATED'] = 'Created';
$memht_lang[$config_sys['language']]['MODIFIED'] = 'Modified';
$memht_lang[$config_sys['language']]['ENABLED'] = 'Enabled';
$memht_lang[$config_sys['language']]['DISABLED'] = 'Disabled';
$memht_lang[$config_sys['language']]['UNKNOWN'] = 'Unknown';
$memht_lang[$config_sys['language']]['PAGE'] = 'Page';
$memht_lang[$config_sys['language']]['STATIC_PAGE'] = 'Static page';
$memht_lang[$config_sys['language']]['STATIC_PAGES'] = 'Static pages';
$memht_lang[$config_sys['language']]['FIRST'] = 'First';
$memht_lang[$config_sys['language']]['LAST'] = 'Last';
$memht_lang[$config_sys['language']]['NEXT'] = 'Next';
$memht_lang[$config_sys['language']]['PREVIOUS'] = 'Previous';
$memht_lang[$config_sys['language']]['THANKS_FOR_VOTING'] = 'Thanks for voting';
$memht_lang[$config_sys['language']]['YOU_ALREADY_VOTED'] = 'Sorry, you have already voted';
$memht_lang[$config_sys['language']]['YOU_ALREADY_VOTED_ON_THIS_X'] = 'Sorry, you have already voted on this %s';
$memht_lang[$config_sys['language']]['DATE'] = 'Date';
$memht_lang[$config_sys['language']]['NO_X_SELECTED'] = 'No %s selected';
$memht_lang[$config_sys['language']]['X_MUST_BE_MIN_Y_CHARS_LONG'] = 'The %s must be at least %d characters long';
$memht_lang[$config_sys['language']]['X_CHAR_MIN'] = '%d characters minimum';
$memht_lang[$config_sys['language']]['LOCATION'] = 'Location';
$memht_lang[$config_sys['language']]['RESULTS'] = 'Results';
$memht_lang[$config_sys['language']]['ANYWHERE'] = 'Anywhere';
$memht_lang[$config_sys['language']]['EVERYONE'] = 'Everyone';
$memht_lang[$config_sys['language']]['ANY'] = 'Any';
$memht_lang[$config_sys['language']]['NOT_AUTH_TO_ACCESS_X'] = 'Sorry, you are not authorized to access this %s';
$memht_lang[$config_sys['language']]['WHO_ACCESS_THE_X'] = 'Who can access the %s';
$memht_lang[$config_sys['language']]['MULTIPLE_CHOICES_ALLOWED'] = 'Multiple choices are allowed';
$memht_lang[$config_sys['language']]['ROLES'] = 'Roles';
$memht_lang[$config_sys['language']]['X_GUESTS_Y_USERS_ONLINE'] = 'There are currently %d guests and %d users online';
$memht_lang[$config_sys['language']]['SHARE'] = 'Share';
$memht_lang[$config_sys['language']]['BROWSE_ALL_X'] = 'Browse all %s';
$memht_lang[$config_sys['language']]['ELEMENTS'] = 'Elements';
$memht_lang[$config_sys['language']]['CLICK_TO_SELECT'] = 'Click to select';
$memht_lang[$config_sys['language']]['CREATE_FOLDER'] = 'You need to create the following folder and make it writable to get the feature working';

//Send by email
$memht_lang[$config_sys['language']]['TONAME'] = 'To name';
$memht_lang[$config_sys['language']]['TOEMAIL'] = 'To email';
$memht_lang[$config_sys['language']]['FROMNAME'] = 'From name';
$memht_lang[$config_sys['language']]['FROMEMAIL'] = 'From email';
$memht_lang[$config_sys['language']]['USE_FORM_EMAIL_POST_FRIEND'] = 'Use this form to email the post to a friend';

//Common: Monts
$memht_lang[$config_sys['language']]['JAN'] = 'January';
$memht_lang[$config_sys['language']]['FEB'] = 'February';
$memht_lang[$config_sys['language']]['MAR'] = 'March';
$memht_lang[$config_sys['language']]['APR'] = 'April';
$memht_lang[$config_sys['language']]['MAY'] = 'May';
$memht_lang[$config_sys['language']]['JUN'] = 'June';
$memht_lang[$config_sys['language']]['JUL'] = 'July';
$memht_lang[$config_sys['language']]['AUG'] = 'August';
$memht_lang[$config_sys['language']]['SEP'] = 'September';
$memht_lang[$config_sys['language']]['OCT'] = 'October';
$memht_lang[$config_sys['language']]['NOV'] = 'November';
$memht_lang[$config_sys['language']]['DEC'] = 'December';

//Comments
$memht_lang[$config_sys['language']]['THERE_ARE_X_COMMENTS_ABOUT_Y'] = 'There are %d comments about `%s`';
$memht_lang[$config_sys['language']]['THERE_ARE_NO_COMMENTS_YET'] = 'There are no comments yet';
$memht_lang[$config_sys['language']]['LEAVE_A_COMMENT'] = 'Leave a comment';
$memht_lang[$config_sys['language']]['POST_COMMENT'] = 'Post comment';
$memht_lang[$config_sys['language']]['NAME'] = 'Name';
$memht_lang[$config_sys['language']]['EMAIL'] = 'Email';
$memht_lang[$config_sys['language']]['URL'] = 'Url';
$memht_lang[$config_sys['language']]['MESSAGE'] = 'Message';
$memht_lang[$config_sys['language']]['REQUIRED'] = 'Required';
$memht_lang[$config_sys['language']]['THIS_FIELD_IS_REQUIRED'] = 'This field is required';
$memht_lang[$config_sys['language']]['LOGIN_TO_WRITE_COMMENT'] = 'Please log in to write a comment';
$memht_lang[$config_sys['language']]['COMMENTS_MODERATED_BEFORE_PUBLISHED'] = 'Comments could be moderated before being published';
$memht_lang[$config_sys['language']]['YOUR_COMMENT_MODERATED_BEFORE_PUBLISHED'] = 'Your comment will be moderated before being published';
$memht_lang[$config_sys['language']]['BBCODE_FORMAT_MESSAGES'] = 'You can use BBCode to format messages';

//Contact
$memht_lang[$config_sys['language']]['SEND'] = 'Send';
$memht_lang[$config_sys['language']]['MESSAGE_SENT'] = 'Your message has been sent';
$memht_lang[$config_sys['language']]['MESSAGE_NOT_SENT'] = 'Sorry, your message has not been sent';
$memht_lang[$config_sys['language']]['TO_VIEW_HTML_MEX_COMPAT_VIEWER'] = 'To view the message, please use an HTML compatible email viewer';

//Core
$memht_lang[$config_sys['language']]['HOME'] = 'Home';
$memht_lang[$config_sys['language']]['REQUEST_URL_CANNOT_BE_PROCESSED'] = 'The requested URL cannot be processed';
$memht_lang[$config_sys['language']]['DELETE_THE_INSTALLATION_FOLDER'] = 'Delete the installation folder';

//Forms
$memht_lang[$config_sys['language']]['INVALID_TOKEN'] = 'Invalid token. Please contact the site administrator if this problem persists';
$memht_lang[$config_sys['language']]['THE_FIELD_X_IS_REQUIRED'] = 'The field `%s` is required';
$memht_lang[$config_sys['language']]['THE_FIELD_X_CONTAINS_INVALID_Y'] = 'The field `%s` contains an invalid %s';
$memht_lang[$config_sys['language']]['THE_FIELD_X_IS_NOT_INVALID'] = 'The %s is not valid';
$memht_lang[$config_sys['language']]['WRONG_CAPTCHA_TEXT'] = 'You have entered a wrong captcha text';
$memht_lang[$config_sys['language']]['TYPE_CHARS_YOU_SEE'] = 'Type the characters you see';
$memht_lang[$config_sys['language']]['X_NOT_VALID'] = '%s not valid';

//Search
$memht_lang[$config_sys['language']]['NO_RESULTS_FOUND'] = 'No results found';

//Surveys
$memht_lang[$config_sys['language']]['SURVEY'] = 'Survey';
$memht_lang[$config_sys['language']]['SURVEYRESULTS'] = 'Survey result';
$memht_lang[$config_sys['language']]['SUBMITVOTE'] = 'Submit your vote';

//Upload
$memht_lang[$config_sys['language']]['UPLOAD'] = 'Upload';
$memht_lang[$config_sys['language']]['FOLDER_NOT_WRITABLE'] = 'Sorry, the file cannot be uploaded because of a lack of writing permissions';
$memht_lang[$config_sys['language']]['FILE_TOO_LARGE_X_MAX'] = 'Sorry, the file is too large and cannot be uploaded (%s KB max)';
$memht_lang[$config_sys['language']]['FILE_TYPE_NOT_ACCEPTED_X'] = 'Sorry, the file type is not accepted (Valid file types: %s)';
$memht_lang[$config_sys['language']]['IMAGE_TOO_LARGE_WxH_MAX'] = 'Sorry, the image is too large (%s max)';
$memht_lang[$config_sys['language']]['FILENAME_NOT_ACCEPTED'] = 'Sorry, the file name is not accepted';
$memht_lang[$config_sys['language']]['FILE_ALREADY_EXISTS'] = 'Sorry, a file with the same name already exists';
$memht_lang[$config_sys['language']]['FILE_NOT_UPLOADED_CHECK_PRIVS'] = 'Sorry, the file hasn\'t been uploaded, check the file permissions and try again';
$memht_lang[$config_sys['language']]['IMAGE_TOO_LARGE_CANNOT_BE_RESIZED_WxH_MAX'] = 'Sorry, the image is too large (%s max) and cannot be resized. Try to convert it in jpg, gif or png first';
$memht_lang[$config_sys['language']]['THUMB_CANNOT_BE_CREATED'] = 'Sorry, the thumbnail cannot be created. Try to convert the image in jpg, gif or png first';

//User
$memht_lang[$config_sys['language']]['HI_X'] = 'Hi %s';
$memht_lang[$config_sys['language']]['DISPLAY_NAME'] = 'Display name';
$memht_lang[$config_sys['language']]['USERNAME'] = 'Username';
$memht_lang[$config_sys['language']]['USERNAMES'] = 'Usernames';
$memht_lang[$config_sys['language']]['PASSWORD'] = 'Password';
$memht_lang[$config_sys['language']]['CONFIRM_X'] = 'Confirm %s';
$memht_lang[$config_sys['language']]['REMEMBERME'] = 'Remember me';
$memht_lang[$config_sys['language']]['LOGIN'] = 'Log In';
$memht_lang[$config_sys['language']]['LOGOUT'] = 'Log Out';
$memht_lang[$config_sys['language']]['LOSTPASS'] = 'Lost password';
$memht_lang[$config_sys['language']]['REGISTER'] = 'Register';
$memht_lang[$config_sys['language']]['CHANGE_X'] = 'Change your %s';
$memht_lang[$config_sys['language']]['PROFILE'] = 'Profile';
$memht_lang[$config_sys['language']]['USER_AND_PASS_NOT_VALID'] = 'Your username and password are not valid';
$memht_lang[$config_sys['language']]['GUEST'] = 'Guest';
$memht_lang[$config_sys['language']]['USER'] = 'User';
$memht_lang[$config_sys['language']]['ADMIN'] = 'Administrator';
$memht_lang[$config_sys['language']]['REG_NEWACCOUNTS_CLOSED'] = 'Sorry, the registration of new accounts is closed';
$memht_lang[$config_sys['language']]['REG_NEWACCOUNTS_INVITATIONONLY'] = 'Sorry, new accounts can be registered by invitation only';
$memht_lang[$config_sys['language']]['REG_NEWACCOUNTS_INVITECODERR'] = 'Sorry, your invitation code is not valid or has expired';
$memht_lang[$config_sys['language']]['PASS_DONT_MATCH'] = 'Your passwords don\'t match';
$memht_lang[$config_sys['language']]['USER_OR_EMAIL_ALREADY_EXIST'] = 'The username or the email already exist in our database';
$memht_lang[$config_sys['language']]['ACTIVATE_ACCOUNT_AT_X'] = 'Activate your account at %s';
$memht_lang[$config_sys['language']]['ACCOUNT_ACTIVED_NOW_UCAN_LOGIN'] = 'Your account has been activated, now you can login to your account';
$memht_lang[$config_sys['language']]['ACCOUNT_ACTIVED_SOON_BYADMIN'] = 'Your account will be activated by an administrator as soon as possible and you should receive an email when it is done';
$memht_lang[$config_sys['language']]['YOU_RECEIVE_ACT_LINK_ACCOUNT_EXPIRE_IN_X'] = 'Registration successful! You should receive an activation link on your email soon. The account will expire automatically after %d hours if not activated';
$memht_lang[$config_sys['language']]['REG_SUC_TECH_PROB_CONTACT_ADMIN_ACC_ACTIVATED'] = 'Registration successful! Anyway, the system was unable to send you the activation link by email. Please contact the site administrator to have your account activated.';
$memht_lang[$config_sys['language']]['EMAIL_ACTIVATION_TEXT'] = 'Dear %s,

Thank you for registering at %s.

To activate your account, click on the following link:
%s

If you have not registered at %s, ignore this e-mail.

Feel free to contact us if you have any problems with your account\'s activation.

Have a nice day,
%s';
$memht_lang[$config_sys['language']]['CODE_WRONG_OR_ACCOUNT_EXPIRED'] = 'Sorry, the verification code is wrong or your account has expired';
$memht_lang[$config_sys['language']]['X_CONFIRMED'] = '%s confirmed';
$memht_lang[$config_sys['language']]['TIMEZONE'] = 'Timezone';
$memht_lang[$config_sys['language']]['DATESTAMP'] = 'Date format';
$memht_lang[$config_sys['language']]['TIMESTAMP'] = 'Time format';
$memht_lang[$config_sys['language']]['DAYS'] = 'Days';
$memht_lang[$config_sys['language']]['HOURS'] = 'Hours';
$memht_lang[$config_sys['language']]['MINUTES'] = 'Minutes';
$memht_lang[$config_sys['language']]['VALUE_EXPRESSED_IN_X'] = 'Value expressed in %s';
$memht_lang[$config_sys['language']]['READ_THIS_X_FOR_MORE_INFORMATION'] = 'Read <a href="%s" rel="external"><em>this</em></a> for more information';
$memht_lang[$config_sys['language']]['PREFERRED_ROLE'] = 'Preferred role';
$memht_lang[$config_sys['language']]['AVATAR'] = 'Avatar';
$memht_lang[$config_sys['language']]['NO_AVATAR'] = 'No avatar';
$memht_lang[$config_sys['language']]['AVATAR_ENGINE'] = 'Avatar engine';
$memht_lang[$config_sys['language']]['AVATAR_TYPE_INFO_X_Y'] = 'JPEG, GIF and PNG images are allowed. Max %s, %s.';
$memht_lang[$config_sys['language']]['IMAGE_TYPE_INFO_X_Y'] = 'JPEG, GIF and PNG images are allowed. Max %s, %s.';
$memht_lang[$config_sys['language']]['NO_IMAGE_SELECTED'] = 'No image selected';
$memht_lang[$config_sys['language']]['INVITATION_CODE'] = 'Invitation code';
$memht_lang[$config_sys['language']]['EMAIL_RESET_TEXT'] = 'Dear %s,

You asked to reset your password at %s.

In order to do that, click on the following link:
%s

If you have not requested to reset your password, ignore this e-mail.

Feel free to contact us if you have any problems with your account.

Have a nice day,
%s';
$memht_lang[$config_sys['language']]['RESET_PASSWORD_AT_X'] = 'Reset your password at %s';
$memht_lang[$config_sys['language']]['YOU_RECEIVE_RESET_LINK'] = 'You should receive the password reset link on your email soon.';
$memht_lang[$config_sys['language']]['ERROR_SENDING_MAIL_CONTACT_ADMIN'] = 'The system was unable to send the email. Please contact the site administrator if this problem persists.';
$memht_lang[$config_sys['language']]['CODE_WRONG'] = 'Sorry, the verification code is wrong';
$memht_lang[$config_sys['language']]['VERIF_DATA_MISSING'] = 'Sorry, the verification data is missing';
$memht_lang[$config_sys['language']]['PASSWORD_CHANGED_USER_IS_X'] = 'Your password has been changed successfully. We want to remind you that your user name is `%s`.';
$memht_lang[$config_sys['language']]['EMAIL_ACCT_ACTIVATED_TEXT'] = 'Dear %s,

Your account (%s) at %s has been activated.

Click on the following link to log in:
%s

Feel free to contact us if you have any problems with your account.

Have a nice day,
%s';
$memht_lang[$config_sys['language']]['EMAIL_ACCT_SOCIAL_ACTIVATED_TEXT'] = 'Dear %s,

Your account at %s has been activated.

These are your login details (you can change the randomly generated password in your profile):
Username: %s
Password: %s

Click on the following link to log in now:
%s

Feel free to contact us if you have any problems with your account.

Have a nice day,
%s';
$memht_lang[$config_sys['language']]['ACCOUNT_ACTIVATED_AT_X'] = 'Your account at %s has been activated';
$memht_lang[$config_sys['language']]['ACCOUNT_ACTIVATED'] = 'Your account has been activated.';
$memht_lang[$config_sys['language']]['ACCOUNT_LOGIN_DETAILS_X_Y_Z'] = 'Username: %s<br />Password: %s<br /><br /><strong><a href="%s">Log into your account now!</a></strong>';
$memht_lang[$config_sys['language']]['ACCOUNT_SOCIAL_EMAIL_EXISTS_X'] = 'Hi %s, it seems you\'re already registered here.';
$memht_lang[$config_sys['language']]['ACCOUNT_SOCIAL_EMAIL_DETAILS_X_Y'] = 'Name: %s<br />Email: %s<br /><br /><em>Please use the \'Lost password\' tool if you can\'t remember your login details.</em>';
$memht_lang[$config_sys['language']]['SOCIAL_LOGIN_WITH_FACEBOOK'] = 'Sign in with Facebook';
$memht_lang[$config_sys['language']]['SOCIAL_LOGIN_WITH_GOOGLE'] = 'Sign in with Google';

/**
 * AdminCP
 */
$memht_lang[$config_sys['language']]['ADMINISTRATION'] = 'Administration';
$memht_lang[$config_sys['language']]['LOADING'] = 'Loading...';
$memht_lang[$config_sys['language']]['SAVED'] = 'Saved';
$memht_lang[$config_sys['language']]['NOT_SAVED'] = 'Not saved';
$memht_lang[$config_sys['language']]['OPTIONS'] = 'Options';
$memht_lang[$config_sys['language']]['PLUGIN_OPTIONS'] = 'Plugin options';
$memht_lang[$config_sys['language']]['TEMPLATE_STICKERS'] = 'Template stickers';
$memht_lang[$config_sys['language']]['STICKER'] = 'Sticker';
$memht_lang[$config_sys['language']]['SELECT'] = 'Select';
$memht_lang[$config_sys['language']]['CUSTOM'] = 'Custom';
$memht_lang[$config_sys['language']]['CUSTOM_X'] = 'Custom %s';
$memht_lang[$config_sys['language']]['DEFAULT_X'] = 'Default %s';
$memht_lang[$config_sys['language']]['TEMPLATE'] = 'Template';
$memht_lang[$config_sys['language']]['DESKTOP'] = 'Desktop';
$memht_lang[$config_sys['language']]['MOBILE'] = 'Mobile';
$memht_lang[$config_sys['language']]['CONTENT'] = 'Content';
$memht_lang[$config_sys['language']]['LABEL'] = 'Label';
$memht_lang[$config_sys['language']]['AUTHORIZATION_MANAGER'] = 'Authorization manager';
$memht_lang[$config_sys['language']]['REQUIRED_ROLES'] = 'Required roles';
$memht_lang[$config_sys['language']]['REVISION_MANAGEMENT'] = 'Revision management';
$memht_lang[$config_sys['language']]['REVISION'] = 'Revision';
$memht_lang[$config_sys['language']]['REVISIONS'] = 'Revisions';
$memht_lang[$config_sys['language']]['DELETE_ALL_REVISIONS'] = 'Delete all revisions';
$memht_lang[$config_sys['language']]['SELECT_2_DIST_REV_COMP'] = 'Select two distinct revisions to compare';
$memht_lang[$config_sys['language']]['CURRENT'] = 'Current';
$memht_lang[$config_sys['language']]['YES'] = 'Yes';
$memht_lang[$config_sys['language']]['NO'] = 'No';
$memht_lang[$config_sys['language']]['MAIN_X'] = 'Main %s';
$memht_lang[$config_sys['language']]['THUMBNAIL'] = 'Thumbnail';
$memht_lang[$config_sys['language']]['ADD_NEW_X'] = 'Add new %s';
$memht_lang[$config_sys['language']]['ADD'] = 'Add';
$memht_lang[$config_sys['language']]['IMAGE'] = 'Image';
$memht_lang[$config_sys['language']]['IMAGES'] = 'Images';
$memht_lang[$config_sys['language']]['LAST_X'] = 'Last %s';
$memht_lang[$config_sys['language']]['BROWSE_THIS_X'] = 'Browse this %s';
$memht_lang[$config_sys['language']]['AUTO'] = 'Auto';
$memht_lang[$config_sys['language']]['APPROVE'] = 'Approve';
$memht_lang[$config_sys['language']]['APPROVED'] = 'Approved';
$memht_lang[$config_sys['language']]['NEW_EVENTS'] = 'New events';
$memht_lang[$config_sys['language']]['DOCUMENTS'] = 'Documents';
$memht_lang[$config_sys['language']]['WARNING'] = 'Warning';
$memht_lang[$config_sys['language']]['WARNINGPOTPROB'] = 'Changing these options could lead to potential stability or security issues';
$memht_lang[$config_sys['language']]['CONTINUE'] = 'Continue';
$memht_lang[$config_sys['language']]['LEAVEPAGE'] = 'Leave page';
$memht_lang[$config_sys['language']]['QUICKNOTE'] = 'Quick note';
$memht_lang[$config_sys['language']]['LAST_MAINTENANCE'] = 'Last maintenance';

//Advertising
$memht_lang[$config_sys['language']]['BANNER'] = 'Banner';
$memht_lang[$config_sys['language']]['PLACEHOLDER_WILL_BE_PLACED'] = 'A placeholder will be placed';
$memht_lang[$config_sys['language']]['SHOW_ON_HOMEPAGE'] = 'Show on Homepage';

//Articles
$memht_lang[$config_sys['language']]['MANAGE_ARTICLES'] = 'Manage articles';
$memht_lang[$config_sys['language']]['MANAGE_SECTIONS'] = 'Manage sections';
$memht_lang[$config_sys['language']]['SECTION'] = 'Section';
$memht_lang[$config_sys['language']]['SECTIONS'] = 'Sections';
$memht_lang[$config_sys['language']]['PARENT_X'] = 'Parent %s';

//Blocks
$memht_lang[$config_sys['language']]['BLOCK'] = 'Block';
$memht_lang[$config_sys['language']]['BLOCKS'] = 'Blocks';
$memht_lang[$config_sys['language']]['STATIC_BLOCK'] = 'Static block';
$memht_lang[$config_sys['language']]['STATIC_BLOCKS'] = 'Static blocks';
$memht_lang[$config_sys['language']]['FILE'] = 'File';
$memht_lang[$config_sys['language']]['PREVIEW'] = 'Preview';
$memht_lang[$config_sys['language']]['PREVIEW_THIS_X'] = 'Preview this %s';
$memht_lang[$config_sys['language']]['STICKER_LABEL'] = 'Sticker label';
$memht_lang[$config_sys['language']]['EVERYWHERE'] = 'Everywhere';
$memht_lang[$config_sys['language']]['SHOW_IN_CONTENT'] = 'Show in content';
$memht_lang[$config_sys['language']]['DISPLAY'] = 'Display';

//Blog
$memht_lang[$config_sys['language']]['MANAGE_BLOG_POSTS'] = 'Manage blog posts';
$memht_lang[$config_sys['language']]['MANAGE_CATEGORIES'] = 'Manage categories';

//Configuration
$memht_lang[$config_sys['language']]['SAVE_CONFIGURATION'] = 'Save configuration';
$memht_lang[$config_sys['language']]['GENERAL'] = 'General';
$memht_lang[$config_sys['language']]['MAINTENANCE'] = 'Maintenance';
$memht_lang[$config_sys['language']]['MAINTENANCE_MODE'] = 'Maintenance mode';
$memht_lang[$config_sys['language']]['ADDITIONAL'] = 'Additional';
$memht_lang[$config_sys['language']]['CONFIGURATION'] = 'Configuration';
$memht_lang[$config_sys['language']]['X_SAVED'] = '%s saved';
$memht_lang[$config_sys['language']]['X_NOT_SAVED'] = '%s not saved';
$memht_lang[$config_sys['language']]['SEO_URLS'] = 'SEO Links';
$memht_lang[$config_sys['language']]['SEO_URLS_SEPARATOR'] = 'SEO Links separator';
$memht_lang[$config_sys['language']]['SEO_URLS_INFO'] = 'Do not use special characters like #, -, ?, &, your URL\'s might not work!';
$memht_lang[$config_sys['language']]['SITE_NAME'] = 'Site name';
$memht_lang[$config_sys['language']]['SITE_ADDRESS'] = 'Site address';
$memht_lang[$config_sys['language']]['SERVICE_EMAIL'] = 'Service email';
$memht_lang[$config_sys['language']]['SERVER_TIMEZONE'] = 'Server timezone';
$memht_lang[$config_sys['language']]['URL_NODE'] = 'Address node';
$memht_lang[$config_sys['language']]['URL_NODE_INFO'] = 'Do not change the default value (cont) when the SEO Links option is enabled!';
$memht_lang[$config_sys['language']]['CAPTCHA'] = 'Captcha';
$memht_lang[$config_sys['language']]['HOMEPAGE'] = 'Homepage';
$memht_lang[$config_sys['language']]['OUTPUT_COMPRESSION'] = 'Output compression';
$memht_lang[$config_sys['language']]['COPYRIGHT'] = 'Copyright';
$memht_lang[$config_sys['language']]['BREADCRUMBS_SEPARATOR'] = 'Breadcrumbs separator';
$memht_lang[$config_sys['language']]['SITE_TITLE_SEPARATOR'] = 'Site title separator';
$memht_lang[$config_sys['language']]['SITE_TITLE_ORDER'] = 'Site title order';
$memht_lang[$config_sys['language']]['CONTACT_TYPE'] = 'Contact type';
$memht_lang[$config_sys['language']]['ASCENDANT'] = 'Ascendant';
$memht_lang[$config_sys['language']]['DESCENDANT'] = 'Descendant';
$memht_lang[$config_sys['language']]['NOTIFICATION'] = 'Notification';
$memht_lang[$config_sys['language']]['WYSIWYG_EDITOR'] = 'WYSIWYG Editor';
$memht_lang[$config_sys['language']]['COOKIE_EXPIRATION'] = 'Login cookie life';
$memht_lang[$config_sys['language']]['CAPTCHA_FOR_USERS'] = 'Captcha for users';
$memht_lang[$config_sys['language']]['USER_SIGNUP'] = 'User registrations';
$memht_lang[$config_sys['language']]['USER_SIGNUP_MOD'] = 'Moderate user registrations';
$memht_lang[$config_sys['language']]['USER_SIGNUP_INVITE'] = 'User registrations by invitation only';
$memht_lang[$config_sys['language']]['USER_SIGNUP_CONFIRM'] = 'Confirm user registrations by email';
$memht_lang[$config_sys['language']]['EMAIL_MAILER'] = 'Mailer engine';
$memht_lang[$config_sys['language']]['SMTP_HOST'] = 'SMTP Host';
$memht_lang[$config_sys['language']]['SMTP_USER'] = 'SMTP Username';
$memht_lang[$config_sys['language']]['SMTP_PASS'] = 'SMTP Password';
$memht_lang[$config_sys['language']]['SMTP_USESSL'] = 'Use SMTP SSL connection';
$memht_lang[$config_sys['language']]['SMTP_PORT'] = 'SMTP Port';
$memht_lang[$config_sys['language']]['CHARSET'] = 'Charset';
$memht_lang[$config_sys['language']]['TYPE'] = 'Type';
$memht_lang[$config_sys['language']]['MAINTENANCE_MSG'] = 'Maintenance mode message';
$memht_lang[$config_sys['language']]['INT_MAINTENANCE_PAUSE'] = 'Core maintenance pause';
$memht_lang[$config_sys['language']]['CRONJOBS'] = 'Use cronjobs';
$memht_lang[$config_sys['language']]['SITE_CUSTOM_HEADER'] = 'Site\'s custom header';
$memht_lang[$config_sys['language']]['SITE_CUSTOM_HEADER_INFO'] = 'This field can be used to add your custom meta tags in the site\'s header. Example: Google Webmaster verification meta tag.';
$memht_lang[$config_sys['language']]['SITE_CUSTOM_FOOTER'] = 'Site\'s custom footer';
$memht_lang[$config_sys['language']]['SITE_CUSTOM_FOOTER_INFO'] = 'This field can be used to add your custom code in the site\'s footer. Example: Google Analytics tracking code.';
$memht_lang[$config_sys['language']]['MANAGE_LANGUAGES'] = 'Manage languages';
$memht_lang[$config_sys['language']]['LOCKED'] = 'Locked';
$memht_lang[$config_sys['language']]['LOCK_TPL_INFO'] = 'Deny users to change the template';
$memht_lang[$config_sys['language']]['WHITELIST_IP'] = 'Whitelisted IP';
$memht_lang[$config_sys['language']]['YOUR_IP'] = 'Your IP';
$memht_lang[$config_sys['language']]['USER_OAUTH_LOGIN'] = 'OAuth login/registration';
$memht_lang[$config_sys['language']]['SOCIAL_LOGIN_INFO'] = 'Allow users to log-in and create accounts using their OAuth compatible accounts (e.g. Facebook)';
$memht_lang[$config_sys['language']]['TERMS_OF_USE'] = 'Terms of use';
$memht_lang[$config_sys['language']]['DIALOG'] = 'Dialog';
$memht_lang[$config_sys['language']]['TERMS_OF_USE_DIALOG_INFO'] = 'Displays a dialog box that prompts the visitors with the terms of use';
$memht_lang[$config_sys['language']]['NOTICE'] = 'Notice';
$memht_lang[$config_sys['language']]['TERMS_OF_USE_NOTICE_INFO'] = 'The notice shown in the displayed dialog box';
$memht_lang[$config_sys['language']]['TERMS_OF_USE_CONTROLLER_INFO'] = 'Page used to display the Terms of use of the website';

//Contact
$memht_lang[$config_sys['language']]['SIMPLE_TEXT'] = 'Simple text';

//Content
$memht_lang[$config_sys['language']]['ID'] = 'ID';
$memht_lang[$config_sys['language']]['TITLE'] = 'Title';
$memht_lang[$config_sys['language']]['STATUS'] = 'Status';
$memht_lang[$config_sys['language']]['AUTHOR'] = 'Author';
$memht_lang[$config_sys['language']]['LANGUAGE'] = 'Language';
$memht_lang[$config_sys['language']]['LANGUAGES'] = 'Languages';
$memht_lang[$config_sys['language']]['TEXT'] = 'Text';
$memht_lang[$config_sys['language']]['CREATE'] = 'Create';
$memht_lang[$config_sys['language']]['CREATE_NEW_X'] = 'Create a new %s';
$memht_lang[$config_sys['language']]['NEW_X'] = 'New %s';
$memht_lang[$config_sys['language']]['OLD_X'] = 'Old %s';
$memht_lang[$config_sys['language']]['SAVE_AS_DRAFT'] = 'Save as draft';
$memht_lang[$config_sys['language']]['DELETE'] = 'Delete';
$memht_lang[$config_sys['language']]['DONT_DELETE'] = 'Do not delete';
$memht_lang[$config_sys['language']]['DELETE_PERMANENTLY'] = 'Delete permanently';
$memht_lang[$config_sys['language']]['SURE_UNINSTALL_THE_X'] = 'Are you sure you want to uninstall the %s?';
$memht_lang[$config_sys['language']]['SURE_PERMANENTLY_DELETE_THE_X'] = 'Are you sure you want to permanently delete the selected %s?';
$memht_lang[$config_sys['language']]['SURE_PERMANENTLY_DELETE_THE_X_AND_CONTENT'] = 'Are you sure you want to permanently delete the selected %s and all the data within?';
$memht_lang[$config_sys['language']]['SURE_RESTORE_THE_X'] = 'Are you sure you want to restore the %s?';
$memht_lang[$config_sys['language']]['SURE_ACTIVATE_THE_X'] = 'Are you sure you want to activate the %s?';
$memht_lang[$config_sys['language']]['SELECT_ALL'] = 'Select All';
$memht_lang[$config_sys['language']]['SELECT_NONE'] = 'Select None';
$memht_lang[$config_sys['language']]['TRASH_CAN'] = 'Trash Can';
$memht_lang[$config_sys['language']]['SEND_TO_TRASH'] = 'Send to trash';
$memht_lang[$config_sys['language']]['RESTORE'] = 'Restore';
$memht_lang[$config_sys['language']]['FILTER'] = 'Filter';
$memht_lang[$config_sys['language']]['SEARCH'] = 'Search';
$memht_lang[$config_sys['language']]['MUST_SELECT_AT_LEAST_ONE_X'] = 'You must select at least one %s';
$memht_lang[$config_sys['language']]['MUST_SELECT_ONE_X'] = 'You must select one %s';
$memht_lang[$config_sys['language']]['SHOW_X_CREATED_BY_Y'] = 'Show %s created by %s';
$memht_lang[$config_sys['language']]['SHOW_X_IN_Y'] = 'Show %s in %s';
$memht_lang[$config_sys['language']]['VIEW_THIS_X'] = 'View this %s';
$memht_lang[$config_sys['language']]['CHANGE_THE_STATUS_OF_THIS_X'] = 'Change the status of this %s';
$memht_lang[$config_sys['language']]['NUM_LOWCASE_LATIN_CHARS_DASH_ONLY'] = 'Numbers, lowercase latin chars and dashes only [a-z0-9-]';
$memht_lang[$config_sys['language']]['4CHARSMIN_LETTERS_NUM_ONLY'] = 'At least 4 characters, letters and numbers only';
$memht_lang[$config_sys['language']]['LETTERS_NUM_SPECIAL_ACCEPT'] = 'Letters, numbers and special characters are accepted';
$memht_lang[$config_sys['language']]['START'] = 'Start';
$memht_lang[$config_sys['language']]['END'] = 'End';
$memht_lang[$config_sys['language']]['LINK_NAME'] = 'Link name';
$memht_lang[$config_sys['language']]['META'] = 'Meta';
$memht_lang[$config_sys['language']]['DESCRIPTION'] = 'Description';
$memht_lang[$config_sys['language']]['KEYWORDS'] = 'Keywords';
$memht_lang[$config_sys['language']]['VALUES_SEPARATED_BY_COMMAS'] = 'Values separated by commas';
$memht_lang[$config_sys['language']]['PREVIOUS_VERSIONS'] = 'Previous versions';
$memht_lang[$config_sys['language']]['CURRENT_VERSION'] = 'Current version';
$memht_lang[$config_sys['language']]['COMPARE'] = 'Compare';
$memht_lang[$config_sys['language']]['VIEW'] = 'View';
$memht_lang[$config_sys['language']]['TRY_AGAIN'] = 'Try again';

//Dashboard
$memht_lang[$config_sys['language']]['QUICK_LINKS'] = 'Quick links';
$memht_lang[$config_sys['language']]['SHOW_IN_QUICK_LINKS'] = 'Show in quick links';
$memht_lang[$config_sys['language']]['NEWS_FROM_MEMHTCOM'] = 'News from MemHT Portal';
$memht_lang[$config_sys['language']]['SYSTEM_LOG'] = 'System log';
$memht_lang[$config_sys['language']]['VERSION'] = 'Version';
$memht_lang[$config_sys['language']]['OS'] = 'Operating system';
$memht_lang[$config_sys['language']]['SERVER_NAME'] = 'Server name';
$memht_lang[$config_sys['language']]['DISK_FREE_SPACE'] = 'Disk free space';
$memht_lang[$config_sys['language']]['SYSTEM'] = 'System';
$memht_lang[$config_sys['language']]['MEMORY_LIMIT'] = 'Memory limit';
$memht_lang[$config_sys['language']]['UPLOAD_MAX_FILESIZE'] = 'Upload max. filesize';
$memht_lang[$config_sys['language']]['REGISTER_GLOBALS'] = 'Register globals';
$memht_lang[$config_sys['language']]['AVAILABLE'] = 'Available';
$memht_lang[$config_sys['language']]['UNAVAILABLE'] = 'Unavailable';
$memht_lang[$config_sys['language']]['GD_GRAPH_LIB'] = 'GD Graphics Library';
$memht_lang[$config_sys['language']]['SUPPORTED_TYPES'] = 'Supported types';

//Downloads
$memht_lang[$config_sys['language']]['DEMO_LINK'] = 'Demo link';
$memht_lang[$config_sys['language']]['VAL_IN_BYTES'] = 'Value in bytes';
$memht_lang[$config_sys['language']]['EXTERNAL'] = 'External';
$memht_lang[$config_sys['language']]['END_USER_LICENSE'] = 'End User License';

//Events
$memht_lang[$config_sys['language']]['CONTACT_MESSAGES'] = 'Contact messages';

//File manager
$memht_lang[$config_sys['language']]['NO_FILE_SELECTED'] = 'No file selected';
$memht_lang[$config_sys['language']]['ACCEPTED_FILE_TYPES_X'] = 'Accepted file types: %s';
$memht_lang[$config_sys['language']]['MAX_FILESIZE_X'] = 'Maximum file size: %s';
$memht_lang[$config_sys['language']]['FILES'] = 'Files';
$memht_lang[$config_sys['language']]['SIZE'] = 'Size';
$memht_lang[$config_sys['language']]['UPLOADED'] = 'Uploaded';
$memht_lang[$config_sys['language']]['BROWSE_X'] = 'Browse %s';

//Internal
$memht_lang[$config_sys['language']]['EVENTS'] = 'Events';
$memht_lang[$config_sys['language']]['EVENT'] = 'Event';
$memht_lang[$config_sys['language']]['ERRORS'] = 'Errors';
$memht_lang[$config_sys['language']]['IP'] = 'IP';

//Links
$memht_lang[$config_sys['language']]['MANAGE_LINKS'] = 'Manage Links';

//Menu
$memht_lang[$config_sys['language']]['BLOG'] = 'Blog';
$memht_lang[$config_sys['language']]['DOWNLOADS'] = 'Downloads';
$memht_lang[$config_sys['language']]['FILES_MANAGER'] = 'Files manager';
$memht_lang[$config_sys['language']]['SECURITY'] = 'Security';
$memht_lang[$config_sys['language']]['STATISTICS'] = 'Statistics';
$memht_lang[$config_sys['language']]['USERS'] = 'Users';

//Plugins
$memht_lang[$config_sys['language']]['INSTALL_NEW_X'] = 'Install a new %s';
$memht_lang[$config_sys['language']]['INSTALLED_X'] = 'Installed %s';
$memht_lang[$config_sys['language']]['PLUGIN'] = 'Plugin';
$memht_lang[$config_sys['language']]['CONTROLLER'] = 'Controller';
$memht_lang[$config_sys['language']]['PLUGINS'] = 'Plugins';
$memht_lang[$config_sys['language']]['INSTALL'] = 'Install';
$memht_lang[$config_sys['language']]['UNINSTALL'] = 'Uninstall';
$memht_lang[$config_sys['language']]['SHOW_TITLE'] = 'Show title';
$memht_lang[$config_sys['language']]['CONT_BEFORE'] = 'Content before';
$memht_lang[$config_sys['language']]['CONT_AFTER'] = 'Content after';
$memht_lang[$config_sys['language']]['SHOW_IN_SITEMAP'] = 'Show in sitemap';
$memht_lang[$config_sys['language']]['ADMINCP'] = 'Administration Control Panel';
$memht_lang[$config_sys['language']]['ADD_IN_SITE_MENU'] = 'Add in site\'s menu';
$memht_lang[$config_sys['language']]['HEADER'] = 'Header';
$memht_lang[$config_sys['language']]['LAYOUT'] = 'Layout';
$memht_lang[$config_sys['language']]['SHOW'] = 'Show';
$memht_lang[$config_sys['language']]['HIDE'] = 'Hide';
$memht_lang[$config_sys['language']]['EXTRA'] = 'Extra';
$memht_lang[$config_sys['language']]['LEFT_COLUMN'] = 'Left column';
$memht_lang[$config_sys['language']]['RIGHT_COLUMN'] = 'Right column';
$memht_lang[$config_sys['language']]['INTERNAL_PAGES'] = 'Internal pages';
$memht_lang[$config_sys['language']]['INTERNAL'] = 'Internal';
$memht_lang[$config_sys['language']]['MENU_EDITOR'] = 'Menu editor';
$memht_lang[$config_sys['language']]['ADMIN_MENU_EDITOR'] = 'AdminCP menu editor';
$memht_lang[$config_sys['language']]['POSITION'] = 'Position';
$memht_lang[$config_sys['language']]['LINK'] = 'Link';
$memht_lang[$config_sys['language']]['LINKS'] = 'Links';
$memht_lang[$config_sys['language']]['HEAD'] = 'Head';
$memht_lang[$config_sys['language']]['NAV'] = 'Nav';
$memht_lang[$config_sys['language']]['OPEN_THIS_X'] = 'Open this %s';
$memht_lang[$config_sys['language']]['OPEN'] = 'Open';
$memht_lang[$config_sys['language']]['RESET_POSITIONS'] = 'Reset positions';
$memht_lang[$config_sys['language']]['USE_NODE_PLACEHOLDER'] = 'Use the {NODE} placeholder in local addresses.<br />Example: index.php?{NODE}=abc';
$memht_lang[$config_sys['language']]['ZONE'] = 'Zone';
$memht_lang[$config_sys['language']]['LEAVE_BLANK_DEFAULT_VAL'] = 'Leave blank for default value';
$memht_lang[$config_sys['language']]['RSS_FEEDS'] = 'RSS Feeds';
$memht_lang[$config_sys['language']]['OPTION'] = 'Option';
$memht_lang[$config_sys['language']]['VALUE'] = 'Value';
$memht_lang[$config_sys['language']]['MANAGE_REDIRECTS'] = 'Manage redirections';
$memht_lang[$config_sys['language']]['REDIRECTION'] = 'Redirection';
$memht_lang[$config_sys['language']]['REDIRECTIONS'] = 'Redirections';
$memht_lang[$config_sys['language']]['KEY'] = 'Key';
$memht_lang[$config_sys['language']]['INSTALLED'] = 'Installed';
$memht_lang[$config_sys['language']]['UNINSTALLED'] = 'Uninstalled';

//Users
$memht_lang[$config_sys['language']]['USERS_WAIT_ACT'] = 'Users waiting for activation';
$memht_lang[$config_sys['language']]['REG_DATE'] = 'Registration date';
$memht_lang[$config_sys['language']]['LAST_LOGIN'] = 'Last login';
$memht_lang[$config_sys['language']]['ACTIVATE'] = 'Activate';
$memht_lang[$config_sys['language']]['NEVER'] = 'Never';
$memht_lang[$config_sys['language']]['INFO'] = 'Info';
$memht_lang[$config_sys['language']]['MORE_INFO_ABOUT_THIS_X'] = 'More information about this %s';
$memht_lang[$config_sys['language']]['FIND'] = 'Find';
$memht_lang[$config_sys['language']]['FIND_X'] = 'Find %s';
$memht_lang[$config_sys['language']]['EXACT_MATCH'] = 'Exact match';
$memht_lang[$config_sys['language']]['USERS_LIST'] = 'Users list';
$memht_lang[$config_sys['language']]['CUSTOM_PROFILE_FIELDS'] = 'Custom profile fields';
$memht_lang[$config_sys['language']]['PROHIBITED_X'] = 'Prohibited %s';
$memht_lang[$config_sys['language']]['EMAIL_DOMAINS'] = 'Email domains';
$memht_lang[$config_sys['language']]['USER_ID'] = 'User ID';
$memht_lang[$config_sys['language']]['ACCOUNT_CREATED'] = 'Account created';
$memht_lang[$config_sys['language']]['ACCOUNT_MODIFIED'] = 'Account modified';
$memht_lang[$config_sys['language']]['FIELD'] = 'Field';
$memht_lang[$config_sys['language']]['TEXTAREA'] = 'Text area';
$memht_lang[$config_sys['language']]['CHARACTER'] = 'Character';
$memht_lang[$config_sys['language']]['CHARACTERS'] = 'Characters';
$memht_lang[$config_sys['language']]['MANAGE_CHARACTERS'] = 'Manage Characters';
$memht_lang[$config_sys['language']]['PATTERN'] = 'Pattern';
$memht_lang[$config_sys['language']]['REPLACE'] = 'Replace';
$memht_lang[$config_sys['language']]['MANAGE_INVITATIONS'] = 'Manage invitation codes';
$memht_lang[$config_sys['language']]['INVITATION'] = 'Invitation';
$memht_lang[$config_sys['language']]['INVITATIONS'] = 'Invitations';
$memht_lang[$config_sys['language']]['NUM_OF_INVITES'] = 'Number of invites';
$memht_lang[$config_sys['language']]['EXPIRATION_DATE'] = 'Expiration date';
$memht_lang[$config_sys['language']]['MANAGE_ROLES'] = 'Manage roles';
$memht_lang[$config_sys['language']]['ROLE'] = 'Role';
$memht_lang[$config_sys['language']]['COLOR'] = 'Color';
$memht_lang[$config_sys['language']]['STYLE'] = 'Style';
$memht_lang[$config_sys['language']]['BOLD'] = 'Bold';
$memht_lang[$config_sys['language']]['ITALIC'] = 'Italic';

//Security
$memht_lang[$config_sys['language']]['HOST'] = 'Host';
$memht_lang[$config_sys['language']]['BAN_X'] = 'Ban %s';
$memht_lang[$config_sys['language']]['BANIP_RANGE_INFO'] = 'The second field is used for ip-range banishment. Example: 98.100.200.1 - 98.100.200.23';
$memht_lang[$config_sys['language']]['WRITE_USER_NAME_TO_FIND'] = 'Write the user\'s login name and press the find button to look for the User ID';
$memht_lang[$config_sys['language']]['BAN_EXPIRES'] = 'Ban expires';
$memht_lang[$config_sys['language']]['REASON'] = 'Reason';
$memht_lang[$config_sys['language']]['AUTOBANLOG'] = 'Automatic banishment log';
$memht_lang[$config_sys['language']]['BANNED_VISITORS'] = 'Banned visitors';
$memht_lang[$config_sys['language']]['UNBAN'] = 'Unban';
$memht_lang[$config_sys['language']]['PERMANENT'] = 'Permanent';
$memht_lang[$config_sys['language']]['SURE_UNBAN_IP'] = 'Are you sure you want to unban this ip?';

//Statistics
$memht_lang[$config_sys['language']]['VISITORS'] = 'Visitors';
$memht_lang[$config_sys['language']]['PAGEVIEWS'] = 'Pageviews';
$memht_lang[$config_sys['language']]['HITS'] = 'Hits';
$memht_lang[$config_sys['language']]['LAST_WEEK'] = 'Last week';

//Messages
$memht_lang[$config_sys['language']]['NEW_MSG'] = 'You have new messages';
$memht_lang[$config_sys['language']]['DISMISS'] = 'Dismiss';
$memht_lang[$config_sys['language']]['READ_MSG'] = 'Read messages';
$memht_lang[$config_sys['language']]['REDIRECT'] = 'Redirecting';
$memht_lang[$config_sys['language']]['MESSAGE'] = 'Message';
$memht_lang[$config_sys['language']]['NOTHING_HERE'] = 'There\'s nothing here';
$memht_lang[$config_sys['language']]['NEW_MSG'] = 'New message';
$memht_lang[$config_sys['language']]['SEND_MSG'] = 'Send a message';
$memht_lang[$config_sys['language']]['REPLY'] = 'Reply';

?>