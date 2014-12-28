#### 5.0.1.0 Stable
*in chronological order from most recent to oldest*

- [New] 5.0.0.9 RC to 5.0.1.0 updater (yes, almost there)
- [Mod] Installer and updater scripts were converted to MySQLi
- [Mod] Debug-Development and Production mode (error handling, uses inc/config.inc.php file, debug option 0/1)
- [New] Terms of use dialog notice (required by recent EU regulations)
- [Mod] MobileDetect library updated to version 2.8.11
- [Mod] TinyMCE updated plus mods
- [Mod] Unused library files and folders
- [Fix] Templates compatibility with new Smarty (Paulo Ferreira)
- [New] MySQLi support (you should set it as db engine in inc/config.inc.php)
- [Mod] Updated jQuery to 1.11.1 and jQuery UI to 1.11.2 (Paulo Ferreira)
- [Mod] Updated kses to be compatible with PHP 5.5 (Paulo Ferreira)
- [Mod] Updated Smarty to 3.1.21 (Paulo Ferreira)
- [New] Optional SSL connection to the AdminCP with "use_ssl_admincp" config val
- [Fix] Minor cron & visitor_query string info fixes
- [Mod] Changed the required PHP version to 5.2.0 and added the optional but recommended mbstring extension in the installer
- [Mod] Multibyte encoding string function replaced
- [New] MB Multibyte support class for UTF-8 strings
- [Fix] Minor template fixes
- [Mod] Few info labels in site configuration
- [New] Mobile device detection in scout.inc.php (Try debugging the engine variable $Visitor), class by Serban Ghita
- [Fix] Facebook social login. Engine options changed to "social_signin".
- [New] More extension hooks installed
- [Fix] Multiple minor xml fixes, thanks Paulo
- [Fix] Minor corrections
- [Fix] BBCode editor
- [Mod] Removing duplicated files, not belonging to the RC version
