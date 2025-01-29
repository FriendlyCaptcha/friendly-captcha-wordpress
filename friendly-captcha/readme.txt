=== Friendly Captcha for WordPress ===
Contributors: friendlycaptcha
Tags: captcha, antispam, spam, contact form, recaptcha, friendly-captcha, block spam, anti-spam, comments, elementor
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.3
Stable tag: 1.15.12
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Friendly Captcha is a privacy-first anti-bot solution that protects WordPress website forms from spam and abuse.

== Description ==
 
Friendly Captcha is a privacy-first anti-bot solution that protects WordPress website forms from spam and abuse. 

Do you use a captcha like reCAPTCHA to keep out bots? Friendly Captcha is a powerful anti-spam service that blocks spam-bots without annoying captcha images and protects user privacy. 

== How Friendly Captcha Works ==

Friendly Captcha is a tool for preventing spam on your website. Other CAPTCHAs are a burden on your users, Friendly Captcha respects your users. 

Friendly Captcha is a proof-of-work based anti-bot solution in which the user's device does all the work. We generate a unique crypto puzzle for each visitor.

Solving it will usually take only a few seconds. As soon as the user starts filling the form it starts getting solved. By the time the user is ready to submit, the puzzle is probably already solved.

Friendly Captcha prevents spam and doesn't punish real users in doing so.

== Privacy Notices ==

= No personal information =
Our anti-bot service does not store personal data from your end users. See our Privacy Policy for End Users to learn more.

= No cookies =
Our end user solution does not use cookies. This protects your end users from being tracked and followed from site to site.

= No discrimination =
Normal CAPTCHA tasks are not easy for all humans. Our solution works without labeling tasks and is thus accessible to everyone.

= Decentralized data processing =
By default, requests are processed in the point-of-presence closest to where it is accessed by the end user. See our Privacy Policy for End Users to learn more.

Join thousands of organizations in switching to a privacy-first anti-bot solution.
 
== Installation ==
 
1. Upload `friendly-captcha` folder to the `/wp-content/plugins/` directory  
2. Activate the plugin through the 'Plugins' menu in WordPress  
3. Enter your Site Key and API Key in the Settings -> Friendly Captcha menu in WordPress  
4. Enable the desired integrations
 
== Frequently Asked Questions ==

= How to use the Friendly Captcha plugin? =

The Friendly Captcha plugin supports WordPress core and many plugins with forms automatically. 

To use Friendly Captcha, you can create an account at [www.friendlycaptcha.com](https://friendlycaptcha.com/) and create a Site Key and API Key there. It is best to copy both keys and save them in a safe place. Then go to the Friendly Captcha plugin settings page and enter the created site key and API key. Below that, select the forms you want to protect. Finally, save these settings by clicking on the "Save Changes" button at the end of the settings page.

= You don't support a certain plugin. How can I get support for it added? =
Open a PR on GitHub [here](https://github.com/FriendlyCaptcha/friendly-captcha-wordpress) or just email the authors of the plugin itself. Adding Friendly Captcha support is typically quite a quick task for most plugins.

= Where can I get more information about Friendly Captcha? =
Please see our website at: [www.friendlycaptcha.com](https://friendlycaptcha.com/)

== Forms and Plugins Supported ==

* WordPress Login Form
* WordPress Register Form
* WordPress Reset Password Form
* WordPress Comments
* CoBlocks
* Contact Form 7
* Divi Contact Forms
* Elementor Pro Forms
* FluentForm
* Gravity Forms
* Ultimate Member Login Form
* Ultimate Member Register Form
* Ultimate Member Reset Password Form
* WooCommerce Login Form
* WooCommerce Register Form
* WooCommerce Checkout Form
* WooCommerce Lost Password Form
* WPForms
* Profile Builder Login Form
* Profile Builder Register Form
* Profile Builder Reset Password Form
* Forminator
* Formidable
* Avada Form Builder

If you see an integration that's missing, please [open a pull request](https://github.com/FriendlyCaptcha/friendly-captcha-wordpress)

However, you may wish to email the authors of plugins you'd like to support Friendly Captcha: it will usually take them only an hour or two to add native support if they choose to do so. This will simplify your use of Friendly Captcha, and is the best solution in the long run.

== Changelog ==

= 1.15.12 =

* Fix bug that caused the Ultimate Member integration to verify response twice

= 1.15.10 =

* Fix form validation bug in Profile Builder register page
* Render verification error on Profile Builder register page

= 1.15.9 =

* Fix WordPress login integration when Wordfence 2FA is enabled
* Verify captcha solution before password is verified in WordPress login

= 1.15.7 =

* Fix CoBlocks integration to show an error message when the solution is invalid

= 1.15.6 =

* Update CoBlocks integration to work with the latest version of the plugin

= 1.15.5 =

* Update `friendly-captcha-sdk` to version `0.1.10`, which fixes compatibility with some old browsers (including Safari 11.x and 12.x).

= 1.15.4 =

* Log failed verification requests when WP_DEBUG is enabled

= 1.15.3 =

* Remove incorrect plugin detection for Divi integration

= 1.15.2 =

* Use polyfilled version of widgets for better old browser support for Friendly Captcha v1.
* Update `friendly-captcha-sdk` to version `0.1.8`.
* Update `friendly-challenge` to version `0.9.18` which adds support for more languages (Arabic, Korean, Hebrew, Thai).

= 1.15.1 =

* Don't call siteverify endpoint when Captcha solution is empty

= 1.15.0 =

* Only show integrations for plugins that are installed
* Verify sitekey and API key when saving configuring the plugin

= 1.14.5 =

* Use the EU endpoint for verification when it's enabled

= 1.14.4 =

* Fix captcha being rendered twice in Forminator form

= 1.14.3 =

* Fix dynamic initialization option

= 1.14.2 =

* Add support for multistep form in FluenForm

= 1.14.1 =

* Fix fatal error caused by composer not being installed

= 1.14.0 =

* Add support for Friendly Captcha v2 (preview)
* Show an admin notice when verification has failed

= 1.13.0 =

* Add support for Formidable
* Add support for Divi Contact Forms

= 1.12.3 =
* Reset widget after form submission in some integrations

= 1.12.2 =
* Support for PHP 8.1

= 1.12.1 =
* Support widgets that are dynamically added to the page (e.g. popups or multi-step forms)

= 1.11.0 =
* Add support for Forge12 Contact Form 7 Double Opt-In

= 1.10.8 =
* Fix compatibility with Ultimate Member >= 2.6.7
* Allow logged in Wordpress users to reset passwords

= 1.10.7 =
* Fix avada form builder integration to not block all submissions

= 1.10.6 =
* Fix issue in wordpress login integration that would display and error on every page load

= 1.10.5 =
* Add proper error message for edge case in integrations for wordpress login, registration, and forgot password

= 1.10.3 =
* Fix integration for wordpress login, registration, and forgot password to let users through if the plugin isn't configured properly

= 1.10.2 =
* Update to widget library version 0.9.12, which fixes the vietnamese translation and other minor things.

= 1.10.1 =
* Fix bug in Forminator integration

= 1.10.0 =
* Add support for Forminator
* Add support for Avada Form Builder
* Tested with new Wordpress version 6.2

= 1.9.0 =
* Added support for Profile Builder Login, Register, and Reset Password Forms
* Added admin notice when plugin is not properly configured

= 1.8.2 =
* Fixed WooCommerce Login Form integration
* Add support form WP User Manager
* Update to widget library version 0.9.10, which fixes rare false positives of the headless browser check and other minor things.

= 1.8.1 =
* Fix internal error becauses of wrong error_codes type
* Temporarily removed WooCommerce Login Form from the supported forms
* Fix gravity forms multi page forms

= 1.8.0 =
* Add support for HTML forms
* Fix widget not loading with Gravity Form integration
* Add setting to disable the loading of CSS styles

= 1.7.2 =
* Update to widget library version 0.9.8, which fixes rare cases in which the widget wouldn't work for users with specific browsers plugins installed.

= 1.7.1 =
* Fix Content Forms 7 form validation if javascript is deactivated

= 1.7.0 =
* Add support for Elementor Pro Forms
* Pull default widget language from wordpress
* Updated to friendly-challenge widget version 0.9.7.
* Added support for Turkish, Greek, Ukrainian, Bulgarian, Czech, Slovak, Norwegian, Finnish, Latvian, Lithuanian, Polish, Estonian, Croatian, Serbian, Hungarian, Romanian, and Chinese langauge.

= 1.6.3 =
* Fix for Ultimate Member login integration (thank you @TheZoker!)

= 1.6.2 =
* Update Friendly Captcha dashboard links

= 1.6.1 =
* Fix FluentForm integration (thank you @TheZoker!)

= 1.6.0 =
* Add support for WooCommerce (thank you @TheZoker!)
* Add support for FluentForm (thank you @TheZoker!)
* Add support for Ultimate Member (thank you @TheZoker!)
* Updated to friendly-challenge widget version 0.9.1.
* Added support for Swedish and Russian language.

= 1.5.1 =
* Fix Coblocks integration

= 1.5.0 =
* Support for Coblocks added, thank you @amenk!
* The Friendly Captcha settings menu is now under `Settings` section in the Wordpress admin dashboard.

= 1.4.1 =
* Fix captcha verification not happening for guest Wordpress users (i.e. those not logged in) in Wordpress Comments, thank you @amenk!

= 1.4.0 =
* Fix for some Contact Form 7 users.
* Upgraded to version 0.9.0 of the widget which features a multithreaded solver, which makes the captcha faster for real users.
* Added support Danish and Japanese language.

= 1.3.2 =
* Fixed support for Friendly Captcha widgets in multi-page GravityForms forms.
* Added support for Spanish and Catalan language.

= 1.3.1 =
* Fixed a bug in the WPForms integration when an error would get displayed to the end-user.

= 1.3.0 =
* Added Gravity Forms integration.
* Updated widget to version 0.8.8, which now displays errors in the selected language.
* Added support for Portuguese language.

= 1.2.0 =
* Updated widget to version 0.8.4 (which includes a small fix for IE11).
* Added support for Italian language.
* Added dark theme setting.
* Added endpoint selection menu (only relevant for Business and Enterprise).
* The plugin now talks to `api.friendlycaptcha.com` instead of `friendlycaptcha.com` (no user-facing changes).

= 1.1.1 =
* Update to widget version 0.8.0, which has improved fake-browser detection and slight styling tweaks.

= 1.1.0 =
* Added support for Wordpress Comments (both for logged in and guest users).
* Made the default error message shown to users translatable.

= 1.0.4 =
* Added localization support (English, German, French and Dutch)
* Added text-transform:none to the widget's button (so that it is no longer uppercase in some Wordpress themes).

= 1.0.3 =
* Change required PHP version and update instructions.

= 1.0.2 =
* Improvements relating to input and output validation and sanitization.

= 1.0.1 =
* Minor bugfixes

= 1.0.0 =
* Plugin Created
