#!/bin/bash

# Tammer Deployment Installer
# Reads deploy.config.json and automatically configures all files

set -e

echo "🚀 Tammer Deployment Installer"
echo "======================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if config file exists
if [ ! -f "deploy.config.json" ]; then
    echo -e "${RED}✗${NC} deploy.config.json not found!"
    echo ""
    echo "Please create deploy.config.json with your project settings."
    echo "See deploy.config.json.example for reference."
    exit 1
fi

echo -e "${GREEN}✓${NC} Reading deploy.config.json..."

# Read config using Node.js or Python (whichever is available)
if command -v node &> /dev/null; then
    # Using Node.js
    APP_NAME=$(node -e "console.log(require('./deploy.config.json').app.name)")
    APP_URL=$(node -e "console.log(require('./deploy.config.json').app.url)")
    APP_ENV=$(node -e "console.log(require('./deploy.config.json').app.env)")
    APP_DEBUG=$(node -e "console.log(require('./deploy.config.json').app.debug)")
    DB_HOST=$(node -e "console.log(require('./deploy.config.json').database.host)")
    DB_PORT=$(node -e "console.log(require('./deploy.config.json').database.port)")
    DB_DATABASE=$(node -e "console.log(require('./deploy.config.json').database.database)")
    DB_USERNAME=$(node -e "console.log(require('./deploy.config.json').database.username)")
    DB_PASSWORD=$(node -e "console.log(require('./deploy.config.json').database.password)")
    CPANEL_HOST=$(node -e "console.log(require('./deploy.config.json').cpanel.host)")
    CPANEL_USER=$(node -e "console.log(require('./deploy.config.json').cpanel.user)")
    CPANEL_PASSWORD=$(node -e "console.log(require('./deploy.config.json').cpanel.password)")
    CPANEL_PORT=$(node -e "console.log(require('./deploy.config.json').cpanel.port)")
    CPANEL_REPO_PATH=$(node -e "console.log(require('./deploy.config.json').cpanel.repo_path)")
    GITHUB_SECRET_NAME=$(node -e "console.log(require('./deploy.config.json').github.secret_name)")
elif command -v python3 &> /dev/null; then
    # Using Python 3
    APP_NAME=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['app']['name'])")
    APP_URL=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['app']['url'])")
    APP_ENV=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['app']['env'])")
    APP_DEBUG=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['app']['debug'])")
    DB_HOST=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['database']['host'])")
    DB_PORT=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['database']['port'])")
    DB_DATABASE=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['database']['database'])")
    DB_USERNAME=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['database']['username'])")
    DB_PASSWORD=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['database']['password'])")
    CPANEL_HOST=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['cpanel']['host'])")
    CPANEL_USER=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['cpanel']['user'])")
    CPANEL_PASSWORD=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['cpanel']['password'])")
    CPANEL_PORT=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['cpanel']['port'])")
    CPANEL_REPO_PATH=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['cpanel']['repo_path'])")
    GITHUB_SECRET_NAME=$(python3 -c "import json; print(json.load(open('deploy.config.json'))['github']['secret_name'])")
else
    echo -e "${RED}✗${NC} Node.js or Python 3 required to read JSON config!"
    echo "Please install Node.js or Python 3, or edit files manually."
    exit 1
fi

# Validate required fields
if [ "$APP_NAME" = "YOUR_APP_NAME" ] || [ "$APP_URL" = "https://your-domain.com" ] || [ "$DB_DATABASE" = "your_database_name" ]; then
    echo -e "${YELLOW}⚠${NC} Warning: Some values in deploy.config.json are still placeholders!"
    echo "Please update deploy.config.json with your actual values."
    read -p "Continue anyway? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo -e "${GREEN}✓${NC} Config loaded successfully"
echo ""

# Create .cpanel.yml from template
if [ -f ".cpanel.yml.template" ]; then
    echo -e "${GREEN}✓${NC} Creating .cpanel.yml..."
    sed -e "s/YOUR_APP_NAME/$APP_NAME/g" \
        -e "s|https://your-domain.com|$APP_URL|g" \
        -e "s/your_database_name/$DB_DATABASE/g" \
        -e "s/your_database_user/$DB_USERNAME/g" \
        -e "s|\"your_database_password\"|\"$DB_PASSWORD\"|g" \
        -e "s/127.0.0.1/$DB_HOST/g" \
        -e "s/3306/$DB_PORT/g" \
        .cpanel.yml.template > .cpanel.yml
    rm .cpanel.yml.template
    echo -e "${GREEN}✓${NC} .cpanel.yml created"
else
    echo -e "${YELLOW}⚠${NC} .cpanel.yml.template not found"
fi

# Create GitHub Actions workflow from template
if [ -f ".github/workflows/deploy-cpanel.yml.template" ]; then
    echo -e "${GREEN}✓${NC} Creating GitHub Actions workflow..."
    mkdir -p .github/workflows
    sed -e "s/YOUR_PROJECT_CPANEL_SECRET/$GITHUB_SECRET_NAME/g" \
        .github/workflows/deploy-cpanel.yml.template > .github/workflows/deploy-cpanel.yml
    rm .github/workflows/deploy-cpanel.yml.template
    echo -e "${GREEN}✓${NC} GitHub Actions workflow created"
else
    echo -e "${YELLOW}⚠${NC} deploy-cpanel.yml.template not found"
fi

# Create GitHub Secrets instruction file
echo -e "${GREEN}✓${NC} Creating GitHub Secrets instruction..."
cat > GITHUB_SECRETS_INSTRUCTIONS.txt <<EOF
==========================================
GitHub Secrets Setup Instructions
==========================================

1. Go to: Repository » Settings » Secrets and variables » Actions

2. Add Environment: "${APP_NAME} cpanel"

3. Add Secret: "${GITHUB_SECRET_NAME}"

4. Secret Content:
CPANEL_HOST=${CPANEL_HOST}
CPANEL_USER=${CPANEL_USER}
CPANEL_PASSWORD=${CPANEL_PASSWORD}
CPANEL_PORT=${CPANEL_PORT}
CPANEL_REPO_PATH=${CPANEL_REPO_PATH}

5. Save

==========================================
EOF
echo -e "${GREEN}✓${NC} Instructions saved to GITHUB_SECRETS_INSTRUCTIONS.txt"

echo ""
echo -e "${GREEN}✅ Installation complete!${NC}"
echo ""
echo "Summary:"
echo "  - APP_NAME: $APP_NAME"
echo "  - APP_URL: $APP_URL"
echo "  - Database: $DB_DATABASE"
echo "  - GitHub Secret: $GITHUB_SECRET_NAME"
echo ""
echo "Next steps:"
echo "  1. Review GITHUB_SECRETS_INSTRUCTIONS.txt"
echo "  2. Add GitHub Secret as instructed"
echo "  3. Set up cPanel Git Repository"
echo "  4. Run: git push origin main"
echo ""
