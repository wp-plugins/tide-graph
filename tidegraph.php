<?php
/**
 * Plugin Name: Tide Graph
 * Description: Displays tides
 * Version: 0.1
 * Author: Bryan Aamot (bryanaamot)
 * Author URI: http://www.brainware.net/
 */


add_action( 'widgets_init', 'init_tidegraph' );


function init_tidegraph() {
	register_widget( 'Tide_Graph' );
}

class Tide_Graph extends WP_Widget {

	function Tide_Graph() {
		$widget_ops = array( 'classname' => 'tidegraph', 'description' => __('Displays tides ', 'tidegraph') );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'tidegraph-widget' );
		$this->WP_Widget( 'tidegraph-widget', __('Tide Graph Widget', 'tidegraph'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$background = $instance['background'];
		$scale = (float)$instance['scale'];
		$station = strip_tags($instance['location']);
		$latitude = (float)$instance['latitude'];
		$longitude = (float)$instance['longitude'];

		echo $before_widget;

		// Display the widget title 
//		echo $before_title . 'Tide Graph' . $after_title;

		$src = 'http://tides.tidegraph.com/api/tidegraph.php?bg='.urlencode($background).'&amp;scale='.urlencode($scale);

		if ($latitude != 0 && $longitude != 0)
			$src .= '&amp;lat='.urlencode($latitude).'&amp;lng='.urlencode($longitude);
		else if ($station != '')
			$src .= '&amp;station='.urlencode($station);
		
//		echo $src;
		echo '<script type="text/javascript" src="'.$src.'"></script>';
		
		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags to remove HTML 
		$instance['background'] = strip_tags( $new_instance['background'] );
		$instance['scale'] = strip_tags( $new_instance['scale'] );
		$instance['location'] = $new_instance['location'];
		$instance['latitude'] = $new_instance['latitude'];
		$instance['longitude'] = $new_instance['longitude'];
//		&lat=25.75&lng=-80.2

		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'background' => __('#FFFFFF', 'tidegraph'), 'scale' => __('1.0', 'tidegraph'), 'location' => __('Avalon, Santa Catalina Island, California', 'tidegraph'), 'latitude' => __('', 'tidegraph'), 'longitude' => __('', 'tidegraph'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		Optional Settings:
		<table>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'background' ); ?>"><?php _e('Background:', 'tidegraph'); ?></label>
				</td>
				<td>
					<input style="width:80px" id="<?php echo $this->get_field_id( 'background' ); ?>" name="<?php echo $this->get_field_name( 'background' ); ?>" value="<?php echo $instance['background']; ?>" style="width:100%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'scale' ); ?>"><?php _e('Scale:', 'tidegraph'); ?></label>
				</td>
				<td>
					<input style="width:30px" id="<?php echo $this->get_field_id( 'scale' ); ?>" name="<?php echo $this->get_field_name( 'scale' ); ?>" value="<?php echo $instance['scale']; ?>" style="width:100%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php _e('Location:', 'tidegraph'); ?></label>
				</td>
				<td>
					<input style="width:150px" id="<?php echo $this->get_field_id( 'location' ); ?>" name="<?php echo $this->get_field_name( 'location' ); ?>" value="<?php echo $instance['location']; ?>" style="width:100%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'latitude' ); ?>"><?php _e('Latitude:', 'tidegraph'); ?></label>
				</td>
				<td>
					<input style="width:50px" id="<?php echo $this->get_field_id( 'latitude' ); ?>" name="<?php echo $this->get_field_name( 'latitude' ); ?>" value="<?php echo $instance['latitude']; ?>" style="width:100%;" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'longitude' ); ?>"><?php _e('Longitude:', 'tidegraph'); ?></label>
				</td>
				<td>
					<input style="width:50px" id="<?php echo $this->get_field_id( 'longitude' ); ?>" name="<?php echo $this->get_field_name( 'longitude' ); ?>" value="<?php echo $instance['longitude']; ?>" style="width:100%;" />
				</td>
			</tr>
		</table>
		<div style="font-size:12px; font-style:italic">Note: You can get the location name by picking a location from the widget and copying in the text.</div>
	<?php
	}
}

?>