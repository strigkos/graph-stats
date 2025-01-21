<?php

	/*
	*	Check wp-role / application scope / copywriter / etc.
	*	I use user_nicename as SCOPE title
	*	- VARS -
	* 	$roles_and_scopes : Table with roles, scopes, logins
	*	$redirect : If function called with TRUE then it will decide to redirect (or NOT) the whole page.
	*				Using FALSE it will deceide to show (or NOT) the nested contenet.
	*	$copywriter	: Main for Archives issue, if FALSE then the copywriter of the post has not the right apriori
	*/
	function web_app_request_access( $roles_and_scopes = array(), $redirects = false, $copywriters = true )
	{
		$can_access = false;
		$access_type = 0;
		
		$user_obj = wp_get_current_user();
		$post_obj = get_post();
		
		if ( current_user_can('administrator') )
		{
			/// Current user is Admin
			$access_type = 10;
			$can_access = true;
		}
		else if ( $post_obj->post_author == $user_obj->ID && $copywriters )
		{
			/// Continue beacause Logged in user is THE copywriter of the post..
			$access_type = 11;
			$can_access = true;
		}
		else if ( in_array($user_obj->user_login, $roles_and_scopes) )
		{
			/// Continue for user login username..
			$access_type = 12;
			$can_access = true;
		}
		else if ( in_array($user_obj->user_nicename, $roles_and_scopes) ) 
		{
			/// Continue for user scope..
			$access_type = 13;
			$can_access = true;
		}
		else if ( array_intersect($user_obj->roles, $roles_and_scopes) )
		{
			/// Continue for user roles..
			$access_type = 14;
			$can_access = true;
		}
		else
		{
			foreach ( $roles_and_scopes as $keyphrase ) 
			{
				if ( array_key_exists ($keyphrase, $user_obj->caps) ) 
				{
					$access_type = 15;
					$can_access = true;
				}
			}
		
			foreach ( $roles_and_scopes as $keyphrase ) 
			{
				if ( array_key_exists ($keyphrase, $user_obj->allcaps) ) 
				{
					$access_type = 16;
					$can_access = true;
				}
			}
		}

		/*
			Future development : Check for special user meta fields like 'user_group'
			Redirects : Page name (slug)
		*/
		
		if ( $can_access )
		{
			return $access_type;
		}
		else
		{
			if ( $redirects )
			{
				wp_redirect('/');
				exit('Δεν έχετε δικαιώματα για να δείτε αυτή τη σελίδα!');
			}
			else
			{
				return false;
			}
		}		
	}

?>