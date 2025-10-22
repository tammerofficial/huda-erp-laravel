# 🚀 Quick Start Guide - WooCommerce Integration

## ✅ System is Ready!

All features have been implemented and tested. Here's how to use them:

---

## 🔧 Setup (One-Time)

### 1. Settings are Already Configured
The system has been seeded with default settings:
```bash
# Already done - just for reference
php artisan db:seed --class=CostingSettingsSeeder
```

### 2. Start WooCommerce Auto-Sync
```bash
# This is already running in the background
php artisan schedule:work
```

---

## 💰 Cost Management

### Recalculate All Product Costs
```bash
php artisan products:recalculate-costs
```

### Recalculate Specific Products
```bash
php artisan products:recalculate-costs --product=1 --product=5 --product=10
```

### Manual WooCommerce Sync
```bash
php artisan woocommerce:sync
```

---

## 📊 Using the Features

### 1. View Order Cost Breakdown
Go to any order page and click "View Cost Breakdown" or visit:
```
http://your-domain/orders/{order-id}/cost-breakdown
```

### 2. View Profitability Report
```
http://your-domain/reports/profitability
```
- Filter by date range
- See revenue, costs, profit, margin
- View most profitable orders
- Track UTM sources

### 3. Recalculate Order Costs
On any order page, click "Recalculate Costs" button

### 4. Calculate Product Cost
On any product page, click "Calculate Cost" button

---

## 🔄 How It Works

### Automatic Processes

1. **Every 5 Minutes:**
   - Syncs new orders from WooCommerce
   - Syncs new products
   - Syncs new customers
   - Calculates costs automatically
   - Tracks shipping costs
   - Records UTM analytics

2. **When Order Status Changes to "Completed":**
   - Creates revenue accounting entry
   - Records shipping revenue
   - Records cost of goods sold
   - Creates profit entry
   - Generates journal entries

3. **When Product BOM is Created/Updated:**
   - Recalculates material cost
   - Calculates labor cost
   - Adds overhead cost
   - Generates suggested price

---

## 📈 What You'll See

### Orders Now Include:
- ✅ Material Cost
- ✅ Labor Cost
- ✅ Overhead Cost
- ✅ Shipping Cost
- ✅ Total Cost
- ✅ Profit Amount
- ✅ Profit Margin %
- ✅ UTM Source/Medium/Campaign

### Products Now Include:
- ✅ Calculated Cost
- ✅ Labor Percentage
- ✅ Overhead Percentage
- ✅ Suggested Price
- ✅ Last Calculation Date

### Reports Now Include:
- ✅ Profitability by Period
- ✅ Cost Breakdown by Category
- ✅ Marketing Channel Performance
- ✅ Most Profitable Orders

---

## ⚙️ Configuration

### Edit Cost Settings
Go to Settings → Costing tab:
- Labor Cost Percentage (default: 30%)
- Overhead Cost Percentage (default: 20%)
- Profit Margin Target (default: 40%)

### Edit Shipping Settings
Go to Settings → Shipping tab:
- Kuwait Flat Rate (default: 2 KWD)
- GCC Base Rate (default: 7 KWD)
- GCC Additional Per Kg (default: 2 KWD)
- International Rates

### Edit Payroll Settings
Go to Settings → Payroll tab:
- Overtime Multiplier (default: 1.5)
- Working Days Per Month (default: 26)
- Working Hours Per Day (default: 8)

---

## 🎯 Key URLs

```
Orders List:           /orders
Order Details:         /orders/{id}
Cost Breakdown:        /orders/{id}/cost-breakdown

Products List:         /products
Product Details:       /products/{id}

Reports:
  - Sales:            /reports/sales
  - Inventory:        /reports/inventory
  - Production:       /reports/production
  - Profitability:    /reports/profitability

Accounting:           /accounting
Settings:             /settings
```

---

## 🔍 Troubleshooting

### Issue: Costs Not Calculating
**Solution:**
```bash
php artisan products:recalculate-costs
```

### Issue: WooCommerce Not Syncing
**Check:**
1. `.env` file has correct WooCommerce credentials
2. Run manual sync: `php artisan woocommerce:sync`
3. Check logs: `storage/logs/laravel.log`

### Issue: Scheduler Not Running
**Solution:**
```bash
# Stop current scheduler
# Then restart:
php artisan schedule:work
```

---

## 📝 Notes

- All costs are in KWD (Kuwaiti Dinar)
- Automatic sync runs every 5 minutes
- Observer creates accounting entries automatically
- All changes are non-destructive and backward compatible

---

## 🎉 You're All Set!

The system is running and ready. Just:
1. ✅ Make sure WooCommerce credentials are in `.env`
2. ✅ Ensure scheduler is running (`php artisan schedule:work`)
3. ✅ Create BOMs for products that need costing
4. ✅ Check profitability reports!

**Need Help?** Check `WOOCOMMERCE_INTEGRATION_COMPLETE.md` for full technical documentation.

