<?php 
add_action( 'cmb2_admin_init', 'portfolio_metaboxes' );


function portfolio_metaboxes() {

	$persian_box = new_cmb2_box( array(
		'id'            => 'persian_box_metabox',
		'title'         => __( 'اطلاعات اصلی', 'cmb2' ),
		'object_types'  => array( 'portfolio', ), 
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$persian_box->add_field( array(
		'name'       => __( 'عنوان فارسی', 'cmb2' ),
		'desc'       => __( 'عنوان فارسی را وارد کنید', 'cmb2' ),
		'id'         => 'persian_title',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

	$persian_box->add_field( array(
		'name'       => __( 'عنوان انگلیسی', 'cmb2' ),
		'desc'       => __( 'عنوان انگلیسی را وارد کنید', 'cmb2' ),
		'id'         => 'english_title',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
	) );

    $persian_box->add_field( array(
        'name'    => 'توضیحات فارسی',
        'desc'    => 'توضیحات فارسی را بنویسید',
        'id'      => 'persion_description',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );

	$persian_box->add_field( array(
        'name'    => 'توضیحات انگلیسی',
        'desc'    => 'توضیحات انگلیسی را بنویسید',
        'id'      => 'english_description',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    


	// feature
	$feature_group = $persian_box->add_field( array(
	'id'          => 'feature_group',
	'type'        => 'group',
	'options'     => array(
		'group_title'       => __( 'ویژگی {#}', 'cmb2' ), 
		'add_button'        => __( 'افزودن ویژگی جدید', 'cmb2' ),
        'remove_button'     => __( 'حذف کردن ویژگی', 'cmb2' ),
        'sortable'          => true,
         'closed'         => true, 
         'remove_confirm' => esc_html__( 'آیا از حذف کردن ویژگی اطمینان دارید؟?', 'cmb2' ), 
		),
	) );

	// 
	$persian_box->add_group_field($feature_group ,  array(
		'name'       => __( 'ویدیو', 'cmb2' ),
		'id'         => 'feature_group_video',
		'type'       => 'file',
        'desc'    => 'ویدیو را آپلود کنید یا آدرس را بدهید',
        'options' => array(
            'url' => true, 
        ),
        'text'    => array(
            'add_upload_file_text' => 'آپلود ویدیو' 
        ),
	) );


	$persian_box->add_group_field($feature_group ,  array(
		'name' => 'توضیحات ویژگی فارسی',
		'desc' => 'توضیحات ویژگی به فارسی را بنویسید',
		'default' => '',
		'id' => 'feature_group_persian_description',
		'type' => 'textarea_small'
	) );


	$persian_box->add_group_field($feature_group ,  array(
		'name' => 'توضیحات ویژگی انگلیسی',
		'desc' => 'توضیحات ویژگی به انگلیسی را بنویسید',
		'default' => '',
		'id' => 'feature_group_english_description',
		'type' => 'textarea_small'
	) );


	$gallery_box = new_cmb2_box( array(
		'id'            => 'gallery_box_metabox',
		'title'         => __( 'گالری تصاویر', 'cmb2' ),
		'object_types'  => array( 'portfolio', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


	// gallery
	$gallery_group = $gallery_box->add_field( array(
	'id'          => 'gallery_group',
	'type'        => 'group',
	'options'     => array(
		'group_title'       => __( 'عکس {#}', 'cmb2' ), 
		'add_button'        => __( 'افزودن عکس جدید', 'cmb2' ),
        'remove_button'     => __( 'حذف کردن عکس', 'cmb2' ),
        'sortable'          => true,
         'closed'         => true, 
         'remove_confirm' => esc_html__( 'آیا از حذف کردن عکس اطمینان دارید؟?', 'cmb2' ), 
		),
	) );

	// 
	$gallery_box->add_group_field($gallery_group ,  array(
		'name'       => __( 'عکس', 'cmb2' ),
		'id'         => 'gallery_group_image',
		'type'       => 'file',
        'desc'    => 'عکس را آپلود کنید یا آدرس را بدهید',
        'options' => array(
            'url' => true, 
        ),
        'text'    => array(
            'add_upload_file_text' => 'آپلود عکس' 
        ),
	) );


}

?>