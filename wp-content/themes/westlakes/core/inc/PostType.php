<?php
namespace App;


class PostType {

	protected $site;

	public function __construct($site) {
		$this->site = $site;
		$this->register();
	}

	protected function register(){
		foreach ($this->site->configs['types'] as $type) {
            if (!post_type_exists($type['name']) && $type['name'] != 'post') {
                $this->registerType($type);
            }
			if (!empty($type['fields'])) {
			    $fields = [];
			    $postType = $type['name'];
				foreach ( $type['fields'] as $key => $field ) {
					if ( $key === 'gallery' ) {
						$this->registerGallery( $type );
					} else {
					    $default = !empty($field['default'] ) ? $field['default'] : '';
                        $fields[] = array (
                            'key' => $key,
                            'label' => $field['label'],
                            'name' => $key,
                            'type' => $field['type'],
                            'default_value' => $default,
                        );
					    //$this->registerField( $key, $field['label'], $type['name'], $field['type'],$default  );
					}
				}
				if (!empty($fields)) {
                    if( function_exists('acf_add_local_field_group') ):
                        acf_add_local_field_group(array(
                            'key' => 'group_'.$postType,
                            'title' => " Custom Field",
                            'fields' => $fields,
                            'location' => array (
                                array (
                                    array (
                                        'param' => 'post_type',
                                        'operator' => '==',
                                        'value' => $postType,
                                    ),
                                ),
                            ),
                        ));

                    endif;
                }
			}
			if (!empty($type['category'])) {
				$this->registerTaxonomy($type);
			}
		}
	}

	protected function registerType($type) {
		$labelTypes = array(
			'name' => _x($type['label'], 'post type general name', 'lapsupply'),
			'singular_name' => _x($type['label'], 'post type singular name', 'lapsupply'),
			'menu_name' => _x($type['label'], 'admin menu', 'lapsupply'),
			'name_admin_bar' => _x($type['label'], 'add new on admin bar', 'lapsupply'),
			'add_new' => _x('Thêm mới', 'gallery', 'lapsupply'),
			'add_new_item' => __('Thêm mới '.$type['label'], 'lapsupply'),
			'new_item' => __('Thêm '.$type['label'], 'lapsupply'),
			'edit_item' => __('Sửa '.$type['label'], 'lapsupply'),
			'view_item' => __('Xem '.$type['label'], 'lapsupply'),
			'all_items' => __('Tất cả '.$type['label'], 'lapsupply'),
			'search_items' => __('Tìm '.$type['label'], 'lapsupply'),
			'parent_item_colon' => __('Parent'.$type['label'].':', 'lapsupply'),
			'not_found' => __('No item found.', 'lapsupply'),
			'not_found_in_trash' => __('No item found in Trash.', 'lapsupply'),
		);
		$args = array(
			'labels' => $labelTypes,
			'description' => __('Description.', 'lapsupply'),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'rewrite' => array('slug' => $type['slug']),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => true,
			'menu_position' => null,
            'menu_icon' => !empty($type['menu']) ? $type['menu'] : 'dashicons-images-alt2',
			'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		);
		register_post_type($type['name'], $args);
	}

	protected function registerTaxonomy($type) {
		$labels = array(
			'name' => _x('Danh mục '.$type['label'], 'taxonomy general name', 'labsupply'),
			'singular_name' => _x('Danh mục', 'taxonomy singular name', 'labsupply'),
			'search_items' => __('Tìm Danh mục', 'labsupply'),
			'all_items' => __('Tất cả Danh mục', 'labsupply'),
			'parent_item' => __('Parent Danh mục', 'labsupply'),
			'parent_item_colon' => __('Parent Danh mục:', 'labsupply'),
			'edit_item' => __('Sửa Danh mục', 'labsupply'),
			'update_item' => __('Cập nhật Danh mục', 'labsupply'),
			'add_new_item' => __('Thêm mới Danh mục', 'labsupply'),
			'new_item_name' => __('Tên Danh mục mới', 'labsupply'),
			'menu_name' => __('Danh mục', 'labsupply'),
		);
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => $type['category']['slug']),
		);
		register_taxonomy($type['category']['name'], array($type['name']), $args);
		foreach ($type['category']['fields'] as $key => $field) {
			$this->registerFieldTaxonomy($key, $field['label'], $type['category']['name'], $field['type']);
		}
	}

	protected function registerGallery($type) {
		$postTypeKey = $type['name'].'_image_gallery';
		$metaPostTypeKey = '_'.$postTypeKey;
		add_action('save_post', function ($post_id, $post) use ($postTypeKey, $metaPostTypeKey) {
			$attachment_ids = isset($_POST[$postTypeKey]) ? array_filter(explode(',', $_POST[$postTypeKey])) : [];
			update_post_meta($post_id, $metaPostTypeKey, implode(',', $attachment_ids));
		}, 1, 2);
		add_action('add_meta_boxes', function () use ($postTypeKey, $metaPostTypeKey, $type) {
			$postTypeTitle =  __('Bộ sưu tập hình Sản Phẩm', 'product');
			add_meta_box('woocommerce-'.$type['name'].'-images', $postTypeTitle, function ($post) use ($postTypeKey, $metaPostTypeKey) {
				if (metadata_exists('post', $post->ID, $metaPostTypeKey)) {
					$meta_image_gallery = get_post_meta($post->ID, $metaPostTypeKey, true);
				} else {
					// Backwards compat
					$attachment_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0');
					$attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
					$meta_image_gallery = implode(',', $attachment_ids);
				}
				$attachments = array_filter(explode(',', $meta_image_gallery));
				$update_meta = false;
				$updated_gallery_ids = array();
				echo '<div id="meta_images_container"><ul class="meta_images">';
				if (!empty($attachments)) {
					foreach ($attachments as $attachment_id) {
						$attachment = wp_get_attachment_image($attachment_id, 'thumbnail');
						if (empty($attachment)) {
							$update_meta = true;
							continue;
						}
						echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '">' . $attachment . '<ul class="actions"><li><a href="#" class="delete tips" data-tip="' . esc_attr__('Delete image', 'woocommerce') . '">' . __('Delete', 'woocommerce') . '</a></li></ul></li>';
						$updated_gallery_ids[] = $attachment_id;
					}
					// need to update product meta to set new gallery ids
					if ($update_meta) {
						update_post_meta($post->ID, $metaPostTypeKey, implode(',', $updated_gallery_ids));
					}
				}
				echo "</ul>";
				echo '<input type="hidden" class="meta_gallery_input" id="'.$postTypeKey.'" name="'.$postTypeKey.'" value="' . esc_attr($meta_image_gallery) . '" />';
				echo '</div><p class="add_meta_images hide-if-no-js"><a href="#" data-choose="Add Images to Product Gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete">Thêm tất cả hình ảnh</a></p>';
			}, $type['name'], 'side', 'low');

			add_meta_box('woocommerce-'.$type['name'].'-images', __('Bộ sưu tập ảnh', 'gallery'), function ($post) use ($postTypeKey, $metaPostTypeKey) {

				if (metadata_exists('post', $post->ID, $metaPostTypeKey)) {
					$meta_image_gallery = get_post_meta($post->ID, $metaPostTypeKey, true);
				} else {
					// Backwards compat
					$attachment_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0');
					$attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
					$meta_image_gallery = implode(',', $attachment_ids);
				}

				$attachments = array_filter(explode(',', $meta_image_gallery));
				$update_meta = false;
				$updated_gallery_ids = array();

				echo '<div id="meta_images_container"><ul class="meta_images">';
				if (!empty($attachments)) {
					foreach ($attachments as $attachment_id) {
						$attachment = wp_get_attachment_image($attachment_id, 'thumbnail');

						// if attachment is empty skip
						if (empty($attachment)) {
							$update_meta = true;
							continue;
						}

						echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '">' . $attachment . '<ul class="actions"><li><a href="#" class="delete tips" data-tip="' . esc_attr__('Delete image', 'woocommerce') . '">' . __('Delete', 'woocommerce') . '</a></li></ul></li>';

						// rebuild ids to be saved
						$updated_gallery_ids[] = $attachment_id;
					}

					// need to update product meta to set new gallery ids
					if ($update_meta) {
						update_post_meta($post->ID, $metaPostTypeKey, implode(',', $updated_gallery_ids));
					}
				}
				echo "</ul>";
				echo '<input type="hidden" class="meta_gallery_input" id="'.$postTypeKey.'" name="'.$postTypeKey.'" value="' . esc_attr($meta_image_gallery) . '" />';
				echo '</div><p class="add_meta_images hide-if-no-js"><a href="#" data-choose="Add Images to Product Gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete">Thêm tất cả hình ảnh</a></p>';
			}, $type['name'], 'side', 'low');
		}, 30);
	}

	protected function registerFieldTaxonomy($key, $label, $taxonomy, $type = 'text') {
		if( function_exists('acf_add_local_field_group') ):
			acf_add_local_field_group(array(
				'key' => 'group_'.$key,
				'title' => '',
				'fields' => array (
					array (
						'key' => $key,
						'label' => $label,
                        'name' => $key . '_' . $taxonomy,
						'type' => $type,
					)
				),
				'location' => array (
					array (
						array (
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => $taxonomy,
						),
					),
				),
			));
		endif;
	}

	protected function registerField($key, $label, $postType = 'post', $type = 'text') {
		if( function_exists('acf_add_local_field_group') ):
			acf_add_local_field_group(array(
				'key' => 'group_'.$key.$postType,
				'title' => $label,
				'fields' => array (
					array (
						'key' => $key,
						'label' => $label,
						'name' => $key,
						'type' => $type,
					)
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => $postType,
						),
					),
				),
			));

		endif;
	}

}
