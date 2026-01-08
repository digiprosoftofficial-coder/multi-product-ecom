# How to Build CSS and JavaScript Assets

## The Problem

If CSS is not working, it means the Vite assets haven't been compiled yet. The `@vite()` directive in Blade templates looks for compiled files in `public/build/`, which are created when you run the build command.

## Solution: Build the Assets

### Option 1: Production Build (Recommended for Testing)

Run this command in your terminal:

```bash
cd C:\Users\itc-101\Herd\multi-ecommerce
npm.cmd run build
```

This will:
- Compile Bootstrap CSS
- Compile JavaScript files
- Generate the manifest.json file
- Create all asset files in `public/build/assets/`

**Time:** Takes about 30-60 seconds

### Option 2: Development Mode (For Active Development)

Run this command:

```bash
cd C:\Users\itc-101\Herd\multi-ecommerce
npm.cmd run dev
```

This will:
- Start Vite development server
- Watch for file changes
- Automatically rebuild on changes
- Provide hot module replacement

**Keep this terminal open** while developing. Press `Ctrl+C` to stop.

## Verify It Worked

After running `npm.cmd run build`, check:

1. `public/build/manifest.json` exists and has content
2. `public/build/assets/` directory exists with CSS and JS files
3. Your website now has Bootstrap styles applied

## Troubleshooting

### Issue: "npm is not recognized"

**Solution:** Make sure Node.js is installed. Download from https://nodejs.org/

### Issue: "npm.cmd not found"

**Solution:** Try using `npm` directly, or use Command Prompt instead of PowerShell:
```cmd
cd C:\Users\itc-101\Herd\multi-ecommerce
npm run build
```

### Issue: "Cannot find module"

**Solution:** Run `npm.cmd install` first to install dependencies:
```bash
npm.cmd install
npm.cmd run build
```

### Issue: PowerShell Execution Policy Error

**Solution:** Use Command Prompt (cmd.exe) instead of PowerShell, or run:
```powershell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned
```

## Temporary CDN Fallback

I've added a temporary CDN fallback in the layout file that will load Bootstrap from CDN if the build files don't exist. However, **you should still build the assets** for proper functionality.

## After Building

Once assets are built:
- ✅ CSS will work properly
- ✅ JavaScript will work properly
- ✅ FontAwesome icons will display
- ✅ Bootstrap components will function

## Quick Check

To see if assets are built, check if this file exists:
```
public/build/manifest.json
```

And if this directory has files:
```
public/build/assets/
```

If both exist and have content, your assets are built correctly!

