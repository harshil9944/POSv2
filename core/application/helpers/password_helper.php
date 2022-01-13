<?php
if ( ! function_exists('hash_password')){
	function hash_password($password){
		return sha1(md5(sha1(md5($password))));
	}
}
if ( ! function_exists('compare_hash_password')){
	function compare_hash_password($password,$hashed_password){
		$new_hash_password = hash_password($password);
		if($new_hash_password==$hashed_password){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}