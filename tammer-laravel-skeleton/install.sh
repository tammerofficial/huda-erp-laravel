#!/bin/bash

# Tammer Laravel Skeleton Installer
# This script sets up deployment files for a new Laravel project

set -e

echo "🚀 Tammer Laravel Skeleton Installer"
echo "======================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get project details
read -p "Enter your APP_NAME (e.g., my-app): " APP_NAME
read -p "Enter your APP_URL (e.g., https://example.com): " APP_URL
read -p "Enter your DB_DATABASE: " DB_DATABASE
read -p "Enter your DB_USERNAME: " DB_USERNAME
read -sp "Enter your DB_PASSWORD: " DB_PASSWORD
echo ""

# Create .cpanel.yml from template
if [ -f ".cpanel.yml.template" ]; then
    echo -e "${GREEN}✓${NC} Creating .cpanel.yml..."
    sed -e "s/YOUR_APP_NAME/$APP_NAME/g" \
        -e "s|https://your-domain.com|$APP_URL|g" \
        -e "s/your_database_name/$DB_DATABASE/g" \
        -e "s/your_database_user/$DB_USERNAME/g" \
        -e "s|\"your_database_password\"|\"$DB_PASSWORD\"|g" \
        .cpanel.yml.template > .cpanel.yml
    rm .cpanel.yml.template
    echo -e "${GREEN}✓${NC} .cpanel.yml created successfully"
else
    echo -e "${YELLOW}⚠${NC} .cpanel.yml.template not found, skipping..."
fi

# Create .github/workflows/deploy-cpanel.yml from template
if [ -f ".github/workflows/deploy-cpanel.yml.template" ]; then
    echo -e "${GREEN}✓${NC} Creating GitHub Actions workflow..."
    mkdir -p .github/workflows
    read -p "Enter your GitHub Secret name (e.g., MY_PROJECT_CPANEL): " SECRET_NAME
    sed -e "s/YOUR_PROJECT_CPANEL_SECRET/$SECRET_NAME/g" \
        .github/workflows/deploy-cpanel.yml.template > .github/workflows/deploy-cpanel.yml
    rm .github/workflows/deploy-cpanel.yml.template
    echo -e "${GREEN}✓${NC} GitHub Actions workflow created"
    echo -e "${YELLOW}⚠${NC} Don't forget to add '$SECRET_NAME' secret in GitHub!"
else
    echo -e "${YELLOW}⚠${NC} deploy-cpanel.yml.template not found, skipping..."
fi

# Install npm dependencies
if [ -f "package.json" ]; then
    echo -e "${GREEN}✓${NC} Installing npm dependencies..."
    npm install
    echo -e "${GREEN}✓${NC} npm dependencies installed"
fi

echo ""
echo -e "${GREEN}✅ Installation complete!${NC}"
echo ""
echo "Next steps:"
echo "1. Add GitHub Secret: $SECRET_NAME"
echo "2. Set up cPanel Git Repository"
echo "3. Run: git push origin main"
echo ""

