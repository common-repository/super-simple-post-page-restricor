<?php
/*
Plugin Name: Super Simple Post / Page Restrictor
Plugin URI: https://github.com/arippberger/super-simple-post-page-restrictor
Description: Adds a super simple post / page restriction option
Version: 1.2.1
Author: arippberger
Author URI: http://alecrippberger.com
License: GPL2
*/
/*  Copyright 2014 Alec Rippberger

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! class_exists( 'Super_Simple_Page_Post_Restrictor' ) ) {

	/**
	 * Class Super_Simple_Page_Post_Restrictor
	 */
	class Super_Simple_Page_Post_Restrictor {

		/**
		 * @var mixed|void
		 */
		protected $options;

		/**
		 * @var
		 */
		protected $admin;

		/**
		 * @var
		 */
		protected $current_post_checkbox;

		/**
		 * @var
		 */
		protected $page_unavailable_text;

		/**
		 * Super_Simple_Page_Post_Restrictor constructor.
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'init_frontend' ) );

			//initialize admin
			if ( is_admin() ) {
				$this->admin_includes();
			}

			$this->options = get_option( 'ss_pp_restrictor_option' );

		} // End __construct()

		/**
		 * Init front end
		 */
		public function init_frontend() {

			//hook main function
			add_action( 'the_post', array( $this, 'clean_post' ), 1 );

			//enqueue scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'super_simple_post_restrictor_admin_scripts' ) );

		} // End init()

		/**
		 * Admin includes
		 */
		public function admin_includes() {
			// loads the admin settings page and adds functionality to the order admin
			require_once( 'super-simple-post-page-restrictor-options.php' );
			$this->admin = new Super_Simple_Post_Page_Options();
		}

		/**
		 * Admin scripts
		 */
		public function super_simple_post_restrictor_admin_scripts() {
			wp_enqueue_style( 'chosen-styles', plugin_dir_url( __FILE__ ) . '/assets/css/chosen.min.css' );
			wp_enqueue_script( 'chosen-script', plugin_dir_url( __FILE__ ) . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );
			wp_enqueue_script( 'chosen-init', plugin_dir_url( __FILE__ ) . '/assets/js/chosen-init.js', array(
				'jquery',
				'chosen-script',
			) );
		}

		/**
		 *
		 *
		 * @param $post_object
		 */
		public function clean_post( $post_object ) {

			$restricted_by_default = false;

			$this->options = get_option( 'ss_pp_restrictor_option' );

			//get and store current post checkbox
			$this->current_post_checkbox = get_post_meta( $post_object->ID, 'ss_pp_restrictor_checkbox', true );

			$restrict_by_default_option = isset( $this->options[ 'restrict_by_default' ] ) ? $this->options[ 'restrict_by_default' ] : false;

			if ( $this->current_post_checkbox === '' && $restrict_by_default_option ) {
				$restricted_by_default = true;
			}

			//see if current post type is restricted
			$restricted_post_type = false;
			if ( is_array( $this->options[ 'post_type_select' ] ) ) {
				if ( in_array( $post_object->post_type, $this->options[ 'post_type_select' ] ) ) {
					$restricted_post_type = true;
				}
			}

			//get array of roles that may NEVER access content
			$restricted_roles = isset( $this->options[ 'user_role_select' ] ) ? $this->options[ 'user_role_select' ] : array();

			//get array of current user roles
			$current_user_roles = $this->get_current_user_roles();

			//all users start with access
			$current_user_can_access = true;

			//loop through current user roles and check if any roles are in restricted roles array
			if ( is_array( $current_user_roles ) ) { //first check if is array (settings need to be set)
				foreach ( $current_user_roles as $key => $role ) {
					if ( is_array( $restricted_roles ) && in_array( $role, $restricted_roles ) ) { //if restricted roles is set (is_array()) and current role is in restricted roles
						//restrict access
						$current_user_can_access = false;
					}
				}
			}

			//if current post is restricted and user is not logged in - OR - check if current post is restricted and user can't access
			if (
				$restricted_by_default && ! is_user_logged_in() && $restricted_post_type ||
				$restricted_by_default && $restricted_post_type && ! $current_user_can_access ||
				$this->current_post_checkbox && ! is_user_logged_in() && $restricted_post_type ||
				$restricted_post_type && $this->current_post_checkbox && ! $current_user_can_access
			) {
				add_filter( 'the_content', array( $this, 'filter_content' ) );
				add_filter( 'the_excerpt', array( $this, 'filter_excerpt' ) );
			}

		}

		/**
		 *
		 */
		public function restrict_feed() {
			die( 'this is the rss_head' );
			//add_filter( 'the_content', array( $this, 'filter_feed_content' ) );
		}

		/**
		 * @param $content
		 *
		 * @return string
		 */
		public function filter_feed_content( $content ) {

			if ( is_feed() && $this->current_post_checkbox ) {
				$this->page_unavailable_text = $this->options[ 'page_unavailable_text' ];
				$post_content                = ! empty( $this->page_unavailable_text ) ? $this->page_unavailable_text : 'This content is currently unavailable to you. ';

				return $post_content;
			} else {
				return $content;
			}

		}

		/**
		 * @param $content
		 *
		 * @return string
		 */
		public function filter_content( $content ) {
			if ( $this->current_post_checkbox ) {

				$this->page_unavailable_text = $this->options[ 'page_unavailable_text' ];
				$post_content                = ! empty( $this->page_unavailable_text ) ? $this->page_unavailable_text : 'This content is currently unavailable to you. ';

				return $post_content;
			} else {
				return $content;
			}

		}

		/**
		 * @param $excerpt
		 *
		 * @return string
		 */
		public function filter_excerpt( $excerpt ) {
			if ( $this->current_post_checkbox ) {

				$this->page_unavailable_text = $this->options[ 'page_unavailable_text' ];
				$post_content                = ! empty( $this->page_unavailable_text ) ? $this->page_unavailable_text : 'This content is currently unavailable to you. ';

				return $post_content;
			} else {
				return $excerpt;
			}

		}

		/**
		 * Returns the role of the current user.
		 *
		 * @return array containing the names of the current users role(s)
		 **/
		private function get_current_user_roles() {

			$current_user = wp_get_current_user();
			$roles        = $current_user->roles;

			$translated_roles = array();

			foreach ( $roles as $key => $role ) {
				$translated_roles[] = $role;
			}

			return $translated_roles;
		}

	} // End Class
}
global $super_simple_page_post_restrictor;
$super_simple_page_post_restrictor = new Super_Simple_Page_Post_Restrictor();
