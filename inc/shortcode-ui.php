<?php

add_action( 'init', 'berkeley_shortcodes_register' );

function berkeley_shortcodes_register() {
	add_shortcode( 'pullquote', 'berkeley_pullquote_shortcode' );
	add_shortcode( 'site-name', 'berkeley_sitename_shortcode' );
}

function berkeley_sitename_shortcode() {
	return get_option( 'blogname' );
}

add_action( 'register_shortcode_ui', 'berkeley_pullquote_shortcode_ui' );

function berkeley_pullquote_shortcode_ui() {
	/*
	 * Define the UI for attributes of the shortcode. Optional.
	 *
	 * In this demo example, we register multiple fields related to showing a quotation
	 * - Attachment, Citation Source, Select Page, Background Color, Alignment and Year.
	 *
	 * If no UI is registered for an attribute, then the attribute will
	 * not be editable through Shortcake's UI. However, the value of any
	 * unregistered attributes will be preserved when editing.
	 *
	 * Each array must include 'attr', 'type', and 'label'.
	 * * 'attr' should be the name of the attribute.
	 * * 'type' options include: text, checkbox, textarea, radio, select, email,
	 *     url, number, and date, post_select, attachment, color.
	 * * 'label' is the label text associated with that input field.
	 *
	 * Use 'meta' to add arbitrary attributes to the HTML of the field.
	 *
	 * Use 'encode' to encode attribute data. Requires customization in shortcode callback to decode.
	 *
	 * Depending on 'type', additional arguments may be available.
	 */
	$fields = array(
		array(
			'label'  => esc_html__( 'Citation Source', 'shortcode-ui-example' ),
			'attr'   => 'source',
			'type'   => 'text',
			'encode' => true,
			'meta'   => array(
				'placeholder' => esc_html__( 'Source (optional)', 'shortcode-ui-example' ),
				'data-test'   => 1,
			),
		),
		array(
			'label'       => esc_html__( 'Alignment', 'shortcode-ui-example' ),
			'description' => esc_html__( 'Whether the quotation should be displayed as pull-left, pull-right, or neither.', 'shortcode-ui-example' ),
			'attr'        => 'alignment',
			'type'        => 'select',
			'options'     => array(
				''      => esc_html__( 'None', 'shortcode-ui-example' ),
				'left'  => esc_html__( 'Pull Left', 'shortcode-ui-example' ),
				'right' => esc_html__( 'Pull Right', 'shortcode-ui-example' ),
			),
		),
	);
	/*
	 * Define the Shortcode UI arguments.
	 */
	$shortcode_ui_args = array(
		/*
		 * How the shortcode should be labeled in the UI. Required argument.
		 */
		'label' => esc_html__( 'Pull Quote', 'shortcode-ui-example' ),
		/*
		 * Include an icon with your shortcode. Optional.
		 * Use a dashicon, or full URL to image.
		 */
		'listItemImage' => 'dashicons-editor-quote',
		/*
		 * Limit this shortcode UI to specific posts. Optional.
		 */
		//'post_type' => array( 'post' ),
		/*
		 * Register UI for the "inner content" of the shortcode. Optional.
		 * If no UI is registered for the inner content, then any inner content
		 * data present will be backed-up during editing.
		 */
		'inner_content' => array(
			'label'        => esc_html__( 'Quote', 'shortcode-ui-example' ),
			'description'  => esc_html__( 'Insert a stylized quote.', 'shortcode-ui-example' ),
		),
		/*
		 * Define the UI for attributes of the shortcode. Optional.
		 *
		 * See above, to where the the assignment to the $fields variable was made.
		 */
		'attrs' => $fields,
	);
	shortcode_ui_register_for_shortcode( 'pullquote', $shortcode_ui_args );
}


function berkeley_pullquote_shortcode( $attr, $content, $shortcode_tag ) {
	$attr = shortcode_atts( array(
		'source'     => '',
		'alignment'	 => '',
	), $attr, $shortcode_tag );
	
	$align = '';
	if ( !empty( $attr['alignment'] ) )
		$align = 'align' . esc_attr( $attr['alignment'] );

	ob_start(); ?>
	<section class="pullquote <?php echo $align; ?>">
		<blockquote> <?php echo wpautop( wp_kses_post( $content ) ); ?>
		<?php if ( !empty( $attr[ 'source' ] ) ) : ?>
			<cite><?php echo wp_kses_post( urldecode( $attr[ 'source' ] ) ); ?></cite>
		<?php endif; ?>	
		</blockquote>
	</section>

	<?php
	return ob_get_clean();
}