=== Friendly Captcha for WordPress ===
Contributors: friendlycaptcha
Tags: captcha, anti-spam, antispam, block spam, spam, contact form, comments, friendly-captcha, recaptcha
Requires at least: 5.0
Tested up to: 5.7
Requires PHP: 7.3
Stable tag: 1.1.1
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
3. Enter your Site Key and API Key in the Friendly Captcha menu in WordPress  
4. Enable desired Integrations
 
== Frequently Asked Questions ==

Q: You don't support a certain plugin. How can I get support for it added?
A: Open a PR on github: https://github.com/FriendlyCaptcha/friendly-captcha-wordpress or just email the authors of the plugin itself. Adding Friendly Captcha support is typically quite a quick task for most plugins.

Q: Where can I get more information about Friendly Captcha?  
A: Please see our website at: https://friendlycaptcha.com/

== Forms and Plugins Supported ==

* WordPress Login Form
* WordPress Register Form
* WordPress Lost Password Form
* Wordpress Comments
* WPForms
* Contact Form 7

If you see an integration that's missing, please open a pull request:
https://github.com/FriendlyCaptcha/friendly-captcha-wordpress

However, you may wish to email the authors of plugins you'd like to support Friendly Captcha: it will usually take them only an hour or two to add native support if they choose to do so. This will simplify your use of Friendly Captcha, and is the best solution in the long run.

== Changelog ==
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
