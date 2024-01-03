# Persona Picker Plugin for WordPress

The Persona Picker Plugin for WordPress lets you create Personas and then make them behave like tabs on a page so you can serve up customized content to visitors. You get two shortcodes, which when used together, act like a navigation system with panels that show/hide based on the persona you choose. Personas are edited in wp-admin as a custom post type. 

See the demo below:

https://github.com/mayurjobanputra/Personas-Picker-WordPress-Plugin/assets/6332663/7ffaaaac-a5da-45ce-ae30-28006d0e693e


## Description

This plugin allows WordPress users to create and manage a new post type called 'Personas'. It includes standard WordPress fields such as title, content, categories, and tags. A unique feature of this plugin is the ability to output the titles of these Persona posts in a specific format using a shortcode, making it ideal for users who want to enhance their website's interactivity and organization. The plugin also intelligently selects an appropriate URL slug for the 'Personas' post type, ensuring no conflicts with existing site content.

## Installation

1. **Download the Plugin:**
   - Download the zip file of the latest code from GitHub.
   - Unzip the file into your `wp-content/plugins` directory.

2. **Activate the Plugin:**
   - Navigate to the 'Plugins' menu in WordPress.
   - Locate 'Persona Picker Plugin' and click 'Activate'.
   - The plugin checks for the availability of URL slugs and selects the most suitable one. If all potential slugs are taken, the plugin will not activate.

3. **Using the Plugin:**
   - Once activated, the plugin automatically creates the 'Persona' custom post type.
   - You can start adding new Persona posts from the 'Personas' menu in the WordPress admin area.
   - See "Usage" below for the two shortcodes to use and some optional CSS if you like

## Updating the Plugin

When updating the Persona Picker Plugin, follow these steps to ensure a smooth transition:

1. **Backup Your Site:** Always back up your WordPress site before updating the plugin.
2. **Update the Plugin:** Download and install the latest version of the plugin. You should see a message to "replace existing" after uploading the new zip file. One thing to keep in mind when downloading from GitHub. If downloading from the Actions tab in GitHub, you need to extract the zip file locally first and ensure the zip file you upload is exactly named personas-picker.zip. If it's anything else, it makes a mess.
3. **Check for Changes:** After updating, visit the 'Personas' section in your admin area to ensure that everything functions as expected. Pay special attention to the URL structure of the Personas post type.

Note: If the URL slug for 'Personas' has changed in the new version, you might need to refresh your permalink settings. Go to Settings > Permalinks and click 'Save Changes' to update the permalink structure.

## Usage

- **Creating Personas:**
  - Go to 'Personas' -> 'Add New Persona' in your WordPress dashboard to add new Persona posts.
- **Displaying Personas:**
  - Use the shortcode `[persona_picker]` in your posts or pages to display the Persona titles in the specified format.
  - Secondly use the `[persona_content]` shortcode to display the personas. They won't show up on the page until you click one of the titles.
  - Optionally, use the CSS styling with examples shown in styles.css file (in this repo) to make your navigation and tyepout text look like mine.

## Styling

Take a look at the styles.css file in this repo for various examples of how to style the typeout class the navigation
to suit your needs. 

## Contributing

I am always looking for ways to improve this plugin. If you have suggestions or enhancements, feel free to fork this repository and submit a pull request. Your contributions are greatly appreciated!

## Support

For support, please open an issue on the GitHub repository. I'll do my best to address it as quickly as possible.

## License

This plugin is released under the [GPLv2 license](https://www.gnu.org/licenses/gpl-2.0.html).
