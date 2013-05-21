# Top Spam IPs

A WordPress plugin that collects the Top Spam IP addresses and lists them on a new admin
tools page. Allows to export them and to block via your `.htaccess` file.

## Contributions

This repo is widely open to _any_ sort of contribution.

Here's a list of things that need to get done:

 * A better readme file here
 * Screenshots both for the dot org txt file as well as for this file
 * Generate main translation files
 * Translation into other languages
 * Automatically add all or just a single IP to the `.htaccess`
 * Remove a single IP (or all) from the `.htaccess`
 * Clean `.htaccess` on uninstall
 * Central storage for IP addresses, similar to project honeypot
 * Remote connection to send the IPs there

## Thank you!

 Thanks in advance for any contributions. Your work will be honored with a link to your
 wordpress dot org profile in the "Contributors" section of the Readme file.

## HowTo block IP addresses

…or IP ranges via the `.htaccess` file.

```
	# Start IP block
	order allow,deny
	# Block single IP example
	deny from 123.45.6.7
	# Block IP range example
	deny from 012.34.5.
	allow from all
	# End IP block
```

## Screenshots

A short preview with too little Spam IP addresses.

![The IP list screen. Per page, minimum amount and sort order configurable.](screenshot-1.png "List Screen")
---
![The export screen. The IP addresses come pre selected. Just copy them to clipboard.](screenshot-2.png "Export Screen")
---
![The IP list screen with a larger set of unsorted spam IPs.](screenshot-3.png "List Screen")
---
