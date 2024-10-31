<?php


namespace rnpagebuilder\core\Managers\PreviewManager;


class PreviewManager
{
    public function GetPreviewURL()
    {
        $pageId=0;
        $pages=get_pages( array(
            'meta_key'   => 'rnpb_preview_page',
            'meta_value' => true,
        ));

        if(count($pages)==0)
        {
            $post = array(
                'post_content' => '[rnpbpreview]',
                'post_name' => __('Page Builder Preview'),
                'post_title' => __('Page Builder Preview'),
                'post_status' => 'publish',
                'post_type' => 'page',
                'ping_status' => 'closed',
                'comment_status' => 'closed',
                'meta_input' => array(
                    'rnpb_preview_page' => true
                )
            );
            $pageId = wp_insert_post($post);
        }else
        {
            $currentPage=$pages[0];
            if(strpos($currentPage->post_content,'[rnpbpreview]')===false)
            {
               wp_update_post(array(
                   'ID'=>$currentPage->ID,
                   'post_content'=>$currentPage->post_content.'[rnpbpreview]'
               ));
            }
            $pageId = $currentPage->ID;
        }

        return get_permalink($pageId);
    }

}