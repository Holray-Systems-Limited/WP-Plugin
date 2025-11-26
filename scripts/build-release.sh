
#!/bin/bash
set -e

# Variables
BRANCH="tom-build"
REPO="plugin"
ZIP_NAME="holray-units.zip"
PLUGIN_NAME="holray-units"
git@github.com:Holray-Simon/plugin.git
# Clone the repository and checkout the branch
git clone -b $BRANCH git@github.com:Holray-Simon/$REPO.git

# Rename folder to official plugin name
mv $REPO $PLUGIN_NAME

# Install production dependencies
cd $PLUGIN_NAME
composer install --no-dev --optimize-autoloader
cd ..

# Create ZIP excluding .git
zip -r $ZIP_NAME $PLUGIN_NAME -x "$PLUGIN_NAME/.git/*" " $PLUGIN_NAME/scripts/*" "$PLUGIN_NAME/*.md"

echo "✅ Release ZIP created: $ZIP_NAME"
