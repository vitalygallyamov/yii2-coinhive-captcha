<?php
/**
 * @link https://github.com/vitalygallyamov/yii2-coinhive-captcha
 * @copyright Copyright (c) 2017 Vitaly Gallyamov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace vitalygallyamov\yii2\chcaptcha;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\validators\Validator;

/**
 * ChCaptcha widget validator.
 *
 * @author Vitaly Gallyamov
 * @package vitalygallyamov\yii2\chcaptcha
 */

class ChCaptchaValidator extends Validator
{
    const CAPTCHA_TOKEN_FIELD = 'coinhive-captcha-token';
    const CAPTCHA_HASHES_FIELD = 'coinhive-captcha-hashes';

    public $skipOnEmpty = false;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'The verification token is incorrect.');
        }
    }

    /**
     * @param string $value
     * @return array|null
     * @throws Exception
     * @throws \yii\base\InvalidParamException
     */
    protected function validateValue($value)
    {
        $hashes = Yii::$app->request->post(self::CAPTCHA_HASHES_FIELD);

        if (empty($value)) {
            if (!($value = Yii::$app->request->post(self::CAPTCHA_TOKEN_FIELD))) {
                return [$this->message, []];
            }
        }
        $data = http_build_query([
            'secret' => Yii::$app->chCaptcha->secretKey,
            'token' => $value,
            'hashes' => $hashes
        ]);
        $response = $this->getResponse(Yii::$app->chCaptcha->verifyUrl, $data);
        if (!isset($response['success'])) {
            throw new Exception('Invalid coinhive captcha verify response.');
        }
        return $response['success'] ? null : [$this->message, []];
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        return 'console.log(attributes); return false;';
    }

    protected function getResponse($request, $data = ''){
        
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data
        );

        $ch = curl_init($request);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $errno;
        $header['errmsg'] = $errmsg;

        $response = $content;
        
        if ($header['errno'] !== 0) {
            throw new Exception(
                'Unable connection to the captcha server. Curl error #' . $header['errno'] . ' ' . $header['errmsg']
            );
        }
        
        return Json::decode($response, true);
    }
}
