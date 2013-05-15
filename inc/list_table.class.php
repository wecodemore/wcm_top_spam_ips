<?php
defined( 'ABSPATH' ) OR exit;

class TSIListTable extends WP_List_Table
{
	private $data = null;

	public function __construct()
	{
		# Dependency
		$this->data = new TSIData();

		$this->set_order();
		$this->set_orderby();

		parent::__construct( array(
			'singular' => __( 'Spam IP', 'tsi_lang' ),
			'plural'   => __( 'Spam IPs', 'tsi_lang' ),
			'ajax'     => true
		) );

		$this->prepare_items();
		?>
		<form action="" method="post">
			<label for="amount">
				<?php _e( 'Set minimum amount of spam comments', 'tsi_lang' ); ?>
			</label>
			<input
				type="number"
				class="screen-per-page"
				id="amount"
				name="amount"
				value="<?php echo $this->data->get_amount(); ?>"
				step="1"
				min="1"
				max="100"
				maxlength="3" />
			<?php
			submit_button(
				__( 'Set', 'tsi_lang' ),
				'primary',
				null,
				false
			);
			?>
		</form>
		<?php
		$this->display();
	}

	public function set_order()
	{
		$this->order = $this->data->get_order();
	}

	public function set_orderby()
	{
		$this->orderby = $this->data->get_orderby();
	}

	public function ajax_user_can()
	{
		return current_user_can( 'manage_options' );
	}

	public function no_items()
	{
		_e( 'No Spam IPs found.', 'tsi_lang' );
	}

	public function get_columns()
	{
		return array(
		#   'ID'                => 'Title'
			'comment_author_IP' => __( 'IP', 'tsi_lang' ),
			'amount'            => __( 'Amount', 'tsi_lang' ),

		);
	}

	public function get_sortable_columns()
	{
		return array(
		#   'ID'                => 'orderby'
			'comment_author_IP' => array( 'comment_author_IP', true ),
			'amount'            => array( 'amount', true ),

		);
	}

	public function prepare_items()
	{
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array(
			$columns,
			$hidden,
			$sortable,
		);

		$IPs = $this->data->get_sql_result( $this->get_pagenum() );
		empty( $IPs ) AND $IPs = array();

		$total_items  = count( $IPs );
		$per_page = $this->data->get_per_page_value();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );

		return $this->items = $IPs;
	}

	public function column_default( $item, $column_name )
	{
		return 'comment_author_IP' === $column_name
			? sprintf(
				'<a href="http://ip.toscho.de/?ip=%1$s" title="%2$ss" target="_blank">%1$s</a>',
				$item->$column_name,
				__( 'Identify Spammer', 'tsi_lang' )
			)
			: $item->$column_name
		;
	}

	public  function display_tablenav( $which )
	{
		# if ( 'top' == $which )
		# wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<!--
			<div class="alignleft actions">
				<?php # $this->bulk_actions( $which ); ?>
			</div>
			 -->
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>
			<br class="clear" />
		</div>
		<?php
	}

	public function extra_tablenav( $which )
	{
		$views = $this->get_views();
		if ( empty( $views ) )
			return;

		$this->views();
	}
}