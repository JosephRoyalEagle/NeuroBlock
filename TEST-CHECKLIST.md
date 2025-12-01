# NeuroBlock - Complete Testing Checklist

## 🎯 Pre-Testing Setup

### Required Tools
- [ ] Local WordPress installation (Local by Flywheel, XAMPP, or MAMP)
- [ ] Web browser (Chrome, Firefox recommended)
- [ ] Browser Developer Tools enabled
- [ ] API key for at least one provider (OpenAI, DeepSeek, Gemini, or Mistral)

### Optional Tools
- [ ] Gutenberg plugin (if testing block generation)
- [ ] Elementor plugin (if testing Elementor features)

---

## 📦 Phase 1: Installation Testing

### 1.1 Plugin Upload
- [ ] Navigate to wp-content/plugins/
- [ ] Create neuroblock folder
- [ ] Upload all plugin files
- [ ] Verify file permissions (755 for folders, 644 for files)
- [ ] Check that all subdirectories exist (assets, includes, languages)

### 1.2 Plugin Activation
- [ ] Go to WordPress Admin → Plugins
- [ ] Locate NeuroBlock in plugin list
- [ ] Click "Activate"
- [ ] ✅ Expected: No PHP errors, success message appears
- [ ] ❌ If error: Check error_log, verify PHP version (7.4+), check OpenSSL

### 1.3 Menu Appearance
- [ ] Check WordPress admin sidebar
- [ ] Verify "NeuroBlock" menu item appears
- [ ] Check menu icon displays correctly
- [ ] Click menu item
- [ ] ✅ Expected: Admin page loads with 4 tabs

---

## 🔧 Phase 2: Settings Configuration

### 2.1 AI Settings Tab
- [ ] Click "AI Settings" tab
- [ ] Verify all providers are listed:
  - [ ] OpenAI
  - [ ] DeepSeek
  - [ ] Google Gemini
  - [ ] Mistral AI
- [ ] Verify Ollama is NOT in the list ✅
- [ ] Select a provider
- [ ] Verify model dropdown updates with correct models

### 2.2 API Key Configuration
- [ ] Enter a test API key (use format: sk-test123...)
- [ ] Click "Save Settings"
- [ ] ✅ Expected: SweetAlert success notification appears
- [ ] Verify notification auto-closes after 2 seconds
- [ ] Verify API key field clears for security
- [ ] Check database: wp_options → neuroblock_api_key (should be encrypted)

### 2.3 Model Selection
For each provider, verify correct models:
- [ ] OpenAI: gpt-4, gpt-3.5-turbo, gpt-4-turbo
- [ ] DeepSeek: deepseek-chat, deepseek-coder
- [ ] Gemini: gemini-pro, gemini-ultra
- [ ] Mistral: mistral-small, mistral-medium, mistral-large

---

## 🎨 Phase 3: Generator Testing

### 3.1 Basic Block Generation (Without Gutenberg)
- [ ] Click "Generator" tab
- [ ] Enter prompt: "Create a simple pricing card"
- [ ] Select: Gutenberg Block
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Error message if Gutenberg not installed
- [ ] Message should say: "Gutenberg is required but not installed"

### 3.2 Basic Block Generation (With Gutenberg)
- [ ] Install and activate Gutenberg plugin
- [ ] Refresh admin page
- [ ] Enter prompt: "Create a pricing card with title, price $99, features list (3 items), and blue button"
- [ ] Select: Gutenberg Block, Modern style
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: SweetAlert loading modal appears
- [ ] ✅ Expected: Loading text: "Please wait while AI generates..."
- [ ] ✅ Expected: Cannot click outside or close modal during generation
- [ ] Wait for response (5-30 seconds depending on API)
- [ ] ✅ Expected: Loading closes, success message appears
- [ ] ✅ Expected: Generated code appears in textarea
- [ ] ✅ Expected: "Copy Code" button appears

### 3.3 Copy Functionality
- [ ] Click "Copy Code" button
- [ ] ✅ Expected: SweetAlert with success icon
- [ ] ✅ Expected: Instructions for Gutenberg appear
- [ ] Instructions should include:
  - Step 1: Go to Posts/Pages → Add New
  - Step 2: Click "+" button
  - Step 3: Search for "HTML"
  - Step 4: Paste code
- [ ] Paste in text editor (Ctrl+V / Cmd+V)
- [ ] ✅ Expected: Code is pasted correctly

### 3.4 Elementor Widget Generation (Without Elementor)
- [ ] Deactivate Elementor (if installed)
- [ ] Enter prompt: "Create a testimonial card"
- [ ] Select: Elementor Widget
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Error: "Elementor is required but not installed"

### 3.5 Elementor Widget Generation (With Elementor)
- [ ] Install and activate Elementor
- [ ] Enter prompt: "Create a team member card with photo placeholder, name, role, and social icons"
- [ ] Select: Elementor Widget, Professional style
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Loader, then success
- [ ] ✅ Expected: Instructions for Elementor appear
- [ ] Copy code and test in Elementor HTML widget

### 3.6 Complete Page Generation (Gutenberg)
- [ ] Enter prompt: "Create a landing page for a fitness app with hero section, 3 features, pricing table, and footer"
- [ ] Select: Complete Gutenberg Page, Modern style
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Longer generation time (30-60 seconds)
- [ ] ✅ Expected: Success message with "View Page" link
- [ ] Click "View Page" link
- [ ] ✅ Expected: New page opens in WordPress
- [ ] Verify page appears in Pages list (as Draft)
- [ ] Check page content contains generated HTML

### 3.7 Complete Page Generation (Elementor)
- [ ] Enter prompt: "Create a portfolio page with hero, project grid, about section, and contact form"
- [ ] Select: Complete Elementor Page, Creative style
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Page created
- [ ] ✅ Expected: Can edit with Elementor
- [ ] Verify Elementor metadata exists

### 3.8 Multiple Generation Test
- [ ] Generate a block
- [ ] Wait for completion
- [ ] Immediately try to generate another
- [ ] ✅ Expected: First must complete before second starts
- [ ] ✅ Expected: No overlapping loaders

---

## 🖼️ Phase 4: Blocks & Pages Tab

### 4.1 Recent Blocks Display
- [ ] Click "Blocks & Pages" tab
- [ ] ✅ Expected: Grid with 2 cards (Gutenberg Block, Complete Page)
- [ ] ✅ Expected: Recent blocks section appears
- [ ] If pages were generated in Phase 3:
  - [ ] Verify they appear in recent list
  - [ ] Check titles display correctly
  - [ ] Verify "X ago" timestamp format

### 4.2 Card Interactions
- [ ] Hover over cards
- [ ] ✅ Expected: Cards lift up (transform effect)
- [ ] ✅ Expected: Border color changes
- [ ] ✅ Expected: Shadow increases

---

## 💝 Phase 5: Support Tab

### 5.1 Crypto Address Copy
- [ ] Click "Support" tab
- [ ] Click on Bitcoin address
- [ ] ✅ Expected: SweetAlert "Copied!" appears
- [ ] ✅ Expected: Auto-closes after 2 seconds
- [ ] Paste in text editor
- [ ] ✅ Expected: Bitcoin address pasted correctly
- [ ] Repeat for Monero address
- [ ] Repeat for USDT address

### 5.2 Visual Elements
- [ ] Verify gradient header displays
- [ ] Verify heart icon appears
- [ ] Verify "Recommended" badge on Bitcoin
- [ ] Verify all addresses are readable

---

## 🌍 Phase 6: Translation Testing

### 6.1 French Translation
- [ ] Go to Settings → General
- [ ] Change Site Language to "Français"
- [ ] Save changes
- [ ] Navigate to NeuroBlock
- [ ] ✅ Expected: All interface text in French:
  - "Paramètres IA" not "AI Settings"
  - "Générateur" not "Generator"
  - "Blocs & Pages" not "Blocks & Pages"
  - All buttons in French
  - All notifications in French

### 6.2 English Translation
- [ ] Change Site Language back to English
- [ ] Navigate to NeuroBlock
- [ ] ✅ Expected: All interface text in English

---

## 🔐 Phase 7: Security Testing

### 7.1 API Key Encryption
- [ ] Save an API key in settings
- [ ] Check database: wp_options table
- [ ] Find: option_name = 'neuroblock_api_key'
- [ ] ✅ Expected: option_value is encrypted (base64 gibberish)
- [ ] ✅ Expected: NOT plain text API key

### 7.2 Nonce Verification
- [ ] Open browser DevTools → Network tab
- [ ] Save settings
- [ ] Check AJAX request
- [ ] ✅ Expected: 'nonce' parameter present in request
- [ ] Try to submit without nonce (manually via console)
- [ ] ✅ Expected: Request fails with permission error

### 7.3 Permission Checks
- [ ] Log out of WordPress
- [ ] Try to access: /wp-admin/admin.php?page=neuroblock
- [ ] ✅ Expected: Redirect to login
- [ ] Log in as Subscriber (low-privilege user)
- [ ] Try to access NeuroBlock
- [ ] ✅ Expected: Menu doesn't appear (requires manage_options capability)

---

## 📱 Phase 8: Responsive Testing

### 8.1 Mobile View (< 768px)
- [ ] Open DevTools, toggle device emulation
- [ ] Select iPhone or Android
- [ ] Navigate through all tabs
- [ ] ✅ Expected: Layout adapts to mobile
- [ ] ✅ Expected: No horizontal scrolling
- [ ] ✅ Expected: Buttons are touch-friendly
- [ ] ✅ Expected: Grid becomes single column

### 8.2 Tablet View (768px - 1024px)
- [ ] Select iPad in device emulation
- [ ] Test all tabs
- [ ] ✅ Expected: Layout works well
- [ ] ✅ Expected: Grid remains 2 columns

### 8.3 Desktop View (> 1024px)
- [ ] Return to desktop view
- [ ] Test at different widths
- [ ] ✅ Expected: Max-width container centers content
- [ ] ✅ Expected: Spacing looks good

---

## 🚨 Phase 9: Error Handling

### 9.1 Invalid API Key
- [ ] Enter invalid API key (e.g., "invalid-key-123")
- [ ] Try to generate content
- [ ] ✅ Expected: Error message from API provider
- [ ] ✅ Expected: SweetAlert error modal
- [ ] ✅ Expected: Helpful error message

### 9.2 Empty Prompt
- [ ] Leave prompt textarea empty
- [ ] Click "Generate with AI"
- [ ] ✅ Expected: Warning: "Please enter a description"
- [ ] ✅ Expected: No API call made

### 9.3 Network Error
- [ ] Disconnect internet
- [ ] Try to generate content
- [ ] ✅ Expected: Error message about network failure
- [ ] Reconnect internet
- [ ] Try again
- [ ] ✅ Expected: Works normally

### 9.4 Very Long Prompt
- [ ] Enter a 1000+ word prompt
- [ ] Generate content
- [ ] ✅ Expected: Either succeeds or shows clear error
- [ ] ✅ Expected: No browser freeze

---

## ⚡ Phase 10: Performance Testing

### 10.1 Load Time
- [ ] Clear browser cache
- [ ] Navigate to NeuroBlock admin
- [ ] Measure load time (DevTools → Network)
- [ ] ✅ Expected: < 2 seconds on fast connection
- [ ] Check asset loading:
  - [ ] CSS loads properly
  - [ ] JS loads properly
  - [ ] SweetAlert CDN loads

### 10.2 Generation Speed
For different content types, measure time:
- [ ] Simple block: ✅ Expected 5-15 seconds
- [ ] Complex block: ✅ Expected 10-20 seconds
- [ ] Complete page: ✅ Expected 20-60 seconds

### 10.3 Memory Usage
- [ ] Open DevTools → Memory
- [ ] Take heap snapshot before generation
- [ ] Generate content
- [ ] Take heap snapshot after
- [ ] ✅ Expected: No significant memory leaks
- [ ] ✅ Expected: Memory usage reasonable

---

## 🎨 Phase 11: UI/UX Testing

### 11.1 Visual Consistency
- [ ] Check all buttons use consistent styling
- [ ] Verify color scheme matches throughout
- [ ] Check icons display properly
- [ ] Verify spacing is uniform
- [ ] Check typography is consistent

### 11.2 Interaction Feedback
- [ ] Hover over buttons
- [ ] ✅ Expected: Visual feedback (color change, lift, shadow)
- [ ] Click buttons
- [ ] ✅ Expected: Loading state appears immediately
- [ ] Fill forms
- [ ] ✅ Expected: Input focus styles work

### 11.3 SweetAlert Styling
- [ ] Trigger various alerts
- [ ] ✅ Expected: Consistent with NeuroBlock theme
- [ ] ✅ Expected: Readable text
- [ ] ✅ Expected: Icons display correctly
- [ ] ✅ Expected: Buttons styled properly

---

## 🔍 Phase 12: Browser Compatibility

### 12.1 Chrome
- [ ] Test all features in Chrome
- [ ] ✅ Expected: Everything works perfectly

### 12.2 Firefox
- [ ] Test all features in Firefox
- [ ] ✅ Expected: Everything works perfectly

### 12.3 Safari (if on Mac)
- [ ] Test all features in Safari
- [ ] ✅ Expected: Everything works perfectly

### 12.4 Edge
- [ ] Test all features in Edge
- [ ] ✅ Expected: Everything works perfectly

---

## 📊 Phase 13: Integration Testing

### 13.1 With Gutenberg
- [ ] Create new post
- [ ] Add NeuroBlock AI Content block
- [ ] Generate content via NeuroBlock admin
- [ ] Copy and paste into block
- [ ] ✅ Expected: Content displays correctly
- [ ] Publish post
- [ ] View on frontend
- [ ] ✅ Expected: Styling works

### 13.2 With Elementor
- [ ] Create new page
- [ ] Edit with Elementor
- [ ] Add HTML widget
- [ ] Generate content via NeuroBlock
- [ ] Paste into widget
- [ ] ✅ Expected: Content displays
- [ ] Preview page
- [ ] ✅ Expected: Looks good

---

## ✅ Final Checklist

### Must Pass
- [ ] Plugin activates without errors
- [ ] All tabs accessible
- [ ] Settings save successfully
- [ ] API keys are encrypted
- [ ] Content generation works
- [ ] SweetAlert notifications work
- [ ] Copy functionality works
- [ ] Gutenberg check works
- [ ] Elementor check works
- [ ] Translations work
- [ ] No console errors
- [ ] No PHP errors
- [ ] Security checks pass

### Nice to Have
- [ ] Fast load times
- [ ] Smooth animations
- [ ] Good mobile experience
- [ ] All browsers supported
- [ ] Clear error messages

---

## 📝 Test Results Template

```
Test Date: __________
Tester: __________
WordPress Version: __________
PHP Version: __________
Gutenberg: Yes / No
Elementor: Yes / No

Phase 1 - Installation: ✅ Pass / ❌ Fail
Phase 2 - Settings: ✅ Pass / ❌ Fail
Phase 3 - Generator: ✅ Pass / ❌ Fail
Phase 4 - Blocks Tab: ✅ Pass / ❌ Fail
Phase 5 - Support: ✅ Pass / ❌ Fail
Phase 6 - Translation: ✅ Pass / ❌ Fail
Phase 7 - Security: ✅ Pass / ❌ Fail
Phase 8 - Responsive: ✅ Pass / ❌ Fail
Phase 9 - Errors: ✅ Pass / ❌ Fail
Phase 10 - Performance: ✅ Pass / ❌ Fail
Phase 11 - UI/UX: ✅ Pass / ❌ Fail
Phase 12 - Browsers: ✅ Pass / ❌ Fail
Phase 13 - Integration: ✅ Pass / ❌ Fail

Overall Result: ✅ PASS / ❌ FAIL

Notes:
_______________________________
_______________________________
_______________________________
```

---

## 🐛 Bug Report Template

```
Bug Title: __________

Steps to Reproduce:
1. 
2. 
3. 

Expected Result:
__________

Actual Result:
__________

Environment:
- WordPress: __________
- PHP: __________
- Browser: __________
- Plugins: __________

Screenshots:
(attach if applicable)

Priority: High / Medium / Low
```