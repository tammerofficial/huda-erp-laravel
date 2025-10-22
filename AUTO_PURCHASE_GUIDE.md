# 🤖 Auto-Purchase System Guide

## Overview
The Auto-Purchase system automatically creates purchase orders when materials fall below minimum stock levels, ensuring you never run out of critical materials.

---

## 🎯 How It Works

### 1. **Material Configuration**
Each material has the following auto-purchase settings:

- **Auto Purchase Enabled**: Enable/disable automatic purchase for this material
- **Min Stock Level**: The threshold that triggers auto-purchase
- **Auto Purchase Quantity**: How much to order when triggered
- **Supplier**: Which supplier to order from

### 2. **Stock Monitoring**
The system checks stock levels:
- **Automatically**: Daily at 8 AM (alert) and 9 AM (auto-purchase)
- **Manually**: Via "Low Stock Alert" button or command

### 3. **Purchase Order Creation**
When triggered, the system:
1. Groups materials by supplier
2. Creates one PO per supplier
3. Sets status to "pending"
4. Adds auto-purchase note
5. Schedules delivery for 7 days

---

## 📋 Setup Instructions

### Step 1: Configure Material Settings

Go to **Materials → Edit Material**:

```
✅ Auto Purchase Enabled: Yes
📊 Min Stock Level: 5
📦 Auto Purchase Quantity: 50
🏭 Supplier: Select supplier
```

### Step 2: Set Minimum Stock Levels

For each material, determine:
- **Safety stock**: Amount needed for emergencies
- **Lead time**: How long supplier takes to deliver
- **Usage rate**: How fast you consume the material

**Formula:**
```
Min Stock Level = (Daily Usage × Lead Time Days) + Safety Stock
```

**Example:**
- Daily usage: 10 meters
- Lead time: 3 days
- Safety stock: 20 meters
- **Min Stock Level = (10 × 3) + 20 = 50 meters**

### Step 3: Set Auto-Purchase Quantity

Consider:
- **Order frequency**: How often you want to order
- **Storage capacity**: How much you can store
- **Supplier MOQ**: Minimum order quantity
- **Price breaks**: Bulk discount thresholds

**Example:**
- Monthly usage: 200 meters
- Want to order bi-weekly
- **Auto Purchase Qty = 100 meters**

---

## 🔍 Monitoring Low Stock

### Method 1: Low Stock Alert Page

Click **"Low Stock Alert"** button on Materials page to see:

- 📊 Total low stock materials
- 🤖 Auto-purchase enabled count
- 📋 Manual order needed count
- ❌ Materials without supplier
- 📈 Current vs. minimum levels
- 💰 Estimated purchase costs

### Method 2: Manual Command

```bash
# Check only (no orders created)
php artisan stock:check-low

# Check and create orders
php artisan stock:check-low --create-orders
```

### Method 3: Via UI Button

Click **"Auto Purchase"** button to:
- Create POs for all low stock materials with auto-purchase enabled
- Redirect to Purchase Orders page
- Show success message with count

---

## 🤖 Automatic Scheduling

### Daily Schedule:

**8:00 AM** - Stock Check (Alert Only)
```bash
php artisan stock:check-low
```
- Lists materials below minimum
- No purchase orders created
- Useful for morning review

**9:00 AM** - Auto-Purchase
```bash
php artisan stock:check-low --create-orders
```
- Creates purchase orders automatically
- Groups by supplier
- Sets pending status

### Enable Scheduler:

Ensure Laravel Scheduler is running:

```bash
# Development (keeps running)
php artisan schedule:work

# Production (add to cron)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📊 Purchase Order Details

### Auto-Generated PO Format:

```
PO Number: AUTO-PO-20251022-0001
Status: Pending
Expected Delivery: +7 days
Notes: 🤖 Auto-generated purchase order due to low stock levels
```

### PO Grouping:

Materials are grouped by supplier:
- **Same supplier** → One PO with multiple items
- **No supplier** → Separate PO per material
- **Different suppliers** → One PO per supplier

---

## 💡 Best Practices

### 1. Review Auto-POs Before Approving
- Check quantities match current needs
- Verify supplier pricing
- Confirm delivery dates
- Adjust if needed

### 2. Set Realistic Levels
- Don't set too low (frequent stockouts)
- Don't set too high (excess inventory)
- Review and adjust quarterly

### 3. Assign Suppliers
- All materials should have a default supplier
- Keep supplier info updated
- Maintain backup suppliers

### 4. Monitor the System
- Check Low Stock Alert daily
- Review auto-generated POs
- Update settings as usage changes

### 5. Regular Audits
- Weekly: Review low stock materials
- Monthly: Analyze purchase patterns
- Quarterly: Adjust min/max levels

---

## 🔧 Configuration Examples

### High-Volume Material (Silk Fabric)
```
Current Stock: 25 meters
Min Stock Level: 50 meters
Auto Purchase Qty: 200 meters
Supplier: Silk & Satin House
Status: ⚠️ LOW STOCK → Will auto-order 200 meters
```

### Medium-Volume Material (Buttons)
```
Current Stock: 100 pieces
Min Stock Level: 150 pieces
Auto Purchase Qty: 500 pieces
Supplier: Hardware Supplies Co.
Status: ⚠️ LOW STOCK → Will auto-order 500 pieces
```

### Low-Volume Material (Special Beads)
```
Current Stock: 10 cards
Min Stock Level: 5 cards
Auto Purchase Qty: 20 cards
Supplier: Pearl & Bead Traders
Status: ✅ IN STOCK → No action needed
```

---

## 📈 Reports & Analytics

### View Low Stock Summary:
- Navigate to: **Materials → Low Stock Alert**
- See real-time statistics
- Filter by supplier, category, status

### Purchase History:
- Navigate to: **Purchase Orders**
- Filter by: `Notes contains "Auto-generated"`
- Review auto-purchase performance

### Cost Analysis:
- Total estimated cost on Low Stock page
- Compare with manual ordering costs
- Track time saved

---

## ⚠️ Important Notes

### Materials Without Auto-Purchase
If `auto_purchase_enabled = false`:
- Material appears in Low Stock Alert
- No automatic PO is created
- Manual order required
- Shows "Manual" badge

### Materials Without Supplier
If `supplier_id = null`:
- Still creates PO (supplier: NULL)
- Requires manual supplier assignment
- Shows warning badge
- PO status: Pending

### Existing Open POs
The system does NOT check for:
- Existing pending POs for same material
- In-transit deliveries
- May create duplicate orders

**Recommendation**: Check pending POs before approving auto-generated ones

---

## 🚀 Quick Actions

### Enable Auto-Purchase for Material:
1. Go to Materials → Edit Material
2. Check "Enable Auto Purchase"
3. Set Min Stock Level
4. Set Auto Purchase Quantity
5. Select Supplier
6. Save

### Create Manual Purchase Orders:
1. Go to Materials → Low Stock Alert
2. Click "Create Auto Purchase Orders"
3. Review created POs in Purchase Orders page
4. Approve or adjust as needed

### Check Low Stock via Command:
```bash
# Just check
php artisan stock:check-low

# Check and create orders
php artisan stock:check-low --create-orders
```

---

## 📞 Support

For questions or issues with the Auto-Purchase system:
- Review this guide
- Check Low Stock Alert page
- Review generated POs
- Contact system administrator

---

**Last Updated**: October 22, 2025  
**Version**: 1.0  
**System**: Huda ERP - Haute Couture Management System
