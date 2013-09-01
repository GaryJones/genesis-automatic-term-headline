# Genesis Automatic Term Headline

Automatically adds a headline to the term archive page, the same as the name of taxonomy term, if no explicit value is given.

## Description 

In Genesis, the headline on the term archive page only appears if a value is given. This plugin changes that behaviour so that the default value is the name of the term itself, instead of an empty string.

The field on the Edit Term page will still be empty, so if you enter a custom headline, this value will be used instead.

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:GaryJones/genesis-automatic-term-headline.git`

Then go to your Plugins screen and click __Activate__.

## Usage

Just activate the plugin, and the term headlines will automatically appear.

### Selective Criteria

If you want to be a bit more selective of which taxonomies the plugin affects, you can use the `genesis-automatic-term-headline-exclusion` filter. It's a boolean filter, where any truthy value will stop the term headline from appearing. To not add the term headlines for post tags, for categories begining with _Test_, or the archive page for the flavour taxonomy with the slug of 'mild', for instance, you can do:

~~~php
add_filter( 'genesis-automatic-term-headline-exclusion', 'prefix_automatic_term_headline_exclusion' );
/**
 * Remove automatic term headlines for specific conditions.
 *
 * @param  bool $return Existing value, originally false.
 *
 * @return bool Return true if the condition should be excluded from showing automatic term headlines, false otherwise.
 */
function prefix_automatic_term_headline_exclusion( $return ) {
	if ( is_tag() || 'Test' === substr( single_cat_title( '', false ), 0, 4 ) || is_tax( 'flavour', 'mild') )
		return true;
	return false;
}
~~~

## Credits

Built by [Gary Jones](https://twitter.com/GaryJ)  
Copyright 2013 [Gamajo Tech](http://gamajo.com/)
