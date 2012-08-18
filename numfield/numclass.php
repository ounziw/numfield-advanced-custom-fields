<?php

class Num_field extends acf_Field
{

	/*--------------------------------------------------------------------------------------
	 *
	 *	Constructor
	 *	- This function is called when the field class is initalized on each page.
	 *	- Here you can add filters / actions and setup any other functionality for your field
	 *
	 *	@author Elliot Condon
	 *	@since 2.2.0
	 * 
	 *-------------------------------------------------------------------------------------*/

	function __construct($parent)
	{
		// do not delete!
		parent::__construct($parent);

		// set name / title
		$this->name = 'HTML5Number'; // variable name (no spaces / special characters / etc)
		$this->title = __("HTML5 Number",'acf'); // field label (Displayed in edit screens)

	}


	/*--------------------------------------------------------------------------------------
	 *
	 *	create_options
	 *	- this function is called from core/field_meta_box.php to create extra options
	 *	for your field
	 *
	 *	@params
	 *	- $key (int) - the $_POST obejct key required to save the options to the field
	 *	- $field (array) - the field object
	 *
	 *	@author Elliot Condon
	 *	@since 2.2.0
	 * 
	 *-------------------------------------------------------------------------------------*/

	function create_options($key, $field)
	{
		$field['max'] = isset($field['max']) ? $field['max'] : 100;
		$field['min'] = isset($field['min']) ? $field['min'] : 0;
		$field['step'] = isset($field['step']) ? $field['step'] : 1;
		$field['default_value'] = isset($field['default_value']) ? $field['default_value'] : 0;
?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("max",'acf'); ?></label>
				<p class="description"><?php _e("max should be no less than min",'acf'); ?></p>
			</td>
			<td>
<?php 
		$this->parent->create_field(array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][max]',
			'value'	=>	$field['max'],
		));
?>
				<!-- <p class="alert" id="alert_max"></p> TODO -->
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("min",'acf'); ?></label>
				<p class="description"><?php _e("min should be no more than max",'acf'); ?></p>
			</td>
			<td>
<?php 
		$this->parent->create_field(array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][min]',
			'value'	=>	$field['min'],
		));
?>
				<!-- <p class="alert" id="alert_min"></p> TODO -->

			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("step",'acf'); ?></label>
				<p class="description"><?php _e("step should be positive",'acf'); ?></p>
			</td>
			<td>
<?php 
		$this->parent->create_field(array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][step]',
			'value'	=>	$field['step'],
		));
?>
				<!-- <p class="alert" id="alert_step"></p> TODO -->
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Default Value",'acf'); ?></label>
				<p class="description"><?php _e("Default Value should be between min and max",'acf'); ?></p>
			</td>
			<td>
<?php 
		$this->parent->create_field(array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][default_value]',
			'value'	=>	$field['default_value'],
		));
?>
				<!-- <p class="alert" id="alert_default_value"></p> TODO -->
			</td>
		</tr>
<?php 
	}




	/*--------------------------------------------------------------------------------------
	 *
	 *	create_field
	 *	- this function is called on edit screens to produce the html for this field
	 *
	 *	@author Elliot Condon
	 *	@since 2.2.0
	 * 
	 *-------------------------------------------------------------------------------------*/

	function create_field($field)
	{
		$field['max'] = floatval($field['max']);
		$field['min'] = floatval($field['min']);
		$field['step'] = abs(floatval($field['step']));

		echo '<input size="10" type="number" min="'.$field['min'].'" max="'.$field['max'].'" step="'.$field['step'].'" name=' . $field['name'] . ' id=' . $field['name'] . ' value="' . $field['value'] . '" class="number">';
		echo '<input type="range"  min="'.$field['min'].'" max="'.$field['max'].'" step="'.$field['step'].'" value="' . $field['value'] . '" id="val2" class="slider" name="' . $field['name'] . '" />';
?>
<?php
		$add_backslash = function ($data) {
			$data = str_replace('[', '\\\\[', $data);
			$data = str_replace(']', '\\\\]', $data);
			return $data;   
		};
?>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#<?php echo $add_backslash($field['name']);?>').change(function(){
				var value = jQuery(this).val();
				jQuery('#val2').val(value);
			});
			jQuery('#val2').change(function(){
				var value = jQuery(this).val();
				jQuery('#<?php echo $add_backslash($field['name']);?>').val(value);
			});
		});
		</script>	
<?php 
	}

	/*--------------------------------------------------------------------------------------
	 *
	 *	pre_save_field
	 *	- called just before saving the field to the database.
	 *
	 *	@author Elliot Condon
	 *	@since 2.2.0
	 * 
	 *-------------------------------------------------------------------------------------*/


	static function rounding($field) {
		// force min <= max
		$field['max'] = floatval($field['max']);
		$field['min'] = floatval($field['min']);
		if ($field['min'] > $field['max']) {
			$field['min'] = $field['max'];
		}
		// force step > 0
		$field['step'] = abs(floatval($field['step']));
		// force min <= default <= max
		$field['default_value'] = floatval($field['default_value']);
		if ($field['default_value'] < $field['min'] || $field['default_value'] > $field['max']) {
			$field['default_value'] = $field['min'];
		}
		return $field;
	}
	function pre_save_field($field)
	{
		// TODO: need to check at admin page (by javascript)
		return self::rounding($field);
	}


	function admin_print_styles()
	{
		$num_admin_css = '<style type="text/css">input.number {width:20%!important;} .slider {vertical-align:middle;}</style>';
		echo apply_filters('num_admin_css', $num_admin_css);
	}

	function admin_print_scripts()
	{
		// javasccript for browsers which does not support html5
		// http://frankyan.com/labs/html5slider/
		$num_html5slider_dir = apply_filters('num_html5slider_dir', WP_PLUGIN_DIR . '/numfield/');
		wp_register_script( 'html5slider', $num_html5slider_dir . 'html5slider.js' );
		wp_enqueue_script(array(
			'html5slider'
		));	
	}
}
?>
