# 📏 Units of Measurement & Sizes Guide

## Overview
This document provides a comprehensive guide to all units of measurement and standard sizes used in the Huda ERP system, specifically designed for the fashion and haute couture industry.

---

## 🧵 Available Units

### Count/Quantity Units
| Unit | Arabic | Abbreviation | Usage |
|------|--------|--------------|-------|
| **Piece** | قطعة | pcs | Individual items (buttons, accessories, etc.) |
| **Dozen** | دستة | dz | 12 pieces grouped together |
| **Set** | طقم | set | Complete sets of items |
| **Pair** | زوج | pr | Two matching items |
| **Sheet** | ورقة/قطعة | sht | Flat materials (paper, fabric sheets) |

### Length/Distance Units
| Unit | Arabic | Abbreviation | Usage |
|------|--------|--------------|-------|
| **Meter** | متر | m | Standard length measurement for fabrics |
| **Centimeter** | سنتيمتر | cm | Precise measurements for trims, ribbons |
| **Yard** | ياردة | yd | Alternative fabric measurement unit |

### Weight/Mass Units
| Unit | Arabic | Abbreviation | Usage |
|------|--------|--------------|-------|
| **Kilogram** | كيلوجرام | kg | Heavy materials, bulk items |
| **Gram** | جرام | g | Light materials, beads, sequins |

### Packaging Units
| Unit | Arabic | Abbreviation | Usage |
|------|--------|--------------|-------|
| **Box** | علبة | box | Small containers (buttons, pins) |
| **Carton** | كرتون | ctn | Large containers, shipping boxes |
| **Roll** | لفة | roll | Rolled materials (fabrics, ribbons) |
| **Pack** | حزمة | pack | Pre-packaged bundles |
| **Bottle** | قارورة | btl | Liquids (glue, chemicals) |
| **Can** | علبة معدنية | can | Metal containers |
| **Bundle** | رزمة | bndl | Bundled materials |

### Textile/Fashion Specific Units
| Unit | Arabic | Abbreviation | Usage | Description |
|------|--------|--------------|-------|-------------|
| **Spool** | بكرة خيوط | spl | Threads, yarns | For threads wound on spools |
| **Strand** | خصلة/خيط | str | Pearl strings, individual threads | For items counted by strand (e.g., pearl strings) |
| **Card** | كرت/بطاقة | crd | Small accessories | For items mounted on cards (buttons, beads) |

---

## 💡 Usage Examples

### Example 1: Creating a Material
```
Material: Silk Fabric
Unit: meter
Quantity: 50
Unit Cost: 25.00 KWD
```

### Example 2: Creating a Material (Pearl String)
```
Material: Pearl String (Natural)
Unit: strand
Quantity: 100
Unit Cost: 15.00 KWD
```

### Example 3: BOM Item
```
Product: HCCFW24/25 017
Material: Gold Thread
Quantity: 5
Unit: spool
Unit Cost: 12.50 KWD
Total Cost: 62.50 KWD
```

### Example 4: Small Accessories
```
Material: Pearl Buttons
Unit: card (25 buttons per card)
Quantity: 10
Unit Cost: 8.00 KWD
```

---

## 🎯 Best Practices

1. **Consistency**: Always use the same unit for the same type of material across all records
2. **Precision**: Use smaller units (cm, gram) for precise materials
3. **Documentation**: Document the exact meaning when using ambiguous units (e.g., "Card of 25 buttons")
4. **Pearl Strings**: Use "strand" unit and specify length or count in description
5. **Bulk Orders**: Use appropriate packaging units (carton, bundle) for bulk materials

---

## 🔧 Configuration

All units are defined in `config/units.php` and can be customized as needed.

```php
'units' => [
    'strand' => [
        'name' => 'Strand',
        'name_ar' => 'خصلة/خيط',
        'abbreviation' => 'str',
        'type' => 'textile',
        'description' => 'For individual threads or strings (e.g., pearl strings)',
    ],
    // ... more units
]
```

---

## 📝 Notes for Fashion Industry

### Pearl and Bead Strings
- Use **strand** for pearl strings
- Specify in description: "Pearl strand - 40cm" or "Pearl strand - 50 pieces"

### Fabrics and Textiles
- Primary unit: **meter**
- Alternative: **yard** for international suppliers
- Use **roll** when buying in bulk (e.g., "Roll of 100 meters")

### Threads and Yarns
- Use **spool** for standard thread spools
- Use **strand** for specialty threads or when sold by piece

### Small Accessories
- Use **card** for items mounted on display cards
- Use **pack** for pre-packaged small items
- Use **piece** for individual loose items

---

## 🌐 Multi-language Support

All units display both English and Arabic names in the interface:
- Dropdown: `Meter (متر)`
- Reports: Shows unit based on system language

---

## 🔄 Unit Conversion (Future Feature)

Future versions may include automatic unit conversion:
- Meters ↔ Yards
- Kilograms ↔ Grams
- Dozens ↔ Pieces

---

**Last Updated**: October 2025  
**Version**: 1.0  
**System**: Huda ERP - Haute Couture Management System


---

## 📐 Standard Sizes

### Fabric Widths
Common fabric widths used in the industry:
- **90cm** - Narrow fabrics, specialty materials
- **110cm** - Standard quilting cotton
- **115cm** - Common dress fabrics
- **120cm** - Medium width fabrics
- **140cm** - Wide dress fabrics
- **150cm** - Standard width for most fabrics
- **160cm** - Extra wide fabrics
- **180cm** - Wide specialty fabrics
- **220cm** - Extra wide for home textiles
- **280cm** - Ultra wide for curtains/sheers

### Button Sizes
Button sizes in millimeters and ligne (L):
- **10mm (16L)** - Small shirt buttons
- **12mm (18L)** - Standard shirt buttons
- **15mm (24L)** - Dress shirt buttons
- **18mm (28L)** - Blouse buttons
- **20mm (32L)** - Standard coat buttons
- **23mm (36L)** - Large coat buttons
- **25mm (40L)** - Extra large buttons
- **28mm (44L)** - Statement buttons
- **30mm (48L)** - Decorative buttons

### Zipper Lengths
Standard zipper lengths in centimeters:
- **10-20cm** - Bags, pouches, small items
- **25-40cm** - Dresses, skirts
- **45-60cm** - Jackets, coats
- **70-80cm** - Long coats, evening wear

### Thread Sizes
Thread thickness (higher number = finer thread):
- **Size 20** - Heavy duty, topstitching
- **Size 30** - General sewing, heavier fabrics
- **Size 40** - All-purpose sewing
- **Size 50** - Light fabrics, standard
- **Size 60** - Fine sewing, lightweight fabrics
- **Size 80** - Very fine sewing, delicate fabrics
- **Size 100** - Ultra fine, specialty work

### Pearl & Bead Sizes
Sizes in millimeters:
- **2-4mm** - Small beads, delicate work
- **5-6mm** - Medium beads, standard jewelry
- **8mm** - Large beads, statement pieces
- **10-12mm** - Extra large beads
- **14-16mm** - Statement pearls, decorative

### Ribbon & Trim Widths
Widths in centimeters:
- **0.5-1cm** - Narrow ribbons, edging
- **1.5-2.5cm** - Standard ribbons, belts
- **3-5cm** - Wide ribbons, sashes
- **7-10cm** - Extra wide, decorative

### Garment Sizes
Standard clothing sizes:
- **XXS** - Extra Extra Small
- **XS** - Extra Small
- **S** - Small
- **M** - Medium
- **L** - Large
- **XL** - Extra Large
- **XXL** - Extra Extra Large
- **XXXL** - Triple Extra Large

### Generic Sizes
General size categories:
- **Mini** - Very small items
- **Small** - Small items
- **Medium** - Standard items
- **Large** - Large items
- **Extra Large** - Very large items
- **Standard** - Default/standard size
- **Custom** - Custom made/sized
- **Various** - Mixed/various sizes

---

## 💡 Usage Examples by Category

### For Fabrics:
```
Material: Italian Silk
Size: 150cm (fabric width)
Unit: meter
```

### For Buttons:
```
Material: Pearl Button - White
Size: 15mm (24L)
Unit: piece
```

### For Zippers:
```
Material: Metal Zipper - Gold
Size: 50cm
Unit: piece
```

### For Threads:
```
Material: Polyester Thread - Black
Size: 40 (all-purpose)
Unit: spool
```

### For Pearls:
```
Material: Natural Pearl String
Size: 6mm
Unit: strand
```

### For Ribbons:
```
Material: Satin Ribbon - Gold
Size: 2.5cm
Unit: meter or roll
```

---

## 🎯 Best Practices

1. **Choose Appropriate Size Category**: Select the size category that matches your material type
2. **Leave Blank if Not Applicable**: Not all materials need a size specification
3. **Consistency**: Use the same size format for similar materials
4. **Custom Sizes**: Use "Custom" from Generic Sizes if the exact size isn't listed

---

**Note**: All sizes are stored as the key value (e.g., "150cm", "15mm") for consistency and easy searching.

