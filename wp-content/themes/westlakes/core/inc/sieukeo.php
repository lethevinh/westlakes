<?php
if (function_exists('acf_add_local_field_group')):
    $type = 'image';
    $key = 'category_icon';
    $taxonomy = 'category';
    $label = 'Category Icon';
    acf_add_local_field_group(array(
        'key' => 'group_1',
        'title' => '',
        'fields' => array(
            array(
                'key' => $key,
                'label' => $label,
                'name' => $key . '_' . $taxonomy,
                'type' => $type,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => $taxonomy,
                ),
            ),
        ),
    ));
    // color
    $type = 'color_picker';
    $key = 'category_color';
    $taxonomy = 'category';
    $label = 'Category Color';
    acf_add_local_field_group(array(
        'key' => 'group_2',
        'title' => '',
        'fields' => array(
            array(
                'key' => $key,
                'label' => $label,
                'name' => $key . '_' . $taxonomy,
                'type' => $type,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => $taxonomy,
                ),
            ),
        ),
    ));
    // Banner Post Type
    acf_add_local_field_group(array (
        'key' => 'group_4',
        'title' => 'Banner Setting',
        'fields' => array (
            array (
                'key' => 'page',
                'label' => 'Trang Hiển Thị',
                'instructions' => "Mặc định là trang chủ, Là vị trí trang hiển thị banner",
                'name' => 'page',
                'required' => 1,
                'type' => 'page_link',
                'default_value' => 'home',
            ),
            array (
                'key' => 'position',
                'label' => 'Vị Trí',
                'instructions' => " Là vị trí hiển thị banner trong trang",
                'name' => 'position',
                'required' => 1,
                'type' => 'select',
                'choices' => array(
                    'left_top_1'	=> 'Left Banner 1 (120x300)',
                    'left_top_2'	=> 'Left Banner 2 (120x300)',
                    'left_bottom'	=> 'Left Bottom (200x80)',
                    'right_top_1'	=> 'Right Banner 1 (120x300)',
                    'right_top_2'	=> 'Right Banner 2 (120x300)',
                    'right_bottom'	=> 'Right Bottom (200x80)',

                    'page_home_1' => "Home 1 (298x150)",
                    'page_home_2' => "Home 2 (1170x140)",
                    'page_home_3' => "Home 3 (600x140)",
                    'page_home_4' => "Home 4 (600x140)",
                    'page_home_5' => "Home 5 (600x140)",
                    'page_home_6' => "Home 6 (600x140)",
                    'page_home_7' => "Home 7 (375x250)",
                ),
                'default_value' => 'left_top_1',
            ),

            array (
                'key' => 'html',
                'label' => 'Code',
                'name' => 'html',
                'required' => 1,
                'type' => 'textarea',
                'default_value' => "<a rel=\"nofollow\" href=\"https://www.v9bidn.com?affcode=950196\" alt=\"V9BET Cược Thể Thao\" class=\"external\">
    <img class=\"lazy\" src=\"https://v9banners.com/affiliates/vn/affiliate/viVbnG_120x300.gif\" alt=\"V9BET Tỷ Lệ Cược Bóng Đá Hôm Nay\">
</a>",
            )
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'banner',
                ),
            ),
        )
    ));
    // Custom Post
    acf_add_local_field_group(array (
        'key' => 'group_5',
        'title' => 'NHẬN ĐỊNH KÈO',
        'fields' => array (
            array (
                'key' => 'tour_name',
                'label' => 'Tên Giải',
                'instructions' => "Ví dụ: GIẢI VĐQG BA LAN",
                'name' => 'tour_name',
                'type' => 'text',
                'default_value' => 'GIẢI VĐQG BA LAN',
            ),
            array (
                'key' => 'tour_logo_a',
                'label' => 'Logo đội 1',
                'instructions' => "Ví dụ: ",
                'name' => 'tour_logo_a',
                'type' => 'image'
            ),
            array (
                'key' => 'tour_name_a',
                'label' => 'Tên đội 1',
                'instructions' => "Ví dụ: ",
                'name' => 'tour_name_a',
                'type' => 'text'
            ),
            array (
                'key' => 'tour_logo_b',
                'label' => 'Logo đội 2',
                'instructions' => "",
                'name' => 'tour_logo_b',
                'type' => 'image'
            ),
            array (
                'key' => 'tour_name_b',
                'label' => 'Tên đội 2',
                'instructions' => "Ví dụ: ",
                'name' => 'tour_name_b',
                'type' => 'text'
            ),
            array (
                'key' => 'tour_scale_a',
                'label' => 'Tỉ Lệ Châu Á',
                'instructions' => "Ví dụ: 0.90*0.5/1*0.92",
                'name' => 'tour_scale_a',
                'type' => 'text',
                'default_value' => '0.90*0.5/1*0.92',
            ),
            array (
                'key' => 'tour_scale_au',
                'label' => 'Tỉ Lệ Châu Âu',
                'instructions' => "Ví dụ: 0.90*0.5/1*0.92",
                'name' => 'tour_scale_au',
                'type' => 'text',
                'default_value' => '0.90*0.5/1*0.92',
            ),
            array (
                'key' => 'tour_scale_tx',
                'label' => 'Tỉ Lệ Tài Xỉu',
                'instructions' => "Ví dụ: 0.90*0.5/1*0.92",
                'name' => 'tour_scale_tx',
                'type' => 'text',
                'default_value' => '0.90*0.5/1*0.92',
            ),
            array (
                'key' => 'tour_time',
                'label' => 'Thời gian',
                'instructions' => "Ví dụ: 15:45 - 18/03",
                'name' => 'tour_time',
                'type' => 'date_time_picker',
                'default_value' => '15:45 - 18/03',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'position' => 'acf_after_title'
    ));
    // Custom Nhan dinh keo Hot
    acf_add_local_field_group(array (
        'key' => 'group_6',
        'title' => 'BÀI VIẾT ĐỊNH KÈO',
        'fields' => array (
            array (
                'key' => 'tour_posts',
                'label' => 'Bài viết nhận định kèo',
                'instructions' => "Chọn bài viết nhận định kèo: <br> - <strong>Ví dụ: </strong> Soi kèo bóng đá Dalkurd FF vs AFC Eskilstunas lúc 20h00 ngày 28/03/2020 – Giao Hữu CLB... <br>  - <strong>Thứ tự bài viết được chọn cũng là thứ tự hiển thị </strong>",
                'name' => 'tour_posts',
                'type' => 'post_object',
                'required' => 1,
                'position' => 'acf_after_title',
                'post_type' => 'post',
//				'taxonomy' => ['category:keo-tran-hot'],
                'multiple' => 1,
                'return_format' => 'object',
                'allow_null' => 1,
                'ui' => 1
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tour_to_day',
                ),
            ),
        ),
        'position' => 'acf_after_title'
    ));
endif;
add_action('rest_api_init', function () {
    register_rest_route('sieukeo/v1', 'latest-posts/(?P<category_id>\d+)', array(
        'methods' => 'GET',
        'callback' => function ($request) {

            $args = array(
                'category' => $request['category_id'],
            );

            $posts = get_posts($args);
            if (empty($posts)) {
                return new WP_Error('empty_category', 'there is no post in this category', array('status' => 404));

            }

            $response = new WP_REST_Response($posts);
            $response->set_status(200);

            return $response;
        },
    ));
});

//-----------
