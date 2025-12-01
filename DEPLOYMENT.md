# NeuroBlock - Deployment Guide

## 📦 Complete File Structure

```
neuroblock/
│
├── neuroblock.php                          # Main plugin file
├── README.md                               # Plugin documentation
├── LICENSE                                 # GPL v2 license
├── icon.svg                                # Plugin icon
│
├── assets/
│   ├── css/
│   │   ├── neuroblock-admin.css           # Admin panel styles
│   │   ├── neuroblock-blocks.css          # Frontend block styles
│   │   └── neuroblock-blocks-editor.css   # Gutenberg editor styles
│   │
│   └── js/
│       ├── neuroblock-admin.js            # Admin panel JavaScript
│       └── neuroblock-blocks.js           # Gutenberg blocks JavaScript
│
├── includes/
│   ├── class-neuroblock-admin.php         # Admin interface handler
│   ├── class-neuroblock-api.php           # API handler
│   ├── class-neuroblock-blocks.php        # Gutenberg blocks
│   ├── class-neuroblock-security.php      # Security & encryption
│   ├── admin-page-template.php            # Admin page HTML template
│   │
│   └── elementor/
│       └── class-neuroblock-elementor-widget.php  # Elementor widget
│
└── languages/
    ├── neuroblock.pot                     # Translation template
    ├── neuroblock-fr_FR.po                # French translation
    └── neuroblock-fr_FR.mo                # Compiled French translation
```

## 🔧 Installation Steps

### 1. Prepare Files

1. **Create the directory structure** as shown above
2. **Copy all files** from the artifacts to their respective locations
3. **Generate .mo file** from .po file:
   ```bash
   msgfmt neuroblock-fr_FR.po -o neuroblock-fr_FR.mo
   ```

### 2. Local WordPress Setup

#### Using Local by Flywheel (Recommended)
1. Install [Local by Flywheel](https://localwp.com/)
2. Create a new WordPress site
3. Navigate to `wp-content/plugins/`
4. Create `neuroblock` folder
5. Copy all plugin files

#### Using XAMPP/MAMP
1. Install XAMPP or MAMP
2. Start Apache and MySQL
3. Create WordPress site in `htdocs/`
4. Navigate to `wp-content/plugins/`
5. Create `neuroblock` folder
6. Copy all plugin files

### 3. Plugin Activation

1. Go to WordPress admin: `http://localhost/your-site/wp-admin`
2. Navigate to **Plugins** → **Installed Plugins**
3. Find **NeuroBlock**
4. Click **Activate**

### 4. Configuration

1. Go to **NeuroBlock** in WordPress admin menu
2. Click on **AI Settings** tab
3. Select your AI provider:
   - OpenAI
   - DeepSeek
   - Google Gemini
   - Mistral AI
4. Enter your API key
5. Select model
6. Click **Save Settings**

## 🧪 Testing Checklist

### ✅ Basic Functionality
- [ ] Plugin activates without errors
- [ ] Admin menu appears
- [ ] All tabs are accessible
- [ ] Settings can be saved
- [ ] API key is encrypted

### ✅ AI Generation
- [ ] Block generation works
- [ ] Page generation works
- [ ] Elementor widget generation works
- [ ] Complete page creation works
- [ ] Generated pages appear in WordPress

### ✅ UI/UX
- [ ] SweetAlert notifications work
- [ ] Loading states display correctly
- [ ] Copy functionality works
- [ ] Crypto addresses can be copied
- [ ] Instructions modal appears

### ✅ Compatibility
- [ ] Gutenberg check works
- [ ] Elementor check works
- [ ] Blocks only work with Gutenberg installed
- [ ] Elementor features only work with Elementor installed

### ✅ Translations
- [ ] French translation loads correctly
- [ ] All strings are translatable
- [ ] Language switches based on WordPress locale

## 🐛 Common Issues & Solutions

### Issue: Plugin won't activate
**Solution:** Check PHP version (must be 7.4+) and ensure OpenSSL extension is enabled

### Issue: API calls fail
**Solution:** 
- Verify API key is correct
- Check internet connection
- Ensure provider endpoint is accessible
- Check WordPress error logs

### Issue: SweetAlert doesn't appear
**Solution:**
- Clear browser cache
- Check browser console for JavaScript errors
- Ensure jQuery is loaded

### Issue: Translations don't work
**Solution:**
- Regenerate .mo file from .po
- Check file permissions
- Verify locale matches WordPress setting

### Issue: Elementor widget doesn't appear
**Solution:**
- Ensure Elementor is installed and activated
- Clear Elementor cache
- Regenerate Elementor CSS

## 📝 API Key Setup

### OpenAI
1. Go to https://platform.openai.com
2. Create account / Login
3. Navigate to **API Keys**
4. Click **Create new secret key**
5. Copy key (starts with `sk-`)

### DeepSeek
1. Go to https://platform.deepseek.com
2. Register / Login
3. Navigate to API section
4. Generate new key
5. Copy key

### Google Gemini
1. Go to https://makersuite.google.com
2. Sign in with Google account
3. Get API key
4. Copy key

### Mistral AI
1. Go to https://console.mistral.ai
2. Create account
3. Navigate to API keys
4. Create new key
5. Copy key

## 🔐 Security Notes

- API keys are encrypted using WordPress salts
- Keys are stored in wp_options table (encrypted)
- AJAX requests use nonces for security
- All inputs are sanitized
- Outputs are escaped

## 🚀 Performance Tips

1. **Use appropriate models**:
   - For simple blocks: Use smaller models (gpt-3.5-turbo, mistral-small)
   - For complex pages: Use larger models (gpt-4, mistral-large)

2. **Optimize generation**:
   - Be specific in prompts
   - Use style presets
   - Test with smaller requests first

3. **Cache considerations**:
   - Generated pages are saved to WordPress
   - No need to regenerate unless editing

## 📊 Testing Scenarios

### Scenario 1: Generate Simple Block
1. Go to Generator tab
2. Enter: "Create a pricing card with title, price, features list, and button"
3. Select: Gutenberg Block, Modern style
4. Click Generate
5. Verify: Code appears, copy button works, instructions show

### Scenario 2: Generate Complete Page
1. Go to Generator tab
2. Enter: "Create a landing page for a SaaS product with hero, features, pricing, testimonials, and CTA"
3. Select: Complete Gutenberg Page, Professional style
4. Click Generate
5. Verify: Page is created in WordPress, can be viewed

### Scenario 3: Elementor Widget
1. Ensure Elementor is installed
2. Go to Generator
3. Enter: "Create a testimonial card with avatar, name, role, and quote"
4. Select: Elementor Widget, Elegant style
5. Click Generate
6. Verify: Code is generated, compatible with Elementor

## 💡 Development Tips

### Adding New AI Provider
1. Edit `includes/class-neuroblock-api.php`
2. Add provider to `$api_providers` array
3. Create provider-specific method if needed
4. Update translations

### Customizing Styles
1. Edit `assets/css/neuroblock-admin.css`
2. Modify CSS variables at the top
3. Clear WordPress cache
4. Reload admin page

### Adding New Block Types
1. Edit `includes/class-neuroblock-admin.php`
2. Add new case in `build_prompt()` method
3. Update admin form HTML
4. Add translations

## 🎯 Production Deployment

Before deploying to production:

1. **Test thoroughly** on local environment
2. **Backup** WordPress site
3. **Upload** plugin folder via FTP or cPanel
4. **Activate** plugin
5. **Configure** API keys
6. **Test** all features
7. **Monitor** error logs

## 📞 Support

For issues or questions:
- Email: contact@starlightproagency.com
- Website: https://starlightproagency.com

## 📄 License

GPL v2 or later - See LICENSE file

---

**Happy Generating! 🚀**