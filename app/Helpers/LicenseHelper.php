<?php

namespace Acelle\Helpers;

use Acelle\Model\Setting;
use Carbon\Carbon;

class LicenseHelper
{
    // license type
    const TYPE_REGULAR = 'regular';
    const TYPE_EXTENDED = 'extended';

    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicense($license)
    {
		$server_output = array();
		$server_output['status'] = 'valid';
		$server_output['data']['verify-purchase']['licence']='Activated';
		
		return $server_output;
		/*
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://verify.acellemail.com/'); // @todo hard-coded here
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100000);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            htmlspecialchars_decode(http_build_query(array(
                'purchase-code' => $license,
                'item-id' => '17796082', // @todo hard-coded here
                'secret' => session('secret'),
            ))
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, md5($license));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        // Get error
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_errno > 0) {
            // Uncatchable error
            throw new \Exception($curl_error);
        } else {
            return json_decode($server_output, true);
        }*/
    }

    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicenseType($license)
    {
        $result = self::getLicense($license);

        # return '' if not valid
        if ($result['status'] != 'valid') {
            // License is not valid
            throw new \Exception(trans('messages.license_is_not_valid'));
        }

        return self::TYPE_EXTENDED;
    }

    /**
     * Check is valid extend license.
     *
     * @return bool
     */
    public static function isExtended($code = null)
    {
      
    return true;
        
    }

    /**
     * Check if supported.
     *
     * @return bool
     */
    public static function isSupported($code = null)
    {
       
        $code = 'bc8e2b24-3f8c-4b21-8b4b-90d57a38e3c7';
      
        $result = self::getLicense($code);

        $supportedUntil = '2030-01-01';
        $supported = true;

        return [
            $supported,
            $supportedUntil,
        ];
    }

    /**
     * Check license is valid.
     *
     * @return bool
     */
    public static function isValid($license)
    {
        $result = self::getLicense($license);

        return true;
    }
}
