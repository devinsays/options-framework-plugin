<?php
/**
 * This theme is purely for the purpose of testing to see if theme options are working.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
            
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title">Options Check Theme</h2>
            
            <div class="entry-content">
            <p>Using of_get_option( $name, $default ) to retrieve items from the database.</p>
            
            <h3>Basic Options</h3>
            
            <dl>
            <dt>type: text</dt>
            <dd>get_option( 'example_text'): <?php echo of_get_option( 'example_text', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: text</dt>
            <dd>get_option( 'example_text'): <?php echo of_get_option( 'example_text', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: textarea</dt>
            <dd>get_option( 'example_textarea'): <?php echo of_get_option( 'example_textarea', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: select (mini)</dt>
            <dd>get_option( 'example_select'): <?php echo of_get_option( 'example_select', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: select2 (wide)</dt>
            <dd>get_option( 'example_select_wide'): <?php echo of_get_option( 'example_select_wide', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: radio</dt>
            <dd>get_option( 'example_radio'): <?php echo of_get_option( 'example_radio', 'no entry' ); ?></dd>
            </dl>
            
            <dl>
            <dt>type: checkbox</dt>
            <dd>get_option( 'example_checkbox'): <?php echo of_get_option( 'example_checkbox', 'true' ); ?></dd>
            </dl>
            
             <hr/>
            
            <h3>Advanced Options</h3>
            
            <dl>
            <dt>type: uploader</dt>
            <dd>get_option( 'example_uploader'): <?php echo of_get_option( 'example_uploader', 'no entry' ); ?></dd>
            <?php if ( of_get_option( 'example_uploader', 'false' ) ) { ?>
            <img src="<?php echo of_get_option( 'example_uploader' ) ?>" />
			<?php } ?>
            </dl>
            
            <dl>
            <dt>type: multicheck</dt>
            <dd>get_option( 'example_multicheck'):
            <?php $example_multicheck = of_get_option( 'example_multicheck', 'no entry' ); ?>
			<?php echo $example_multicheck; ?>
            </dd>
            </dl>
            
            <p>You can get all the items that were marked true:</p>
            <ul>
            <?php foreach ($example_multicheck as $mc) {
				echo '<li>' . $mc . '</li>';
			} ?>
            </ul>
            
            <p>Or an individual checkbox in the group by using get_option( 'example_multicheck_$key') where $key is on of the items in the checkbox $options array.</p>
            
            <dl>
            <dt>type: colorpicker</dt>
            <dd>get_option( 'example_colorpicker'):
            <span style="color:<?php echo of_get_option( 'example_colorpicker', '#000' ); ?>">
			<?php echo of_get_option( 'example_colorpicker', 'no entry' ); ?>
            </span>
            </dd>
            </dl>
            
            </div>
			
			</div><!-- #content -->
		</div><!-- #container -->
        
<?php get_footer(); ?>