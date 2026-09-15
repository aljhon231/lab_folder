# Laboratory Exercise 4 - Enhanced Inventory Dashboard

This project implements the requirements in Laboratory Exercise 4:
- Advanced DOM manipulation with Vanilla JavaScript ES6
- Asynchronous simulated data loading
- Chart.js interactive visualizations
- Category, stock-status, and price-range filtering
- Product/SKU search
- Low-stock alerts
- CSV export
- Simulated real-time inventory updates
- Responsive Bootstrap 5 layout
- Client-side state management
- Sample PHP JSON API

## Files

- dashboard.html - main dashboard
- css/style.css - custom responsive styling
- js/dataManager.js - data/state/filter/search/export support
- js/charts.js - Chart.js configuration
- js/app.js - DOM updates and event handlers
- api/products.php - optional PHP JSON API sample
- data/products.json - sample data
- README.md - setup instructions

## Run with XAMPP

1. Put this project folder in:
   `C:\xampp\htdocs\lab_folder\lab4_2411600038`
2. Start Apache in XAMPP.
3. Open:
   `http://localhost/lab_folder/lab4_2411600038/dashboard.html`
4. Test the filters, search, charts, low-stock alerts, CSV export, and Simulate Update button.
5. If you want to test the PHP sample API directly, open:
   `http://localhost/lab_folder/lab4_2411600038/api/products.php`

The dashboard currently uses simulated asynchronous data so it works without a database. The PHP API is included as the backend extension requested by the laboratory activity.
