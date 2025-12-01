# NeuroBlock - Quick Start Guide 🚀

Get up and running with NeuroBlock in 5 minutes!

## 📦 Step 1: Install (2 minutes)

### Method A: Upload ZIP
1. Download `neuroblock-1.0.0.zip`
2. Go to WordPress admin → Plugins → Add New
3. Click "Upload Plugin"
4. Choose the ZIP file
5. Click "Install Now"
6. Click "Activate"

### Method B: Manual Upload
1. Unzip the plugin
2. Upload `neuroblock` folder to `/wp-content/plugins/`
3. Go to WordPress admin → Plugins
4. Find "NeuroBlock" and click "Activate"

✅ **Success Check:** You should see "NeuroBlock" in the admin sidebar

---

## 🔑 Step 2: Get API Key (2 minutes)

Choose ONE provider and get your API key:

### Option 1: OpenAI (Recommended)
- Go to: https://platform.openai.com/api-keys
- Sign in or create account
- Click "Create new secret key"
- Copy the key (starts with `sk-`)
- **Cost:** Pay as you go (~$0.002 per generation)

### Option 2: DeepSeek (Budget-Friendly)
- Go to: https://platform.deepseek.com
- Register and login
- Navigate to API section
- Generate key
- **Cost:** Very affordable

### Option 3: Mistral AI (European)
- Go to: https://console.mistral.ai
- Create account
- Get API key
- **Cost:** Competitive pricing

### Option 4: Google Gemini (Google)
- Go to: https://makersuite.google.com
- Sign in with Google
- Get API key
- **Cost:** Free tier available

---

## ⚙️ Step 3: Configure (1 minute)

1. Click **NeuroBlock** in WordPress sidebar
2. You'll see the **AI Settings** tab
3. Select your provider (OpenAI, DeepSeek, Gemini, or Mistral)
4. Paste your API key
5. Select a model:
   - **OpenAI:** gpt-4 (best quality) or gpt-3.5-turbo (faster)
   - **DeepSeek:** deepseek-chat (general) or deepseek-coder (code)
   - **Mistral:** mistral-large (best) or mistral-small (fast)
   - **Gemini:** gemini-pro
6. Click **Save Settings**

✅ **Success Check:** Green notification "Settings saved successfully"

---

## 🎨 Step 4: Generate Your First Block (30 seconds)

1. Click the **Generator** tab
2. In the text box, type:
   ```
   Create a pricing card with title "Pro Plan", price "$99/month",
   3 feature bullet points, and a purple CTA button "Get Started"
   ```
3. Select:
   - **Content Type:** Gutenberg Block
   - **Style:** Modern
4. Click **Generate with AI**
5. Wait 5-15 seconds ⏳
6. Click **Copy Code** button

✅ **Success Check:** You see HTML/CSS code in the box

---

## 📄 Step 5: Use Your Generated Content (30 seconds)

### For Gutenberg (Default WordPress Editor):
1. Go to **Posts** or **Pages** → **Add New**
2. Click the **+** button
3. Search for **"HTML"**
4. Select **"Custom HTML"** block
5. Paste your code (Ctrl+V or Cmd+V)
6. Click **Preview** to see it
7. Publish when ready!

### For Elementor (if installed):
1. Go to **Pages** → **Add New**
2. Click **"Edit with Elementor"**
3. Drag **HTML widget** to page
4. Paste your code
5. Click **Update**

✅ **Success Check:** Your AI-generated content displays beautifully!

---

## 🎯 Quick Examples to Try

### Example 1: Hero Section
```
Create a hero section with headline "Transform Your Business",
subtitle "AI-powered solutions for modern companies",
and two buttons "Get Started" and "Learn More"
```

### Example 2: Feature Grid
```
Create a 3-column feature grid with icons,
titles, and descriptions. Features: Speed, Security, Support
```

### Example 3: Testimonial
```
Create a testimonial card with 5-star rating,
quote text, customer photo, name and company
```

### Example 4: Contact Form
```
Create a contact form with fields for name, email,
subject, message, and submit button. Modern design.
```

### Example 5: Complete Landing Page
```
Create a landing page for a fitness app with:
- Hero section with app screenshot
- 3 key features with icons
- Pricing table (Free, Pro, Enterprise)
- Testimonials section
- CTA footer
```
*Select: Complete Gutenberg Page*

---

## 💡 Pro Tips

### 🎨 For Best Results:
- **Be specific:** "blue button" not "button"
- **Mention style:** "modern", "minimalist", "professional"
- **Include details:** sizes, colors, layouts
- **Use examples:** "like Airbnb's hero section"

### ⚡ For Speed:
- Use **gpt-3.5-turbo** or **mistral-small** for simple blocks
- Use **gpt-4** or **mistral-large** for complex pages
- Simple blocks: 5-15 seconds
- Complete pages: 30-60 seconds

### 💰 For Cost:
- OpenAI gpt-3.5-turbo: ~$0.002 per block
- OpenAI gpt-4: ~$0.03 per block
- DeepSeek: Even cheaper!
- Mistral: Competitive pricing

### 🔒 For Security:
- Your API key is **encrypted** in database
- **Never share** your API key
- Keys are **never sent** to NeuroBlock servers
- All AI calls go **directly** to your chosen provider

---

## 🆘 Common Issues

### "API key not configured"
- Go to AI Settings tab
- Make sure you saved your API key
- Try entering it again

### "Gutenberg is required"
- Install Gutenberg plugin OR
- Switch to "Elementor Widget" type OR
- WordPress 5.0+ has Gutenberg built-in

### "Elementor is required"
- Install Elementor plugin OR
- Switch to "Gutenberg Block" type

### Generation takes too long
- Check your internet connection
- Try a simpler prompt
- Use a faster model (gpt-3.5-turbo, mistral-small)

### Code doesn't display correctly
- Make sure you're using Custom HTML block in Gutenberg
- Check for JavaScript console errors
- Try regenerating with clearer prompt

---

## 🌍 Language Support

NeuroBlock automatically detects your WordPress language:

- **English:** Default
- **French:** Automatically enabled if WordPress is set to French
- **More coming soon!**

To change language:
1. Go to **Settings** → **General**
2. Change **Site Language**
3. Save and reload NeuroBlock

---

## 📱 Mobile-Friendly

All generated content is **responsive by default**!

The AI is instructed to create mobile-friendly code, but you can also specify:
```
Create a responsive pricing card that works on mobile,
tablet, and desktop
```

---

## 🎓 Learning Path

### Beginner (Day 1):
1. ✅ Complete this Quick Start
2. Generate 5 simple blocks
3. Test them on a page

### Intermediate (Week 1):
1. Try different styles (Modern, Minimalist, etc.)
2. Generate complete pages
3. Customize generated code
4. Test Elementor widgets

### Advanced (Month 1):
1. Create custom prompts library
2. Combine multiple blocks
3. Integrate with themes
4. Build complete site sections

---

## 🚀 Next Steps

Now that you're set up:

1. **Explore Styles:** Try Modern, Minimalist, Professional, Creative, Elegant, Classic
2. **Try Different Types:** Blocks, Widgets, Complete Pages
3. **Experiment:** The AI is creative - try unique prompts!
4. **Share:** Generate content for clients or projects
5. **Support:** If you love it, consider donating (Support tab) 💜

---

## 📞 Need Help?

- **Email:** contact@starlightproagency.com
- **Website:** https://starlightproagency.com
- **Docs:** Full documentation in README.md
- **Testing:** Complete checklist in TEST-CHECKLIST.md

---

## 🎉 You're Ready!

You now know how to:
- ✅ Install NeuroBlock
- ✅ Configure your API
- ✅ Generate content with AI
- ✅ Use it in WordPress

**Time to create amazing content!** 🚀

---

**Happy Generating! 💜**

*NeuroBlock - AI-powered WordPress content generation*