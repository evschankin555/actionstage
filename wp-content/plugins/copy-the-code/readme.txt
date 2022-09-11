=== Copy Anything to Clipboard ===
Contributors: Mahesh901122
Donate link: https://www.paypal.me/mwaghmare7/
Tags: Copy to Clipboard, Clipboard, Copy Anything to Clipboard
Tested up to: 5.8
Stable tag: 2.2.2
Requires PHP: 5.6
Requires at least: 4.4

== Description ==

=== Copy Anything to Clipboard: the #1 WordPress Copy to Clipboard plugin

Add a copy button anywhere and simply copy it into the clipboard (📋).

You can copy to clipboard: code snippets, special symbols, discount codes, or anything which you want.

By default, the copy button is added to the `<pre>` tag. You can easily change the `pre` tag selector with any other selector. You can read more [about selector](https://maheshwaghmare.com/doc/copy-anything-to-clipboard/#what-is-the-selector).

Just use the shortcode as:

<pre>
[copy content="100OFF"]
</pre>

Above shortcode display text "Copy" and on click it the text "100OFF" copied into the clipboard.

We can change the "Copy" text with any other text as <code>text="Click Me"</code>.

E.g. <code>[copy text="Click Me" content="100OFF"]</code>

Here we can see the text "Click Me" and on click on it the text "100OFF" copied to the clipboard.

We see the text "Copied" after clicking on the link which indicate user something is copied to clipboard.

See <a href="https://videos.files.wordpress.com/Mo6nR60h/copy-content-with-shortcode_mp4_hd.mp4">quick video</a>.

Or,

Read more at [copy with shortcode](https://maheshwaghmare.com/doc/copy-anything-to-clipboard/#shortcode).

**FREE And Popular Copy to Clipboard Plugin**

Over 6k+ users are empowering their websites with "Copy Anything to Clipboard" – 100% compatible for all themes and plugins.

Reasons why people love the Copy Anything to Clipboard

- Zero configuration
- Easy to use
- Copy with shortcode
- Customization options
- Quick support

**Welcome for featured requests**

If you have any suggestion or any featured request then don't hesitate to [contact](https://maheshwaghmare.com/say-hello/).

=== Bug reports

Bug reports for "Copy Anything to Clipboard" are welcomed in our [repository on GitHub](https://github.com/maheshwaghmare/copy-the-code/). Please note that GitHub is not a support forum, and that issues that are not properly qualified as bugs will be closed.

=== Further reading

For more info check out the following:

* The [maheshwaghmare.com](https://maheshwaghmare.com/) official website.
* The [Contact Me](https://maheshwaghmare.com/say-hello/).
* The [Getting started guide](https://maheshwaghmare.com/doc/copy-anything-to-clipboard/).
* Other my [WordPress Plugins](https://wordpress.org/plugins/search/mahesh901122/).
* Contribute with [development](https://github.com/maheshwaghmare/copy-the-code/).
* Make a [small donation](https://www.paypal.me/mwaghmare7/).

== Image Copyrights

Icons made by <a href="https://www.flaticon.com/authors/pixel-perfect" title="Pixel perfect">Pixel perfect</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a>

== Installation ==

1. Install the <code>Copy Anything to Clipboard</code> plugin either via the WordPress plugin directory, or by uploading the files to your server at <code>wp-content/plugins</code>.

== Changelog ==

= 2.2.2 =

* Improvement: Getting multiple white spaces in Gutenberg editor.
* Fix: The add new link was wrong which navigate to invalid page.

= 2.2.1 =

* Improvement: Move the parent menus as submenu in settings menu.

= 2.2.0 =

* New - Added shortcode [copy] to copy the content. E.g. [copy]12345[/copy]. Read more at https://maheshwaghmare.com/doc/copy-anything-to-clipboard/#shortcode

= 2.1.1 =

* Improvement - Compatibility to WordPress 5.7.

= 2.1.0 =

* New: Added filter `copy_the_code_localize_vars` to allow to copy the content as HTMl instead of text.

= 2.0.0 =

* Tweak: Create a new post copy to clipboard post depends on the old user settings.
* Deprecated: Removed the filter `copy_the_code_enabled` which is no more useful.
* Deprecated: Removed the option `Copy the content` which is no more useful. Will add the support though filter if required.
* New: Added custom post type support to add multiple copy to clipboard buttons with different selectors and styles.
* New: Improve the UI with live preview.
* New: Added the new `SVG Icon` button style to show the SVG icon instead of button.
* New: Added inside and outside position support for the new style SVG Icon.
* New: Added the new `Cover` style to copy the small element in which we could not add the copy button. Such as Emoji and Symbols.

= 1.8.0 =

* New: Set the `Copy Content As` default option with `text`.
* Improvements: Converted the `<br>` tags into the new line if the option "Copy Content As" selected as `Text`.
* Improvements: Converted the `<div>` tags into the new line if the option "Copy Content As" selected as `Text`.
* Improvements: Converted the `<p>` tags into the new line if the option "Copy Content As" selected as `Text`.
* Improvements: Converted the `<li>` tags into the new line if the option "Copy Content As" selected as `Text`.
* Improvements: Remove the white spaces and trim the content if the option "Copy Content As" selected as `Text`.
* Fix: Copy the content as text works different on Chrome, Firefox and Internet Explorer browsers.

= 1.7.5 =

* Fix: The `<br>` tag converted into the next line. Select the `Text` from option `Copy Content As`. Reported by Konrad.
* Fix: Single level selector copies the selector in the clipboard. Reported by Seb.

= 1.7.4 =

* Fix: Nested selectors was not working due to mismatch the copy button position.

= 1.7.3 =

* Fix: The `<br>` tags was not copied as new line.  Reported by @psanger.

= 1.7.2 =

* Improvement: Removed unwanted code.

= 1.7.1 =

* Improvement: Updated Freemius SDK library with version 2.3.2.
* Improvement: Added the latest new section.
* Fix: The submit button is not visible form the settings page. Reported by Nicolas Tizio

= 1.7.0 =

* New: Added General & Style tabs.

= 1.6.1 =

* Improvement: Added WordPress 5.4 compatibility.

= 1.6.0 =

* New: Added filter `copy_the_code_default_page_settings` to change the default page settings.
* New: Added filter `copy_the_code_page_settings` to change the page settings.

= 1.5.0 =

* New: Added option 'Button Text' to set the default button text. Default 'Copy'.
* New: Added option 'Button Copy Text' to set the button text after click on copy. Default 'Copied!'.
* New: Added option 'Button Title' to set the default button title which appear on hover on button. Default 'Copy to Clipboard'.
* New: Added option 'Button Position' to set the button position. Inside or outside the selector. Default 'inside'.
* Improvement: Added support for Internet Explorer devices. Reported by @rambo3000

= 1.4.1 =

* Fix: Added support for IOS devices. Reported by @radiocure1

= 1.4.0 =
* New: Added option 'Copy Content As' to copy the content as either HTML or Text. 

= 1.3.1 =
* Improvement: Updated the strings and compatibility for WordPress 5.0.

= 1.3.0 =
* New: Added support, contact links.

= 1.2.0 =
* New: Added settings page for customizing the plugin. Added option `selector` to set the JS selector. Default its `<pre>` html tag.

= 1.1.0 =
* Fix: Removed `Copy` button markup from the copied content from the clipboard.

= 1.0.0 =
* Initial release.
