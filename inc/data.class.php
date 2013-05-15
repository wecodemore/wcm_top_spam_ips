<?php
defined( 'ABSPATH' ) OR exit;

final class TSIData
{
	public function get_order()
	{
		$order = 'DESC';
		isset( $_GET['order'] )
		AND $_GET['order']
			AND $order = $_GET['order'];

		return $order;
	}

	public function get_orderby()
	{
		$orderby = 'comment_author_IP';
		isset( $_GET['orderby'] )
		AND $_GET['orderby']
			AND $orderby = $_GET['orderby'];

		return $orderby;
	}

	public function get_amount()
	{
		return isset( $_POST['amount'] )
			? absint( esc_attr( $_POST['amount'] ) )
			: 5
		;
	}

	public function get_sql_result( $pagenum = 1 )
	{
		add_filter( 'comments_clauses', array( $this, 'comments_clauses_cb' ) );
		$query = new WP_Comment_Query();
		$query_args = array(
			'status'  => 'spam',
			'orderby' => $this->get_orderby(),
			'order'   => $this->get_order(),
			'number'  => $this->get_per_page_value(),
		);
		1 < $pagenum
			AND $query_args['offset'] = $pagenum * $this->get_per_page_value();

		$results = $query->query( $query_args );
		$results = wp_list_pluck( $results, 'comment_author_IP' );
		$results = array_count_values( $results );

		$items = array();
		foreach ( $results as $ip => $amount )
		{
			$item = new stdClass();
			$item->amount = $amount;
			$item->comment_author_IP = $ip;
			$items[] = $item;
		}
		$items = array_filter( $items, array( $this, 'filter_comments_by_amount' ) );

		return $items;
	}

	/**
	 * @wp-hook comments_clauses
	 * @param   array $pieces
	 * @return  array $pieces
	 */
	public function comments_clauses_cb( $pieces )
	{
		remove_filter( current_filter(), array( $this, __FUNCTION__ ) );

		$pieces['fields'] = 'comment_author_IP';
		return $pieces;
	}

	public function filter_comments_by_amount( $item )
	{
		return $item->amount >= $this->get_amount();
	}

	public function get_per_page_value()
	{
		$per_page = absint( get_user_option( get_current_screen()->id.'_per_page' ) );

		empty( $per_page )
		XOR 1 > $per_page
			AND $per_page = get_current_screen()->get_option(
				'per_page',
				'default'
			);

		$max = $this->get_per_page_max();
		$per_page > $max
		|| isset( $_GET['export'] ) AND $_GET['export']
			AND $per_page = $max;

		return absint( esc_attr( $per_page ) );
	}

	public function get_per_page_max()
	{
		return get_current_screen()->get_option( 'per_page', 'max' );
	}
}