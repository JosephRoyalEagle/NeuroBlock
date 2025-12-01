# NeuroBlock 🧠

**AI-Powered WordPress Extension for Generating Custom Blocks and Pages**

Version: 1.0.0  
Author: Papyrus - Starlight Pro Agency  
License: GPL v2 or later

---

## 📋 Description

NeuroBlock is a modern and powerful WordPress plugin that allows you to automatically generate custom pages and blocks for Gutenberg and Elementor using artificial intelligence models.

### ✨ Key Features

- 🎨 **Automatic Content Generation**: Create Gutenberg blocks and Elementor widgets in seconds
- 🔒 **100% Secure**: Your API keys are encrypted with WordPress salts
- 💰 **Completely Free**: Use your own AI API (no subscription fees)
- 🌐 **Multi-Platform**: Compatible with OpenAI, DeepSeek, Google Gemini, and Mistral AI
- 🎯 **Modern Interface**: Elegant and easy-to-use dashboard
- 🚀 **Optimized**: Lightweight and performant code
- 🌍 **Multilingual**: Available in English and French

---

## 🚀 Installation

### Method 1: Manual Installation

1. Download the plugin ZIP file
2. Go to **Plugins → Add New**
3. Click **Upload Plugin**
4. Select the ZIP file and click **Install Now**
5. Activate the plugin

### Method 2: FTP Installation

1. Unzip the ZIP file
2. Upload the `neuroblock` folder to `/wp-content/plugins/`
3. Activate the plugin from the WordPress Plugins menu

---

## ⚙️ Configuration

### 1. Get an API Key

#### OpenAI (Recommended)
1. Create an account at [platform.openai.com](https://platform.openai.com)
2. Go to **API Keys**
3. Create a new secret key
4. Copy the key (format: `sk-...`)

**Cost:** Pay-as-you-go (~$0.002 per generation)

#### DeepSeek (Budget-Friendly)
1. Create an account at [platform.deepseek.com](https://platform.deepseek.com)
2. Generate an API key
3. Copy the key

**Cost:** Very affordable

#### Google Gemini
1. Create an account at [makersuite.google.com](https://makersuite.google.com)
2. Get an API key
3. Copy the key

**Cost:** Free tier available

#### Mistral AI (European)
1. Create an account at [console.mistral.ai](https://console.mistral.ai)
2. Generate an API key
3. Copy the key

**Cost:** Competitive pricing

### 2. Configure NeuroBlock

1. Go to **NeuroBlock** in the WordPress menu
2. Select your AI provider
3. Enter your API key
4. Choose a model:
   - **OpenAI:** gpt-4 (best quality) or gpt-3.5-turbo (faster)
   - **DeepSeek:** deepseek-chat or deepseek-coder
   - **Mistral:** mistral-large (best) or mistral-small (fast)
   - **Gemini:** gemini-pro
5. Click **Save Settings**

---

## 🎯 Usage

### Generate a Gutenberg Block

1. Go to the **Generator** tab
2. Describe what you want to create
3. Select **Gutenberg Block** as type
4. Choose a style (Modern, Minimalist, etc.)
5. Click **Generate with AI**
6. HTML/CSS code is generated automatically
7. Copy-paste into a Custom HTML block

### Generate a Complete Page

1. Go to the **Generator** tab
2. Describe your page (e.g., "Landing page for a mobile app")
3. Select **Complete Page**
4. Generate and use the code

### Example Prompts

#### Hero Section
```
Create a modern hero section with a catchy title "Revolutionize Your Business",
a subtitle, and a purple CTA button. Minimalist design with gradient background.
```

#### Pricing Table
```
Create a pricing table with 3 columns (Starter, Pro, Enterprise),
including prices, feature lists, and action buttons. Professional style.
```

#### Contact Form
```
Create an elegant contact form with name, email, subject, and message fields.
Include visual validation and submit button with hover effect.
```

#### Feature Grid
```
Create a 3-column feature grid with icons, titles, and descriptions.
Features: Speed, Security, Support. Modern card design with shadows.
```

#### Complete Landing Page
```
Create a landing page for a SaaS product with:
- Hero section with product screenshot
- 3 key features with icons
- Pricing table (Free, Pro, Enterprise)
- Testimonials section
- CTA footer
```

---

## 📁 File Structure

```
neuroblock/
│
├── neuroblock.php                      # Main file
├── README.md                           # Documentation
├── LICENSE                             # GPL v2 license
├── icon.svg                            # Plugin icon
├── build.sh                            # Build script
├── verify-files.sh                     # Verification script
│
├── assets/
│   ├── css/
│   │   ├── neuroblock-admin.css       # Admin styles
│   │   ├── neuroblock-blocks.css      # Frontend block styles
│   │   └── neuroblock-blocks-editor.css # Gutenberg editor styles
│   │
│   └── js/
│       ├── neuroblock-admin.js        # Admin JavaScript
│       └── neuroblock-blocks.js       # Gutenberg blocks JavaScript
│
├── includes/
│   ├── class-neuroblock-admin.php     # Admin interface
│   ├── class-neuroblock-api.php       # API handler
│   ├── class-neuroblock-blocks.php    # Gutenberg blocks
│   ├── class-neuroblock-security.php  # Security & encryption
│   ├── admin-page-template.php        # Admin page template
│   │
│   └── elementor/
│       └── class-neuroblock-elementor-widget.php  # Elementor widget
│
└── languages/
    ├── neuroblock.pot                 # Translation template
    ├── neuroblock-fr_FR.po            # French translation
    └── neuroblock-fr_FR.mo            # Compiled French translation
```

---

## 🔒 Security

NeuroBlock takes security seriously:

- ✅ AES-256 encryption of API keys
- ✅ Uses WordPress salts
- ✅ Nonce verification for all AJAX requests
- ✅ Sanitization of all user inputs
- ✅ No data stored on third-party servers
- ✅ Code compliant with WordPress standards

---

## 🛠️ Development

### Prerequisites

- PHP 7.4+
- WordPress 5.8+
- `openssl` PHP extension enabled

### Building the Plugin

```bash
# Verify all files are present
./verify-files.sh

# Build the plugin (compile translations + create ZIP)
./build.sh
```

The distributable ZIP will be created at: `dist/neuroblock-1.0.0.zip`

### Available Hooks

```php
// Filter prompt before API call
add_filter('neuroblock_prompt', function($prompt, $type, $style) {
    return $prompt . "\nUse vibrant colors.";
}, 10, 3);

// Action after successful generation
add_action('neuroblock_content_generated', function($content, $type) {
    // Your code here
}, 10, 2);
```

---

## 🎨 Design Styles

NeuroBlock offers 6 predefined design styles:

- **Modern**: Clean lines, gradients, shadows
- **Minimalist**: Simple, lots of white space
- **Professional**: Business-oriented, formal
- **Creative**: Bold, unique, artistic
- **Elegant**: Refined, sophisticated
- **Classic**: Traditional, timeless

---

## 💡 Tips for Best Results

### Be Specific
❌ "Create a button"  
✅ "Create a blue gradient button with white text and rounded corners"

### Mention Style
Include style keywords: "modern", "minimalist", "elegant"

### Include Details
Specify sizes, colors, layouts, and element positions

### Use Examples
"Like Airbnb's hero section" or "Similar to Stripe's pricing table"

---

## 📊 Performance

### Generation Times
- Simple block: 5-15 seconds
- Complex block: 10-20 seconds
- Complete page: 30-60 seconds

### Cost Estimates (OpenAI)
- gpt-3.5-turbo: ~$0.002 per block
- gpt-4: ~$0.03 per block

### Optimization
- Use smaller models for simple blocks
- Use larger models for complex pages
- Be specific in prompts to reduce retries

---

## 🌍 Internationalization

NeuroBlock is fully translatable and comes with:
- English (default)
- French (Français)

### Adding a Translation

1. Copy `languages/neuroblock.pot`
2. Rename to `neuroblock-[locale].po` (e.g., `neuroblock-es_ES.po`)
3. Translate using Poedit or similar tool
4. Compile to `.mo` file:
   ```bash
   msgfmt neuroblock-es_ES.po -o neuroblock-es_ES.mo
   ```
5. Place in `languages/` folder

---

## ❤️ Support & Donations

NeuroBlock is **free and open source**. If you find this plugin useful, you can support development:

### Accepted Cryptocurrencies

**Bitcoin (BTC)** - Recommended
```
bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh
```

**Monero (XMR)**
```
4AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRj5UzqtReoS44qo9mtmXCqY45DJ852K5Jv2684Rge
```

**Tether (USDT)**
```
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb5
```

---

## 🐛 Troubleshooting

### Plugin won't activate
**Solution:** Check PHP version (must be 7.4+) and ensure OpenSSL extension is enabled

### API calls fail
**Solutions:**
- Verify API key is correct
- Check internet connection
- Ensure provider endpoint is accessible
- Check WordPress error logs

### SweetAlert doesn't appear
**Solutions:**
- Clear browser cache
- Check browser console for JavaScript errors
- Ensure jQuery is loaded

### Translations don't work
**Solutions:**
- Regenerate .mo file from .po
- Check file permissions
- Verify locale matches WordPress setting

### Elementor widget doesn't appear
**Solutions:**
- Ensure Elementor is installed and activated
- Clear Elementor cache
- Regenerate Elementor CSS

---

## 📝 Changelog

### Version 1.0.0 (2025-01-15)
- 🎉 Initial release
- ✨ Support for OpenAI, DeepSeek, Gemini, Mistral AI
- 🎨 Modern admin interface
- 🔒 API key encryption
- 📦 Gutenberg blocks
- 🚀 AI content generator
- 🌍 English and French translations

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the project
2. Create a branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow WordPress Coding Standards
- Comment your code
- Test thoroughly before submitting

---

## 📄 License

This project is licensed under GPL v2 or later.

See [LICENSE](LICENSE) file for details.

---

## 🔗 Useful Links

- **Website**: https://starlightproagency.com
- **Support**: contact@starlightproagency.com
- **Documentation**: See DEPLOYMENT.md for deployment guide
- **Quick Start**: See QUICK-START.md for 5-minute setup
- **Testing**: See TEST-CHECKLIST.md for complete testing

---

## 🙏 Acknowledgments

- WordPress community for excellent documentation
- OpenAI, DeepSeek, Google, and Mistral for AI APIs
- SweetAlert2 for beautiful notifications
- All contributors and supporters

---

## 📱 Screenshots

### Admin Dashboard
![Admin Dashboard](screenshot-1.png)

### Generator Interface
![Generator](screenshot-2.png)

### Generated Block Example
![Generated Block](screenshot-3.png)

---

## 🎓 FAQ

### Is NeuroBlock really free?
Yes! NeuroBlock itself is 100% free and open source. You only pay for your AI API usage (directly to the provider).

### Which AI provider is best?
- **OpenAI**: Best quality, higher cost
- **DeepSeek**: Great balance of quality and cost
- **Mistral**: European alternative, good quality
- **Gemini**: Google's offering, free tier available

### Can I use NeuroBlock without an API key?
No, you need an API key from at least one supported provider.

### Is my API key safe?
Yes! Your API key is encrypted using AES-256 with WordPress salts and stored securely in your database.

### Can I generate pages in other languages?
Yes! Just write your prompt in the desired language, and the AI will generate content in that language.

### Does NeuroBlock work with page builders?
Yes! NeuroBlock supports both Gutenberg (default WordPress editor) and Elementor.

### Can I edit generated content?
Absolutely! Generated content is standard HTML/CSS that you can edit as needed.

### Is there a limit to generations?
No limits from NeuroBlock. Your only limit is your AI provider's API quota.

---

Developed with ❤️ by **Papyrus** - Starlight Pro Agency

**Transform your WordPress content creation with AI!** 🚀