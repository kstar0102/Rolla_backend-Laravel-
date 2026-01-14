# Terms and Conditions Formatting Guide

## Issues Fixed in Frontend

1. ✅ **HTML Rendering**: Added `flutter_html` package to properly render HTML content
2. ✅ **Bullet Points**: Now displays bullet points from `<ul>` and `<li>` HTML tags
3. ✅ **Bold Text**: Section titles and bold text now display correctly
4. ✅ **Full Content**: Content is displayed in full (no truncation)

## How to Format Content in Admin Panel

### For Proper Display (Bullet Points & Bold Text):

1. **Set Content Type to "HTML"** in the admin panel
2. **Use HTML Format** when entering content:

### Example HTML Format:

```html
<h2>1. Our Service</h2>
<p>Rolla provides a travel-centric social media platform that enables users to:</p>
<ul>
  <li>Create, upload, and share <strong>travel maps</strong> (real-time or retroactive)</li>
  <li>Upload <strong>photos, captions, and other media</strong> to their maps</li>
  <li>Build a personal profile, including:
    <ul>
      <li>Favorite place(s) to travel</li>
      <li>Vehicle manufacturer logo representing the car they drive</li>
    </ul>
  </li>
  <li>View, follow, and interact with the maps and content of others</li>
  <li>Explore travel content shared by the Rolla community</li>
</ul>

<h2>2. Eligibility</h2>
<p>You must be:</p>
<ul>
  <li><strong>At least 13 years old</strong></li>
  <li>Legally capable of entering into a binding agreement</li>
  <li>Not prohibited from using our Service based on applicable laws or previous account bans</li>
</ul>
```

### HTML Tags to Use:

- **Section Titles**: Use `<h2>` or `<h3>` tags (will be bold automatically)
  - Example: `<h2>1. Our Service</h2>`
  
- **Bullet Points**: Use `<ul>` (unordered list) and `<li>` (list item) tags
  - Example:
    ```html
    <ul>
      <li>First item</li>
      <li>Second item</li>
    </ul>
    ```

- **Bold Text**: Use `<strong>` or `<b>` tags
  - Example: `<strong>At least 13 years old</strong>`

- **Paragraphs**: Use `<p>` tags for regular text
  - Example: `<p>This is a paragraph.</p>`

### Converting from Word Document:

If you have a Word document:
1. Copy the content from Word
2. Paste into a text editor that supports HTML conversion
3. Or manually add HTML tags:
   - Replace bullet points with `<ul><li>...</li></ul>`
   - Make section titles bold with `<h2>` or `<h3>`
   - Wrap paragraphs in `<p>` tags

### Important Notes:

- ✅ Content Type must be set to **"HTML"** for formatting to work
- ✅ Use proper HTML tags (`<ul>`, `<li>`, `<h2>`, `<strong>`, etc.)
- ✅ The content field has no character limit - full content will be displayed
- ✅ All content is scrollable in the app

## Troubleshooting

If content is not displaying correctly:
1. Check that Content Type is set to "HTML" (not "Text")
2. Verify HTML tags are properly formatted (opening and closing tags)
3. Check the content in the database to ensure it's complete
4. Clear app cache and reload
