#!/bin/bash

# NeuroBlock File Verification Script
# Checks that all required files are present

echo "🔍 NeuroBlock File Verification"
echo "==============================="
echo ""

ERRORS=0
WARNINGS=0

# Function to check file exists
check_file() {
    if [ -f "$1" ]; then
        echo "✅ $1"
    else
        echo "❌ MISSING: $1"
        ((ERRORS++))
    fi
}

# Function to check directory exists
check_dir() {
    if [ -d "$1" ]; then
        echo "✅ $1/"
    else
        echo "❌ MISSING: $1/"
        ((ERRORS++))
    fi
}

echo "📁 Checking root files..."
check_file "neuroblock.php"
check_file "README.md"
check_file "LICENSE"
check_file "icon.svg"
echo ""

echo "📁 Checking directories..."
check_dir "assets"
check_dir "assets/css"
check_dir "assets/js"
check_dir "includes"
check_dir "includes/elementor"
check_dir "languages"
echo ""

echo "🎨 Checking CSS files..."
check_file "assets/css/neuroblock-admin.css"
check_file "assets/css/neuroblock-blocks.css"
check_file "assets/css/neuroblock-blocks-editor.css"
echo ""

echo "⚡ Checking JavaScript files..."
check_file "assets/js/neuroblock-admin.js"
check_file "assets/js/neuroblock-blocks.js"
echo ""

echo "🔧 Checking PHP includes..."
check_file "includes/class-neuroblock-admin.php"
check_file "includes/class-neuroblock-api.php"
check_file "includes/class-neuroblock-blocks.php"
check_file "includes/class-neuroblock-security.php"
check_file "includes/admin-page-template.php"
check_file "includes/elementor/class-neuroblock-elementor-widget.php"
echo ""

echo "🌍 Checking translation files..."
check_file "languages/neuroblock.pot"
check_file "languages/neuroblock-fr_FR.po"

if [ -f "languages/neuroblock-fr_FR.mo" ]; then
    echo "✅ languages/neuroblock-fr_FR.mo"
else
    echo "⚠️  languages/neuroblock-fr_FR.mo (not compiled yet - run build.sh)"
    ((WARNINGS++))
fi
echo ""

echo "📋 Checking documentation..."
check_file "DEPLOYMENT.md"
check_file "TEST-CHECKLIST.md"
check_file "QUICK-START.md"
echo ""

echo "🔨 Checking build files..."
if [ -f "build.sh" ]; then
    echo "✅ build.sh"
else
    echo "⚠️  build.sh (optional)"
    ((WARNINGS++))
fi

if [ -f "verify-files.sh" ]; then
    echo "✅ verify-files.sh"
else
    echo "⚠️  verify-files.sh (this file)"
    ((WARNINGS++))
fi
echo ""

# Verify file permissions (Unix/Linux/Mac only)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔒 Checking file permissions..."
    
    # Check PHP files are readable
    find . -name "*.php" -type f ! -perm -644 2>/dev/null | while read file; do
        echo "⚠️  $file has wrong permissions (should be 644)"
        ((WARNINGS++))
    done
    
    # Check directories are executable
    find . -type d ! -perm -755 2>/dev/null | while read dir; do
        if [ "$dir" != "." ]; then
            echo "⚠️  $dir has wrong permissions (should be 755)"
            ((WARNINGS++))
        fi
    done
    echo ""
fi

# Check for Ollama references (should be removed)
echo "🔍 Checking for Ollama references..."
if grep -r "ollama" --include="*.php" --include="*.js" . 2>/dev/null | grep -v "verify-files.sh"; then
    echo "⚠️  Found Ollama references - these should be removed!"
    ((WARNINGS++))
else
    echo "✅ No Ollama references found"
fi
echo ""

# Check for Mistral references (should be present)
echo "🔍 Checking for Mistral references..."
if grep -r "mistral" --include="*.php" . 2>/dev/null | grep -v "verify-files.sh" > /dev/null; then
    echo "✅ Mistral AI provider is configured"
else
    echo "❌ Mistral AI provider not found!"
    ((ERRORS++))
fi
echo ""

# Summary
echo "================================"
echo "📊 Verification Summary"
echo "================================"

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo "✅ Perfect! All files present and correct"
    echo ""
    echo "🎉 Your plugin is ready for testing!"
    echo ""
    echo "Next steps:"
    echo "1. Run: chmod +x build.sh"
    echo "2. Run: ./build.sh"
    echo "3. Upload to WordPress"
    echo "4. Follow QUICK-START.md"
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo "⚠️  $WARNINGS warning(s) - plugin may work but should be fixed"
    echo ""
    echo "You can still test, but fix warnings for production."
    exit 0
else
    echo "❌ $ERRORS error(s), $WARNINGS warning(s)"
    echo ""
    echo "Please fix errors before deployment!"
    echo "Missing files must be created or restored."
    exit 1
fi