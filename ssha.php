<?php
function HashPassword($password)
{
  mt_srand((double)microtime()*1000000);
  $salt = mhash_keygen_s2k(MHASH_SHA1, $password, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
  $hash = "{SSHA}".base64_encode(mhash(MHASH_SHA1, $password.$salt).$salt);
  return $hash;
}

function ValidatePassword($password, $hash)
{
  $hash = base64_decode(substr($hash, 6));
  $original_hash = substr($hash, 0, 20);
  $salt = substr($hash, 20);
  $new_hash = mhash(MHASH_SHA1, $password . $salt);
  if (strcmp($original_hash, $new_hash) == 0)
	return true;
  else
	return false;
} 

?>
