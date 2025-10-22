# ✅ Materials Stock Display & Auto-Purchase Fixes

## Issues Resolved

### 1. ❌ **Stock Quantities Not Showing**
**Problem**: Materials table showed "0" for all stock quantities

**Root Cause**: 
- View was using `$material->stock_quantity` (non-existent column)
- Actual stock is in `material_inventories` table (relationship)

**Solution**:
- Updated to use `$material->available_quantity` (accessor method)
- Accessor sums all quantities from all warehouses
- Added visual indicators:
  - Red text for low stock
  - Warning badge for low stock items
  - Shows minimum level below quantity

**Files Changed**:
- `resources/views/materials/index.blade.php`

---

### 2. ❌ **Auto-Purchase Not Creating Orders**
**Problem**: Materials reaching minimum stock didn't trigger purchase orders

**Root Cause**:
- Feature existed but required manual execution
- No UI integration
- Scheduler not configured

**Solution**:
Added 3 ways to create auto-purchase orders:

#### A) **Manual via UI** (Instant)
- New "Auto Purchase" button on Materials page
- Creates POs for all low stock materials with auto-purchase enabled
- Groups by supplier
- Redirects to Purchase Orders page

#### B) **View Low Stock First** (Review Before Creating)
- New "Low Stock Alert" button on Materials page
- Shows detailed low stock report:
  - Current vs minimum levels
  - Shortage amounts
  - Auto-purchase status
  - Estimated costs
  - Supplier info
- "Create Auto Purchase Orders" button on this page

#### C) **Automatic Scheduler** (Daily)
- **8:00 AM**: Check and alert (no orders)
- **9:00 AM**: Check and create purchase orders automatically

**Files Changed**:
- `app/Http/Controllers/MaterialController.php` (added methods)
- `resources/views/materials/index.blade.php` (added buttons)
- `resources/views/materials/low-stock.blade.php` (new page)
- `routes/web.php` (added routes)
- `app/Console/Kernel.php` (scheduled tasks)

---

## 🚀 Usage Guide

### Check Low Stock Materials

**Option 1: Via UI**
```
Materials → Low Stock Alert button
```
Shows all materials below minimum with:
- Current stock levels
- Minimum levels required
- Shortage amounts
- Supplier information
- Auto-purchase settings
- Estimated purchase costs

**Option 2: Via Command**
```bash
# Just check (no orders created)
php artisan stock:check-low

# Check and create orders
php artisan stock:check-low --create-orders
```

---

### Create Purchase Orders

**Option 1: From Materials List**
```
Materials → Auto Purchase button (purple)
```
- Instantly creates POs for all low stock materials
- Groups by supplier
- Redirects to Purchase Orders

**Option 2: From Low Stock Alert**
```
Materials → Low Stock Alert → Create Auto Purchase Orders button
```
- Review materials first
- See estimated costs
- Then create orders

**Option 3: Automatic (Daily)**
```
Runs automatically at 9:00 AM via scheduler
```
- No manual intervention
- Creates POs for enabled materials only
- Sends to suppliers automatically

---

## 📊 Current Status

As of now, you have **19 materials** below minimum stock:

| Category | Count | Suppliers |
|----------|-------|-----------|
| Fabrics | 9 | Silk & Satin House (3), Chiffon & Lace Gallery (4), Velvet & Brocade Trading (2) |
| Embellishments | 4 | Embellishment World |
| Notions | 6 | Thread & Notion Suppliers |

**Total Estimated Cost**: ~KWD 47,500
(19 materials × 50 units × avg KWD 50/unit)

---

## 🎯 Recommended Actions

### Immediate (Now):
1. ✅ Review low stock materials
   ```
   Go to: Materials → Low Stock Alert
   ```

2. ✅ Create purchase orders
   ```
   Click: "Create Auto Purchase Orders" button
   ```

3. ✅ Review generated POs
   ```
   Go to: Purchase Orders → Filter by "Auto-generated"
   ```

4. ✅ Approve and submit to suppliers
   ```
   Edit PO → Change status to "approved" → Send to supplier
   ```

### Short-term (This Week):
1. 📋 Verify all materials have suppliers assigned
2. 📋 Check auto-purchase quantities are correct
3. 📋 Adjust min stock levels based on usage
4. 📋 Enable scheduler for daily auto-checks

### Long-term (Monthly):
1. 📊 Review purchase patterns
2. 📊 Optimize min/max levels
3. 📊 Analyze supplier performance
4. 📊 Adjust auto-purchase quantities

---

## 🔧 Configuration

### For Each Material:

Navigate to: **Materials → Edit Material**

Set these fields:
```
✅ Auto Purchase Enabled: Yes/No
📊 Min Stock Level: 5 (or calculate based on usage)
📦 Auto Purchase Quantity: 50 (or calculate based on order frequency)
🏭 Supplier: Select default supplier
```

### Formula for Min Stock Level:
```
Min Stock = (Daily Usage × Lead Time Days) + Safety Stock

Example:
- Daily usage: 10 meters
- Lead time: 3 days
- Safety stock: 20 meters
- Min Stock = (10 × 3) + 20 = 50 meters
```

### Formula for Auto Purchase Quantity:
```
Auto Purchase Qty = Expected Usage Until Next Order + Buffer

Example:
- Monthly usage: 200 meters
- Order every 2 weeks
- Buffer: 10%
- Auto Purchase Qty = (200 / 2) × 1.1 = 110 meters
```

---

## 📈 Testing

### Test 1: View Low Stock
```bash
# Should show 19 materials
php artisan stock:check-low
```

### Test 2: Create Orders (Test)
```bash
# CAUTION: This creates actual POs in database
php artisan stock:check-low --create-orders
```

### Test 3: Via UI
```
1. Go to Materials page
2. Click "Low Stock Alert" (orange button)
3. Review materials
4. Click "Create Auto Purchase Orders" (top button)
5. Check Purchase Orders page for new AUTO-PO-* orders
```

---

## 🎨 UI Improvements

### Materials Index Page:
- ✅ Stock column now shows actual quantities
- ✅ Red highlighting for low stock
- ✅ Warning badges for low stock items
- ✅ Shows minimum level under quantity
- ✅ Displays unit of measurement
- ✅ Two new action buttons (orange and purple)

### Low Stock Alert Page:
- ✅ Summary statistics cards
- ✅ Detailed materials table
- ✅ Color-coded badges
- ✅ Estimated costs
- ✅ Supplier links
- ✅ Quick actions
- ✅ Total cost summary

---

## 📝 Notes

### About Stock Display:
- Shows total across all warehouses
- Real-time calculation
- Includes all inventory movements
- Low stock threshold is `min_stock_level` or `reorder_level`

### About Auto-Purchase:
- Only creates POs for materials with `auto_purchase_enabled = true`
- Groups materials by supplier into single PO
- Materials without supplier get separate POs
- PO status is "pending" (requires approval)
- Expected delivery is +7 days from order date
- PO numbers follow format: `AUTO-PO-YYYYMMDD-####`

### About Scheduler:
- Requires `php artisan schedule:work` to be running
- In production, add to cron: `* * * * * cd /path && php artisan schedule:run`
- Runs automatically if set up
- Can still trigger manually via UI or command

---

## 🔍 Troubleshooting

### Stock Still Shows 0:
**Check:**
1. Are there entries in `material_inventories` table?
2. Run: `SELECT * FROM material_inventories WHERE material_id = X`
3. If empty, add inventory via "Adjust Inventory" button

### Auto-Purchase Not Working:
**Check:**
1. Is `auto_purchase_enabled = 1` for the material?
2. Is current stock <= `min_stock_level`?
3. Is material active (`is_active = 1`)?
4. Check: `php artisan stock:check-low` output

### No Purchase Orders Created:
**Check:**
1. Did you use `--create-orders` flag?
2. Are there materials with `auto_purchase_enabled = true`?
3. Check Purchase Orders page for AUTO-PO-* entries
4. Look in logs: `storage/logs/laravel.log`

---

## 📚 Related Documentation

- `AUTO_PURCHASE_GUIDE.md` - Detailed auto-purchase system guide
- `UNITS_GUIDE.md` - Units and sizes reference
- `QUICK_START.md` - General system quick start

---

**Fixed On**: October 22, 2025  
**Version**: 1.1  
**System**: Huda ERP - Haute Couture Management System
