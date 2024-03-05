<?php

namespace OKLab_Klimawidget\Rendering;

class OKLab_Klimawidget_Klimawidget_Block {

	/**
	 * The single class instance.
	 *
	 * @var $instance
	 */
	private static $instance = null;

	public function __construct() {}

	/**
	 * Main Instance
	 * Ensures only one instance of this class exists in memory at any one time.
	 */
	public static function callback( $attributes, $content ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			return self::$instance->rendering( $attributes, $content );
		} else {
			return self::$instance->rendering( $attributes, $content );
		}
	}

	public function rendering( $attributes, $content ) {
		$dataset         = $attributes['dataset'];
		$title           = $attributes['title'];
		$dataset_options = $attributes['datasetOptions'];
		$energy_source   = $dataset_options['energySource'];
		$property        = $dataset_options['property'];
		$interval        = $dataset_options['interval'];

		$json = new \stdClass();

		$options                  = new \stdClass();
		$options->responsive      = true;
		$options->legend_position = 'top';
		$options->title_display   = ! empty( $title ) ? true : false;
		$options->title_text      = ! empty( $title ) ? $title : '';

		$dataset1                   = new \stdClass();
		$dataset1->label            = ! empty( $dataset['label'] ) ? $dataset['label'] : '';
		$dataset1->data             = ! empty( $dataset['data'] ) ? $dataset['data'] : array();
		$dataset1->background_color = 'rgba(255, 99, 132, 0.5)';

		$labels = ! empty( $dataset['x'] ) ? $dataset['x'] : array();

		$json->propery       = $property;
		$json->energy_source = $energy_source;
		$json->interval      = $interval;
		$json->options       = $options;
		$json->labels        = $labels;
		$json->dataset1      = $dataset1;

		$uid = wp_unique_id( 'oklab_klimawidget_' );

		$inline = 'var ' . $uid . ' = ' . wp_json_encode( $json );

		$content .= '<div class="klimawidget" data-uid="' . $uid . '">';
		$content .= '<canvas id="' . $uid . '"></canvas>' . "\n";
		$content .= sprintf( "<script>%s</script>\n", $inline );
		$content .= '</div>';

		return $content;
	}
}



