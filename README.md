# Bulk Delete Users by Keyword

**Author:** [Sheikh Mizan](https://github.com/sheikhmizanbd)  
**License:** [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)  
**Tags:** bulk delete, user management, keyword-based deletion, WordPress admin tools  
**Stable Version:** 2.0  
**Tested Up To:** WordPress 6.8  
**Requires At Least:** WordPress 5.5  

> Efficiently manage your WordPress users with keyword-based bulk deletion capabilities.

---

## Description

The **Bulk Delete Users by Keyword** plugin enables administrators to clean up their WordPress user base by deleting users based on specific keywords. Ideal for removing spam, inactive, or unnecessary accounts in bulk.

### ✅ Key Features

- 🔍 **Keyword Filtering**: Search by username, email, display name, or nickname.
- 🗑 **Bulk Deletion**: Delete users in batches to avoid performance issues.
- 🚀 **AJAX Processing**: Smooth real-time feedback during deletion.
- 📊 **Progress Tracking**: Monitor deletion operations live.
- ⚠️ **Safety Features**: Confirmation dialogs, nonce verification, and capability checks.
- ⚙️ **Custom Batch Size**: Optimize performance on larger sites.

---

## Installation

### 🔁 Automatic Installation
1. Go to `Plugins > Add New` in your WordPress admin panel.
2. Search for **Bulk Delete Users by Keyword**.
3. Click **Install Now**, then **Activate**.

### 📦 Manual Installation
1. Download the plugin as a ZIP file.
2. In your WordPress admin panel, go to `Plugins > Add New > Upload Plugin`.
3. Upload the ZIP file and click **Install Now**.
4. Click **Activate**.

---

## Usage

1. Navigate to **Bulk Delete Users** in the WordPress admin menu.
2. Enter the keyword(s) to filter users.
3. Select user fields to search in (e.g., email, username).
4. Click **Delete** to begin the batch process.

---

## FAQ

**Q: How does keyword matching work?**  
A: The plugin searches for partial matches in multiple fields: username, email, display name, and nickname.

**Q: Can deleted users be recovered?**  
A: No. Deletions are permanent. Backup your database before running the plugin.

**Q: What batch size should I use?**  
A: Recommended batch size is 100–500. For large databases, use smaller batches (50–100).

**Q: Is it compatible with WordPress Multisite?**  
A: Yes, but users are deleted **only from the current site** within the network.

---

## Screenshots

1. Main plugin interface with keyword input and search options.
2. Real-time progress tracking.
3. Confirmation dialog before deletion.
4. Success and error messages.

---

## Changelog

### v2.0
- AJAX-powered batch deletion
- Added live progress tracking
- Enhanced UI/UX
- Improved error handling

### v1.0
- Basic keyword-based deletion feature

---

## License

This plugin is licensed under the [GNU General Public License v2.0 or later](https://www.gnu.org/licenses/gpl-2.0.html).

---

## Privacy Statement

This plugin does **not** collect, store, or share any user data. All operations are performed locally within your WordPress site.
