<?php
namespace App\Helpers;

use Carbon\Carbon;
use Core\Lib\Controller;

class Utils extends Controller{

    private static $instance;
	public $debug;

    /**
     * @return Utils
     */
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new Utils();
        }

        return self::$instance;
    }

	/**
	 * @param $useragent
	 *
	 * @return bool
	 */
	public static function detect_mobile($useragent)
	{
		if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $email
	 *
	 * @return bool
	 */
	public static function is_email($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @param $rawData
     *
     * @return mixed
     */
    public static function stripSlashes($rawData) {
        return is_array($rawData) ? array_map(array('self', 'stripSlashes'), $rawData) : stripslashes($rawData);
    }

    /**
     * @param $string
     * @param $size
     *
     * @return string
     */
    public static function textLimit($string, $size) {
        $string = (strlen($string) <= $size) ? $string : substr($string, 0, $size) . '...';
        return $string;
    }

	/**
	 * @param $uri
	 */
	public function active($uri){
		echo (str_replace('/','.',substr($_SERVER['REQUEST_URI'], 1)) == $uri)? "class='active'" : "";
	}

    /**
     * @return bool
     */
    static function value($key = null) {
        if (isset($key) && $key !== null) {
            return (isset($_REQUEST[$key])) ? ' value=' . $_REQUEST[$key] . '' : false;
        }
        return true;
    }

    /**
     * @param $key
     *
     * @return string|static
     */
    public static function formatDate($key) {
        $now = Carbon::now();
        $createdAt = Carbon::parse($key);
        $now = $createdAt->format('d/m/Y');
        return $now;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function formatMoney($value) {
        return number_format($value, 2, ',', '.');
    }


	/**
     * @param       $data
     * @param       $key
     * @param       $iv
     * @param array $settings
     *
     * @return mixed
     */
    public static function encrypt($data, $key, $iv, $settings = array()) {
        if ($data === '' || !extension_loaded('mcrypt')) {
            return $data;
        }

        //Merge settings with defaults
        $defaults = array(
            'algorithm' => MCRYPT_RIJNDAEL_256,
            'mode' => MCRYPT_MODE_CBC
        );
        $settings = array_merge($defaults, $settings);

        //Get module
        $module = mcrypt_module_open($settings['algorithm'], '', $settings['mode'], '');

        //Validate IV
        $ivSize = mcrypt_enc_get_iv_size($module);
        if (strlen($iv) > $ivSize) {
            $iv = substr($iv, 0, $ivSize);
        }

        //Validate key
        $keySize = mcrypt_enc_get_key_size($module);
        if (strlen($key) > $keySize) {
            $key = substr($key, 0, $keySize);
        }

        //Encrypt value
        mcrypt_generic_init($module, $key, $iv);
        $res = @mcrypt_generic($module, $data);
        mcrypt_generic_deinit($module);

        return $res;
    }

	public static function setDebug($exceptionCode){
		$exception = null;
		switch ($exceptionCode){
			case 23000 : $exception = "Erro ao inserir os dados!"; break;
		}
		$print = "<div class='debug'>";
		$print .= $exception;
		$print .= "</div>";
		self::getInstance()->debug =  $print;
	}

	public static function getDebug(){
		echo self::getInstance()->debug;
	}

	/**
	 * @param int $length
	 *
	 * @return string
	 */
	public static function generateToken($length = 20) {
		if(function_exists('openssl_random_pseudo_bytes')) {
			$token = base64_encode(openssl_random_pseudo_bytes($length, $strong));
			if($strong == TRUE)
				return strtr(substr($token, 0, $length), '+/=', '-_,');
		}

		$characters = '0123456789';
		$characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters)-1;
		$token = '';

		for ($i = 0; $i < $length; $i++) {
			$token .= $characters[mt_rand(0, $charactersLength)];
		}

		return $token;
	}

	/**
     * @param       $data
     * @param       $key
     * @param       $iv
     * @param array $settings
     *
     * @return mixed
     */
    public static function decrypt($data, $key, $iv, $settings = array()) {
        if ($data === '' || !extension_loaded('mcrypt')) {
            return $data;
        }

        //Merge settings with defaults
        $defaults = array(
            'algorithm' => MCRYPT_RIJNDAEL_256,
            'mode' => MCRYPT_MODE_CBC
        );
        $settings = array_merge($defaults, $settings);

        //Get module
        $module = mcrypt_module_open($settings['algorithm'], '', $settings['mode'], '');

        //Validate IV
        $ivSize = mcrypt_enc_get_iv_size($module);
        if (strlen($iv) > $ivSize) {
            $iv = substr($iv, 0, $ivSize);
        }

        //Validate key
        $keySize = mcrypt_enc_get_key_size($module);
        if (strlen($key) > $keySize) {
            $key = substr($key, 0, $keySize);
        }

        //Decrypt value
        mcrypt_generic_init($module, $key, $iv);
        $decryptedData = @mdecrypt_generic($module, $data);
        $res = rtrim($decryptedData, "\0");
        mcrypt_generic_deinit($module);

        return $res;
    }


	/**
     * @param $expires
     * @param $secret
     *
     * @return mixed
     */
    private static function getIv($expires, $secret) {
        $data1 = hash_hmac('sha1', 'a'.$expires.'b', $secret);
        $data2 = hash_hmac('sha1', 'z'.$expires.'y', $secret);

        return pack("h*", $data1.$data2);
    }
}