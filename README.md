# Rise UTM Tracker for Gravity Forms

A WordPress plugin that seamlessly tracks UTM parameters and traffic sources for Gravity Forms submissions. This plugin helps you understand where your form submissions are coming from by capturing and storing traffic source data.

## Features

- Automatically identifies and tracks traffic sources including:
  - Google (Organic & Paid Search)
  - Facebook (Organic & Paid Social)
  - Other Social Media Platforms
  - Direct Traffic
  - Referral Traffic
  - Email Campaigns
- Tracks UTM parameters (utm_source, utm_medium, utm_campaign, utm_term, utm_content)
- Captures referrer and landing page information
- Integrates directly with Gravity Forms
- Easy implementation with a simple 'Traffic Channel' field
- Stores traffic data with form submissions
- Admin interface for configuration and tracking management

## Installation

1. Download the plugin files
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Ensure Gravity Forms is installed and activated

## Quick Start Guide

1. Create or edit a Gravity Form
2. Add the 'Advanced Fields' > 'Traffic Channel' field to your form
3. Save the form
4. That's it! The plugin will automatically start tracking traffic sources for submissions

## How It Works

The plugin automatically captures traffic source data when visitors land on your website. When a form containing the 'Traffic Channel' field is submitted, the plugin associates the captured traffic data with the submission.

### Tracked Parameters

- UTM Source
- UTM Medium
- UTM Campaign
- UTM Term
- UTM Content
- Original Referrer
- Landing Page
- First Visit Timestamp
- Last Visit Timestamp

## Requirements

- WordPress 5.0 or higher
- Gravity Forms 2.4 or higher
- PHP 7.4 or higher

## Configuration

1. Go to WordPress Admin Panel
2. Navigate to Rise UTM > Settings
3. Configure your tracking preferences
4. Optionally set up channel attribution rules

## Support

For support questions or feature requests, please use the GitHub issues section.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under the GPL v2 or later.

---
Made with ❤️ by Rise
