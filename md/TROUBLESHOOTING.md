# Troubleshooting: Changes Not Showing After Refresh

## Problem

Changes made to PHP/HTML/CSS/JS files are not reflecting in the web browser even after refreshing the page.

## Common Causes & Solutions

### 1. Browser Cache

**Cause**: Browsers cache static files to improve performance, so changes aren't loaded immediately.

**Solutions**:

- **Hard Refresh**: Press `Ctrl + Shift + R` (Windows/Linux) or `Cmd + Shift + R` (Mac) in your browser
- **Force Refresh**: Press `Ctrl + F5` (Windows/Linux) or `Cmd + Shift + R` (Mac)
- **Disable Cache Temporarily**:
  - Open Developer Tools (F12)
  - Go to Network tab
  - Check "Disable cache" checkbox
  - Refresh the page

### 2. PHP OPcache

**Cause**: PHP's OPcache stores compiled bytecode, preventing changes from taking effect.

**Solutions**:

- **Restart Apache**: In XAMPP Control Panel, stop and start Apache
- **Disable OPcache for Development**:
  - Open `php.ini` (usually `C:\xampp\php\php.ini`)
  - Find the `[opcache]` section
  - Set `opcache.enable=0` for development
  - Restart Apache

### 3. File Not Saved Properly

**Cause**: Changes might not be saved or saved in wrong location.

**Solutions**:

- Ensure files are saved in `C:\xampp\htdocs\smarthostel\`
- Check file timestamps in VS Code
- Use "Save All" (Ctrl + K, S) to ensure all changes are saved

### 4. Wrong File Being Edited

**Cause**: Editing a different file than what's being served.

**Solutions**:

- Verify you're editing files in `C:\xampp\htdocs\smarthostel\`
- Check if there are multiple copies of the same file
- Use browser's "View Page Source" to see what's actually being served

### 5. Server Not Running

**Cause**: Apache/MySQL not started in XAMPP.

**Solutions**:

- Open XAMPP Control Panel
- Start Apache and MySQL modules
- Access via `http://localhost/smarthostel/`

### 6. Firewall/Antivirus Blocking

**Cause**: Security software might be interfering.

**Solutions**:

- Temporarily disable antivirus/firewall
- Add exceptions for XAMPP ports (80, 443)

## Quick Fix Checklist

1. Save all files in VS Code
2. Hard refresh browser (`Ctrl + Shift + R`)
3. If still not working, restart Apache in XAMPP
4. Check Developer Tools Console for errors
5. Verify file paths are correct (relative vs absolute)

## Prevention Tips

- Always hard refresh after making changes
- Use browser developer tools during development
- Consider disabling OPcache during active development
- Use version control (Git) to track changes

## If Problem Persists

- Clear browser history and cache completely
- Try a different browser (Chrome, Firefox, Edge)
- Check XAMPP logs for errors
- Verify PHP configuration
