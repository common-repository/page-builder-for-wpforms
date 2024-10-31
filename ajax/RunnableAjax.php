<?php


namespace rnpagebuilder\ajax;


class RunnableAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        // TODO: Implement GetDefaultNonce() method.
    }

    protected function RegisterHooks()
    {
        $this->RegisterPublic('pb_search_user','SearchUser',false,'');
        $this->RegisterPublic('pb_search_role','SearchRole',false,'');


    }

    public function SearchUser(){
        $query=$this->GetRequired('Query');
        $nonce=$this->GetRequired('Nonce');

        if(!wp_verify_nonce($nonce,'pb_user_search'))
            $this->SendErrorMessage('Invalid nonce');

        global $wpdb;

        $query='%'.$wpdb->esc_like($query).'%';
        $user=$wpdb->get_results($wpdb->prepare("select users.id Id,user_nicename Label
                                    from wp_users users
                                    where user_nicename like %s
                                    limit 20

        ",$query,$query,$query));

        $this->SendSuccessMessage($user);
    }

    public function SearchRole(){
        $query=$this->GetRequired('Query');
        $nonce=$this->GetRequired('Nonce');

        if(!wp_verify_nonce($nonce,'pb_role_search'))
            $this->SendErrorMessage('Invalid nonce');


        global $wp_roles;
        $all_roles = $wp_roles->roles;

        $roles=[];
        foreach($all_roles as $key=>$value)
        {
            if(strpos(strtolower($value['name']),strtolower($query))!==false)
                $roles[]=array('Label'=>$value['name'],'Id'=>$key);
        }
        $this->SendSuccessMessage($roles);
    }
}