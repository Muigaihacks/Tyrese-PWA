# Screenshot & Video Recording Guide for SokoFresh Portfolio

This guide will help you capture professional screenshots and videos of the SokoFresh system for your portfolio.

## Prerequisites

Before starting, make sure you've run:

```bash
php artisan db:demo
```

This ensures all data shown is dummy data (privacy-safe).

## Login Credentials

```
Email: admin@demo.com
Password: demo123
```

## Screenshots to Capture

### 1. Dashboard Overview (Priority: High)
**Filename:** `sokofresh-dashboard.png`

**What to show:**
- Main dashboard with statistics/widgets
- Clean, organized layout
- Data visualizations (if any)
- Navigation menu visible

**Tips:**
- Use full browser width (1920px recommended)
- Make sure no console errors are visible
- Capture during daytime for good lighting if taking screen photos

---

### 2. Inventory Management (Priority: High)
**Filename:** `sokofresh-inventory.png`

**What to show:**
- Inventory list with multiple items
- Search/filter functionality (if visible)
- Item details showing:
  - Product names
  - Item types (tool, spare_part, asset)
  - Quantities
  - Stock levels
  - Conditions

**Tips:**
- Show a populated table with at least 10 items
- Highlight any key features (color coding, badges, etc.)

---

### 3. Visit Scheduling (Priority: High)
**Filename:** `sokofresh-visits.png`

**What to show:**
- List of scheduled visits
- Visit statuses (upcoming, completed, in_progress)
- Date/time information
- Location/unit details
- Assigned users

**Tips:**
- Capture a view with different visit statuses
- Show calendar or date picker if available

---

### 4. Casual Labourers Management (Priority: Medium)
**Filename:** `sokofresh-labourers.png`

**What to show:**
- List of casual labourers
- Personal information (dummy names, phone numbers)
- Status indicators (active/inactive)
- Safety compliance checkboxes

**Tips:**
- Show the table with several labourer records
- Highlight any status badges or indicators

---

### 5. Cold Storage Units (Priority: Medium)
**Filename:** `sokofresh-units.png`

**What to show:**
- List or grid of cold storage units
- Unit details:
  - Unit numbers
  - Locations
  - Capacity
  - Status
  - Monthly fees

**Tips:**
- If there's a map view, capture that too
- Show unit details modal if available

---

### 6. Hub Management (Priority: Medium)
**Filename:** `sokofresh-hubs.png`

**What to show:**
- List of hubs with locations
- Crate and scale counts
- Hub operations data

---

### 7. Battery Monitoring (Priority: Low)
**Filename:** `sokofresh-batteries.png`

**What to show:**
- Battery list with health status
- Maintenance schedules
- Battery details (type, capacity, voltage)

---

### 8. Mobile Responsive View (Priority: Medium)
**Filename:** `sokofresh-mobile.png`

**What to capture:**
- Responsive design on mobile screen size
- Use browser DevTools (F12) → Toggle device toolbar
- iPhone 12 Pro or similar size

---

## Video Recording Tips

### Equipment/Software

**Mac:**
- Use QuickTime Player (built-in)
- Or use CMD + Shift + 5 for screen recording

**Windows:**
- Use Xbox Game Bar (Win + G)
- Or OBS Studio (free, professional)

**Chrome Extension:**
- Loom (great for quick recordings)
- Screencastify

### Video Structure (2-3 minutes max)

**Recommended Flow:**

1. **Introduction (5 seconds)**
   - Show login page
   - Quick login animation

2. **Dashboard Tour (20 seconds)**
   - Pan through the main dashboard
   - Highlight key metrics/widgets

3. **Inventory Management (30 seconds)**
   - Navigate to inventory
   - Show list of items
   - Open item details (if modal available)
   - Show search/filter in action

4. **Visit Scheduling (20 seconds)**
   - Navigate to visits
   - Show calendar or list view
   - Highlight different visit statuses

5. **Labour Management (20 seconds)**
   - Show labourer list
   - Quick scroll through records
   - Show attendance if available

6. **Hub Operations (15 seconds)**
   - Navigate to hubs
   - Show hub list with data
   - Quick view of crate movements

7. **Mobile View (10 seconds)**
   - Resize browser to show responsive design
   - Navigate through 2-3 pages

8. **Conclusion (5 seconds)**
   - Back to dashboard or logout

### Recording Best Practices

1. **Preparation**
   ```bash
   # Clear browser cache and restart servers
   php artisan serve
   npm run dev
   ```

2. **Browser Setup**
   - Use Chrome or Firefox (most professional looking)
   - Hide bookmarks bar (CMD/CTRL + Shift + B)
   - Close unnecessary tabs
   - Open DevTools and close them (ensures consistent window size)
   - Use 100% zoom level

3. **Recording Settings**
   - 1080p resolution minimum (1920x1080)
   - 30 FPS is fine
   - Capture system audio if adding narration

4. **During Recording**
   - Move mouse slowly and deliberately
   - Pause 2-3 seconds on each section
   - Don't click too fast
   - Avoid mistakes (better to restart than edit)

5. **Post-Recording**
   - Trim intro/outro if needed
   - Add fade in/out
   - Consider adding background music (royalty-free)
   - Export as MP4 (H.264 codec)

### Narration Script (Optional)

If you want to add voice-over:

```
"SokoFresh is an enterprise cold storage management system 
built with Laravel and React. 

It features comprehensive inventory tracking across multiple hubs, 
battery health monitoring, and workforce management. 

The system handles complex operations like visit scheduling, 
crate movements, and real-time reporting.

With role-based access control, managers can oversee operations 
while technicians manage day-to-day tasks.

The responsive design works seamlessly on desktop and mobile devices."
```

## Technical Screenshot Settings

### Browser Window Size
- **Desktop:** 1920x1080 (full HD)
- **Tablet:** 768x1024
- **Mobile:** 375x667 (iPhone SE) or 390x844 (iPhone 12)

### Screenshot Tools

**Mac:**
- CMD + Shift + 4: Capture selection
- CMD + Shift + 3: Capture full screen
- CMD + Shift + 4, then Space: Capture window (adds shadow)

**Windows:**
- Snipping Tool (built-in)
- Snagit (professional, paid)

**Browser Extensions:**
- Awesome Screenshot
- Nimbus Screenshot
- Full Page Screen Capture (for long pages)

### Image Editing (Optional)

If you want to polish screenshots:

1. **Crop & Resize**
   - Remove excessive whitespace
   - Maintain 16:9 aspect ratio for consistency

2. **Add Annotations** (use sparingly)
   - Arrow pointing to key features
   - Subtle highlight boxes
   - Text callouts for important elements

3. **Optimize File Size**
   - Use PNG for screenshots (lossless)
   - Use JPG for video thumbnails
   - Compress with TinyPNG.com or similar

## Where to Save Files

### For Portfolio (Next.js)

Save all screenshots to:
```
/Users/user/Documents/GitHub/tyrese-portfolio/public/
```

**Required files:**
- `sokofresh-dashboard.png`
- `sokofresh-inventory.png`
- `sokofresh-visits.png`
- `sokofresh-labourers.png`

**Optional files:**
- `sokofresh-units.png`
- `sokofresh-hubs.png`
- `sokofresh-batteries.png`
- `sokofresh-mobile.png`
- `sokofresh-demo.mp4` (if creating video)

## After Capturing

1. **Review All Assets**
   - Check image quality
   - Ensure no real data is visible
   - Verify consistent sizing

2. **Update Portfolio**
   - Images are already configured in Projects.tsx
   - Just add the files to `/public/` folder

3. **Test Portfolio**
   ```bash
   cd /Users/user/Documents/GitHub/tyrese-portfolio
   npm run dev
   ```
   - Navigate to projects section
   - Click on SokoFresh project
   - Verify images load correctly

4. **Deploy**
   - Commit changes
   - Push to GitHub
   - Vercel/Netlify will auto-deploy

## Checklist

Before you consider the task complete:

- [ ] Database reset with dummy data (`php artisan db:demo`)
- [ ] All required screenshots captured
- [ ] Screenshots saved to portfolio `/public/` folder
- [ ] Images are high quality (1080p or better)
- [ ] No real/sensitive data visible in screenshots
- [ ] Portfolio tested locally
- [ ] Video recorded (optional but recommended)
- [ ] Video edited and exported as MP4
- [ ] All assets committed to Git

## Need Help?

If you encounter issues:

1. **Data doesn't look right:** Re-run `php artisan db:demo`
2. **Images not loading:** Check file names match exactly
3. **Browser looks messy:** Clear cache and restart dev server
4. **Video too large:** Compress with HandBrake (free)

---

**Ready to capture?** Start your servers and begin with the dashboard screenshot!

```bash
# Terminal 1
cd /Users/user/Documents/GitHub/Tyrese-PWA
php artisan serve

# Terminal 2  
cd /Users/user/Documents/GitHub/Tyrese-PWA
npm run dev

# Then open: http://localhost:5173 or http://localhost:8000
```

