<?php

namespace ACA\BP;

use AC;
use ACA\BP\Editing\Ajax\EditableRows;
use ACA\BP\Editing\Ajax\TableRows;
use ACA\BP\ListScreen\Email;
use ACA\BP\ListScreen\Group;
use ReflectionException;

final class BuddyPress extends AC\Plugin {

	public function __construct( $file ) {
		parent::__construct( $file, 'aca_buddypress' );
	}

	/**
	 * Register hooks
	 */
	public function register() {
		add_action( 'ac/list_screen_groups', [ $this, 'register_list_screen_group' ] );
		add_action( 'ac/list_screens', [ $this, 'register_list_screens' ] );

		add_action( 'ac/column_groups', [ $this, 'register_column_groups' ] );
		add_action( 'acp/column_types', [ $this, 'register_columns' ] );

		add_action( 'ac/table/list_screen', [ $this, 'init_editable_table' ] );

		add_action( 'ac/table_scripts', [ $this, 'table_scripts' ], 1 );
	}

	private function is_bp_list_screen( $list_screen ) {
		return $list_screen instanceof Group ||
		       $list_screen instanceof Email;
	}

	/**
	 * @param AC\ListScreen $list_screen
	 */
	public function table_scripts( AC\ListScreen $list_screen ) {
		if ( ! $this->is_bp_list_screen( $list_screen ) ) {
			return;
		}

		$style = new AC\Asset\Style( 'aca-bp-table', $this->get_location()->with_suffix( 'assets/css/table.css' ) );
		$style->enqueue();
	}

	/**
	 * @param AC\AdminColumns $admin_columns
	 */
	public function register_list_screens( AC\ListScreens $list_screens ) {
		$list_screens->register_list_screen( new ListScreen\Email() )
		             ->register_list_screen( new ListScreen\Group() );
	}

	/**
	 * @param AC\Groups $groups
	 */
	public function register_list_screen_group( AC\Groups $groups ) {
		$groups->register_group( 'buddypress', __( 'BuddyPress' ), 14 );
	}

	/**
	 * @param AC\Groups $groups
	 */
	public function register_column_groups( AC\Groups $groups ) {
		$groups->register_group( 'buddypress', 'BuddyPress', 11 );
	}

	/**
	 * @param AC\ListScreen $list_screen
	 */
	public function init_editable_table( AC\ListScreen $list_screen ) {
		if ( ! $list_screen instanceof Group ) {
			return;
		}

		$table_rows = new TableRows\Groups( new AC\Request(), $list_screen );

		if ( $table_rows->is_request() ) {
			$table_rows->register();
		}

		$editable_rows = new EditableRows\Groups( new AC\Request(), $list_screen->editing() );

		if ( $editable_rows && $editable_rows->is_request() ) {
			$editable_rows->register();
		}
	}

	/**
	 * Add custom columns
	 *
	 * @param AC\ListScreen $list_screen
	 *
	 * @throws ReflectionException
	 */
	public function register_columns( AC\ListScreen $list_screen ) {
		if ( $list_screen instanceof AC\ListScreen\User ) {
			$list_screen->register_column_type( new Column\Profile() );
			$list_screen->register_column_types_from_dir( 'ACA\BP\Column\User' );
		}
	}

}

