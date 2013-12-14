=== Top Spam IPs ===
Contributors: F J Kaiser, toscho, sergejmueller, TJNowell, latz, senlin
Donate Link: http://unserkaiser.com/donate/
Tags: comments, spam, wcm, wecodemore, ip, prevent, block, anti, bot, botnet, security
Author URI: http://unserkaiser.com
Requires at least: 3.5
Tested up to: 3.5
Stable Tag: 1.0
License: MIT

== Description ==

This plugin shows you the top spam commentators and their IP addresses on a new
admin/tools subpage. You can filter them by setting a minimum amount of spam comments.

It also features an export area, that gives you the possibility to easily copy them to
the clipboard to block them via your <code>.htaccess</code> file.

The plugin was built after reading Thomas "toscho" Scholz blog post on WPKrauts
about [penetrant spammers](http://wpkrauts.com/2013/get-ip-addresses-from-most-penetrant-spammers/).

To catch as much spam as possible, we recommend using the
[AntiSpam Bee](http://antispambee.com/) plugin by Sergej M&uuml;ller.

This plugin is written under the WeCodeMore plugin label.
WeCodeMore (WCM) is your label for high quality WordPress code from renowned authors.

If you want to get updates, just follow us on…

 * [our page on Google+](https://plus.google.com/b/109907580576615571040/109907580576615571040/posts)
 * [our GitHub repository](https://github.com/wecodemore)

== Frequently Asked Questions ==

= What can I do with the IP addresses? =

You can block them via your <code>.htaccess</code> file. Either by single IPs,
IP range (just leave the last digits off) or a combination of both.

<pre>
# Start IP block
order allow,deny
# Block single IP example
deny from 123.45.6.7
# Block IP range example
deny from 012.34.5.
allow from all
# End IP block
</pre>

== Installation ==

1. Upload the zip file to the <code>/wp-content/plugins/</code> directory,
2. Activate the plugin through the <code>Plugins</code> menu in WordPress.

== Screenshots ==

1. The IP list screen. Per page, minimum amount and sort order configurable.
2. The export screen. The IP addresses come pre selected. Just copy them to clipboard.
3. The IP list screen with a larger set of unsorted spam IPs.

== Changelog ==

= 1.1 =

 * Bug Fix: Missing list table on admin/theme customizer page
 * Bug Fix: Non-static method called statically
 * Performance: Don't load the list table if we don't need it.

= 1.0 =

 * Initial Version