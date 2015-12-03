<?php
/**
 * Genesis Automatic Term Headline
 *
 * @package           Genesis_Automatic_Term_Headline
 * @author            Gary Jones
 * @license           GPL-2.0+
 * @link              https://github.com/GaryJones/genesis-automatic-term-headline
 * @copyright         2013 Gamajo Tech
 *
 * @wordpress-plugin
 * Plugin Name:       Genesis Automatic Term Headline
 * Plugin URI:        https://github.com/GaryJones/genesis-automatic-term-headline
 * Description:       Automatically adds a headline to the term archive page, the same as the name of taxonomy term, if no explicit value is given.
 * Version:           1.2.0
 * Author:            Gary Jones
 * Author URI:        https://gamajo.com/
 * Text Domain:       genesis-automatic-term-headline
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/GaryJones/genesis-automatic-term-headline
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'genesis_before', 'genesis_automatic_term_headline_remove_taxonomy_title_description' );
/**
 * Remove the existing headline and intro text.
 *
 * @since 1.1.0
 */
function genesis_automatic_term_headline_remove_taxonomy_title_description() {
	remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
}

add_action( 'genesis_before_loop', 'genesis_automatic_term_headline_do_taxonomy_title_description', 15 );
/**
 * Change the behaviour of the default term headline, by making the default the name of the term.
 *
 * If the headline has been customised in some way, then this will show instead.
 *
 * @since 1.0.0
 *
 * @return null Return early if not the correct archive page, not page one, or no term meta is set.
 */
function genesis_automatic_term_headline_do_taxonomy_title_description() {

	global $wp_query;

	if ( ! genesis_automatic_term_headline_is_term_archive_first_page() ) {
		return;
	}

	$term = genesis_automatic_term_headline_get_term();

	if ( genesis_automatic_term_headline_is_valid_term( $term ) ) {
		return;
	}

	$headline_text = genesis_automatic_term_headline_get_headline_text( $term );

	$headline = sprintf( '<h1 class="archive-title">%s</h1>', strip_tags( $headline_text ) );

	$intro_text = genesis_automatic_term_headline_get_intro_text( $term );
	?>
	<div class="archive-description taxonomy-description"><?php echo $headline . $intro_text; ?></div>
	<?php
}

/**
 * Check if viewing first page of a category, tag or custom taxonomy term archive.
 *
 * @since 2.0.0
 *
 * @return bool True if on the first page of a term archive, false otherwise.
 */
function genesis_automatic_term_headline_is_term_archive_first_page() {
	if ( ( is_category() || is_tag() || is_tax() ) && ! get_query_var( 'paged' ) >= 2 ) {
		return true;
	}

	return false;
}

/**
 * Get term for current archive.
 *
 * Taxonomy terms and category / tag terms work slightly differently.
 *
 * @since 2.0.0
 *
 * @return string Term for this archive.
 */
function genesis_automatic_term_headline_get_term() {
	if ( is_tax() ) {
		return get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	}

	global $wp_query;

	return $wp_query->get_queried_object();
}

/**
 * Check if term exists and has meta values.
 *
 * @since 1.0.0
 *
 * @param object $term Term object.
 * @return bool False if term is falsy or has no meta property. True otherwise.
 */
function genesis_automatic_term_headline_is_valid_term( $term ) {
	if ( ! $term || ! isset( $term->meta ) ) {
		return false;
	}

	return true;
}

/**
 * Get term headline text.
 *
 * By default, the headline text is the term name.
 * 
 * If a custom headline text has been set, then use that instead.
 *
 * If `genesis-automatic-term-headline-exclusion` filter has been set as true,
 * then exclude the term headline and use an empty string.
 *
 * @since 1.0.0
 *
 * @param object $term Term object.
 * @return string Term headline.
 */
function genesis_automatic_term_headline_get_headline_text( $term ) {
	$headline_text = $term->name;

	if ( $term->meta['headline'] ) {
		$headline_text = $term->meta['headline'];
	} elseif ( apply_filters( 'genesis-automatic-term-headline-exclusion', false, $term ) ) {
		$headline_text = '';
	}

	return $headline_text;
}

/**
 * Get intro text for a term.
 *
 * If it exists, apply Genesis filter.
 *
 * @since 1.0.0
 *
 * @param object $term Term object.
 * @return string Term intro text.
 */
function genesis_automatic_term_headline_get_intro_text( $term ) {
	if ( $term->meta['intro_text'] ) {
		return apply_filters( 'genesis_term_intro_text_output', $term->meta['intro_text'] );
	}
	
	return '';
}
