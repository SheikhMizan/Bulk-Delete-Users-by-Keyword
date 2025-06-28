=== Bulk Delete Users by Keyword ===
Contributors: sheikhmizanbd
Tags: bulk delete, delete users, keyword-based deletion, user management, admin tools
Requires at least: 5.5
Tested up to: 6.8
Stable tag: 2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Efficiently manage your WordPress users with keyword-based bulk deletion capabilities.

== Description ==

The **Bulk Delete Users by Keyword** plugin provides administrators with a powerful tool for cleaning up user databases by allowing bulk deletion based on specific keywords. Perfect for removing spam accounts, inactive users, or performing database maintenance.

**Key Features:**
- Advanced keyword filtering across multiple user fields (username, email, display name)
- Batch processing for handling large user databases efficiently
- Real-time progress tracking during deletion operations
- Comprehensive safety warnings and confirmations
- Customizable batch sizes for optimal performance

**Enhanced Functionality in Version 2.0:**
- AJAX-powered processing for smooth operation
- Detailed progress reporting
- Support for multiple search fields
- Improved user interface
- Better error handling and notifications

== Features ==

= Core Functionality =
- Keyword-based user filtering and deletion
- Batch processing for large datasets
- Multi-field search (username, email, display name, nickname)
- Progress tracking during operations

= Safety Features =
- Explicit warning messages
- Confirmation dialogs
- Nonce verification for all operations
- Capability checks

= Performance =
- Optimized database queries
- Configurable batch sizes
- Memory-efficient processing

== Installation ==

= Automatic Installation =
1. Navigate to "Plugins > Add New" in your WordPress admin
2. Search for "Bulk Delete Users by Keyword"
3. Click "Install Now" and then "Activate"

= Manual Installation =
1. Download the plugin ZIP file
2. Go to "Plugins > Add New > Upload Plugin" in WordPress
3. Upload the ZIP file
4. Click "Install Now" and then "Activate"

= After Installation =
1. Navigate to "Bulk Delete Users" in the admin menu
2. Configure your search criteria
3. Execute the deletion process

== Frequently Asked Questions ==

= How does the keyword matching work? =
The plugin searches for your keyword in multiple user fields: username, email, display name, and nickname. Partial matches are included.

= Can I recover deleted users? =
No, this is a permanent deletion. Always maintain regular backups of your database.

= What's the recommended batch size? =
For most sites, 100-500 users per batch works well. Larger sites may benefit from smaller batches (50-100).

= Does this work with multisite installations? =
Yes, but users will only be deleted from the current site in the network.

== Screenshots ==
1. Main plugin interface showing keyword input and options
2. Progress tracking during deletion
3. Confirmation dialog
4. Success/error notifications

== Changelog ==

= 2.0 =
- Complete rewrite with AJAX processing
- Added progress tracking
- Enhanced search capabilities
- Improved user interface
- Better error handling

= 1.0 =
- Initial release with basic functionality

== Upgrade Notice ==

= 2.0 =
Major update with significant improvements. Recommended for all users.

= 1.0 =
Initial release with basic keyword-based deletion.

== License ==
GNU General Public License v2.0 or later

== Privacy Notice ==
This plugin does not collect any user data or transmit information to external servers. All operations occur entirely within your WordPress installation.