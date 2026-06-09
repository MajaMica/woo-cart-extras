WooCommerce Cart Extras

A lightweight, production-ready WooCommerce customization plugin that extends cart functionality with dynamic UX features such as gift products, express shipping options, and product visibility rules.

This is a working development codebase designed for real-world use and further adaptation based on project requirements.

Overview

WooCommerce Cart Extras enhances the default WooCommerce cart experience by adding flexible, behavior-driven features without relying on heavy third-party plugins.

The code is structured as a modular functional plugin, making it easy to extend, modify, or refactor into a more advanced architecture (OOP or service-based plugin) for future projects.

Features
🎁 Gift Product Toggle (AJAX)
Adds optional gift product to cart via checkbox
Real-time AJAX add/remove without page reload
Fully integrated with WooCommerce cart system
🚀 Express Shipping / Packaging Upsell
Optional express delivery product toggle
AJAX-based cart updates
Useful for checkout upsell strategies
👁️ Smart Product Visibility Rules
Hide specific products from:
Shop page
Category pages
Product search results
🔒 Cart Quantity Restrictions
Forces quantity display to 1 for selected special products
Prevents modification of predefined system products
📦 Cart Count Optimization
Excludes special system products from cart item count
Useful for free shipping and threshold logic
⚡ Generic AJAX Add-to-Cart Endpoint
Reusable AJAX handler for custom frontend integrations
Supports quantity-based product additions
Technical Stack
WordPress Plugin Architecture
WooCommerce Hooks & Filters
jQuery AJAX
PHP procedural development
Dynamic cart manipulation
Installation

Upload plugin folder to:

/wp-content/plugins/woo-cart-extras/
Activate plugin via WordPress admin panel
Ensure WooCommerce is installed and active
Usage

Once activated, features are automatically applied based on predefined product IDs and WooCommerce hooks.

No additional configuration is required unless customization of product IDs or business logic is needed.

Notes
This is a working development codebase, not a final commercial plugin
Easily adaptable for different ecommerce requirements
Can be refactored into:
OOP architecture
Service-based plugin structure
Admin settings panel
Multi-store reusable module

Purpose

This plugin is intended as a flexible development foundation for WooCommerce projects where custom cart behavior, upsells, and product logic are required.

It is built to be:

easy to extend
easy to modify
reusable across multiple client projects
