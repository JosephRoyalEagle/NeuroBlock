#!/bin/bash

# NeuroBlock Build Script
# Compiles translations and creates distribution package

echo "🚀 NeuroBlock Build Script"
echo "=========================="
echo ""

# Check if msgfmt is available
if ! command -v msgfmt &> /dev/null; then
    echo "❌ Error: msgfmt not found. Please install gettext."
    echo "   Ubuntu/Debian: sudo apt-get install gettext"
    echo "   macOS: brew install gettext"
    exit 1
fi

# Compile French translation
echo "📦 Compiling French translation..."
msgfmt languages/neuroblock-fr_FR.po -o languages/neuroblock-fr_FR.mo

if [ $? -eq 0 ]; then
    echo "✅ French translation compiled successfully"
else
    echo "❌ Error compiling French translation"
    exit 1
fi

# Create distribution directory
echo ""
echo "📁 Creating distribution package..."
mkdir -p dist/neuroblock

# Copy all files
cp -r neuroblock.php dist/neuroblock/
cp -r README.md dist/neuroblock/
cp -r LICENSE dist/neuroblock/
cp -r icon.svg dist/neuroblock/
cp -r assets dist/neuroblock/
cp -r includes dist/neuroblock/
cp -r languages dist/neuroblock/

# Create ZIP file
cd dist
zip -r neuroblock-1.0.0.zip neuroblock
cd ..

echo "✅ Distribution package created: dist/neuroblock-1.0.0.zip"
echo ""
echo "📊 Package contents:"
echo "   - neuroblock.php (Main plugin file)"
echo "   - README.md (Documentation)"
echo "   - LICENSE (GPL v2)"
echo "   - icon.svg (Plugin icon)"
echo "   - assets/ (CSS, JS)"
echo "   - includes/ (PHP classes)"
echo "   - languages/ (Translations)"
echo ""
echo "🎉 Build completed successfully!"
echo ""
echo "📋 Next steps:"
echo "   1. Test the plugin in local WordPress"
echo "   2. Upload to wp-content/plugins/"
echo "   3. Activate from WordPress admin"
echo "   4. Configure API settings"
echo ""