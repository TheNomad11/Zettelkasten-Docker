# Zettelkasten-Docker
A simple Zettelkasten web app for organizing your notes and ideas using the Zettelkasten method.

Here's a comprehensive README.md for your Zettelkasten app that you can copy and paste directly into your GitHub repository:

## Features

- **Markdown Support**: Write and format notes using Markdown syntax
- **Tag System**: Organize notes with custom tags
- **Linking**: Create connections between notes with internal links
- **Search**: Full-text search across all your notes
- **Export Options**:
  - Single Markdown file with table of contents
  - ZIP archive with individual Markdown files
  - JSON backup for complete data preservation
- **Import Functionality**: Import from JSON backups
- **Security Features**:
  - CSRF protection
  - Session management with timeout
  - Rate limiting for login attempts
  - Secure password hashing
- **Responsive Design**: Works on desktop and mobile devices
- **Progressive Web App**: Installable with offline capabilities

## Installation

### Requirements
- PHP 8.4+
- Web server (Apache recommended)
- MySQL (optional, for future database support)

### Quick Setup with Docker

1. Clone this repository:
   ```bash
   git clone https://github.com/TheNomad11/zettelkasten.git
   cd zettelkasten
   ```

2. Edit `.env` with your credentials:
   ```ini
   ZETTEL_USERNAME=your_username
   ZETTEL_PASSWORD=your_secure_password
   SESSION_LIFETIME=2592000
   SESSION_TIMEOUT=7200
   ```

3. Start the application:
   ```bash
   docker-compose up -d
   ```

4. Access the application at `http://localhost:8333`

### Manual Installation

1. Upload files to your web server
2. Create a `config.php` file from the template
3. Set up your `.env` file with credentials
4. Ensure the `zettels` directory is writable by the web server

## Usage

### Creating Notes
1. Click "Create New Zettel"
2. Add a title and content (Markdown supported)
3. Add tags (comma-separated)
4. Link to other notes using their IDs

### Exporting Notes
- **Single Markdown**: All notes in one file with table of contents
- **ZIP Archive**: Individual Markdown files with INDEX.md
- **JSON Backup**: Complete backup with all metadata

### Importing Notes
1. Go to the Import page
2. Upload a JSON backup file
3. Review the import summary

## Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Session Management**: Automatic timeout and strict mode
- **Rate Limiting**: Prevents brute force attacks
- **Secure Headers**: XSS and clickjacking protection
- **Input Validation**: All user input is sanitized

## Configuration

Edit `config.php` or use environment variables:

```ini
# Session settings
SESSION_LIFETIME=2592000  # 30 days
SESSION_TIMEOUT=7200       # 2 hours

# Security settings
MAX_LOGIN_ATTEMPTS=5
LOGIN_LOCKOUT_TIME=900     # 15 minutes

# Application settings
ZETTELS_PER_PAGE=10
RELATED_ZETTELS_LIMIT=5
```

## Development

### Requirements
- PHP 8.4+
- Composer (for dependency management)
- Node.js (for frontend assets)

### Setup
```bash
composer install
npm install
```

### Running Tests
```bash
php vendor/bin/phpunit
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

**Note**: This application is designed for personal use. For production deployment, ensure you:
1. Set strong credentials
2. Configure proper HTTPS
3. Regularly backup your data
4. Monitor security updates

You can customize the sections as needed for your specific implementation. The content is based on the features visible in your codebase, particularly the export/import functionality, security measures, and the overall structure of your Zettelkasten application.
