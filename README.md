# WordPress-Image-Resize-Advanced
This little snippet allows you to properly resize and crop any image in WordPress. It uses native WordPress `WP_Image_Editor` and resizes image according to it's ratio.

# How to use?
Simply put the entire code in your functions.php file, and call function in your theme as following:<br />
`lt_image_resize('url', width, height)`

Where:
`url` - image address,
`width` - image width (just number),
`height` - image height (just number).
