# Building the Release ZIP for Holray Units

## Steps

1. **Checkout the live branch:**
   ```bash
   git checkout Tom
   ```

2. **Rename the folder to the official plugin name:**
   ```bash
   mv your-repo holray-units
   ```

3. **Install production dependencies using Composer:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

4. **Create the ZIP file excluding .git folder:**
   ```bash

zip -r holray-units.zip holray-units -x "holray-units/.git/*" "holray-units/scripts/*" "holray-units/*.md"

   ```

5. **Upload the ZIP to GitHub Release:**
   - Go to your repository's Releases page.
   - Create a new release, add a tag (e.g., v1.0.0), and upload `holray-units.zip` under Assets.

### Notes:
- Ensure `vendor/` is included in the ZIP so the plugin works without Composer on the user's server.
- Keep `vendor/` in `.gitignore` for development, but include it in the release ZIP.
