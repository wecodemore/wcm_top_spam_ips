<?php
defined( 'ABSPATH' ) OR exit;

final class TSIExport
{
	public $data = null;

	public $export_id = 'export-tool-id';

	public function __construct( TSIData $data )
	{
		$this->data = new $data;

		$this->get_message();
		$this->get_textarea();
		$this->get_script();
	}

	public function get_message()
	{
		?>
		<div id="message" class="updated">
			<p><?php
			_e(
				'Use <kbd>CTRL/CMD</kbd>+<kbd>C</kbd> to <strong>copy</strong> the list.',
				'tsi_lang'
			);
			?></p>
		</div>
		<?php
	}

	public function get_textarea()
	{
		$result = $this->data->get_sql_result();
		$result = wp_list_pluck( $result, 'comment_author_IP' );
		?>
		<textarea
			id="<?php echo $this->export_id; ?>"
			name="<?php echo $this->export_id; ?>"
			rows="20"
			cols="40"
			style="width: 100%;"><?php
			echo join( "\n", $result );
		?></textarea>
		<label for="<?php echo $this->export_id; ?>" class="description">
			<?php _e( 'Export: Copy IPs to clipboard.', 'tsi_lang' ); ?>
		</label>
		<?php
	}

	public function get_script()
	{
		?>
		<script type="text/javascript">
			;( function($) {
				$( '#<?php echo $this->export_id; ?>' ).focus().select();
			} )( jQuery );
		</script>
		<?php
	}
}