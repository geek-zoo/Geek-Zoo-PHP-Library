<?php

/*
 * This file is part of the Geek-Zoo Projects.
 *
 * @copyright (c) 2010 Geek-Zoo Projects More info http://www.geek-zoo.com
 * @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
 * @author xuanyan <xuanyan@geek-zoo.com>
 *
*/

class Cipher
{
    /**
     * 加密函数
     *
     * @param string 原字符 
     * @param string 密钥
     * @return string
     */
    public static function encrypt($text, $salt = 'whateveryouwant')
    {
        if (!function_exists('mcrypt_encrypt')) {
            return trim(base64_encode(self::strcode($text, $salt)));
        }
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    /**
     * 解密函数
     *
     * @param string 加密字符 
     * @param string 密钥
     * @return string
     */
    public static function decrypt($text, $salt = 'whateveryouwant')
    {
        if (!function_exists('mcrypt_encrypt')) {
            return trim(self::strcode(base64_decode($text), $salt));
        }
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    /**
     * 加密函数，加密后通过url传递
     *
     * @param string 原字符 
     * @param string 密钥
     * @return string
     */
    public static function encryptForUrl($text, $salt = 'whateveryouwant')
    {
        return urlencode(self::encrypt($text, $salt));
    }

    public static function strcode($string, $salt = 'whateveryouwant')
    {
        $key = md5($salt);
        $keylen = strlen($key);
        $strlen = strlen($string);
        $code = '';

        for ($i = 0; $i < $strlen; $i ++) {  
            //echo $i;  
            $k = $i % $keylen; //余数  将字符全部位移  
            $code .= $string[$i] ^ $key[$k];//位移  
        }

        return $code;
    }
}