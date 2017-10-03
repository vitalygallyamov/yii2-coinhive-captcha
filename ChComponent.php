<?php

/**
 * @link https://github.com/vitalygallyamov/yii2-coinhive-captcha
 * @copyright Copyright (c) 2017 Vitaly Gallyamov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace vitalygallyamov\yii2\chcaptcha;

use Yii;
use yii\base\InvalidConfigException;

/**
 * ChComponent captcha component class.
 *
 * @author Vitaly Gallyamov
 * @package vitalygallyamov\yii2\chcaptcha
 */

class ChComponent extends Component
{
	/** @var string Your Secret-Key. */
	public $secretKey = '';

	/** @var string Api Url. */
	public $verifyUrl = 'https://api.coinhive.com/token/verify';

    public function init()
    {
        parent::init();

        if (empty($this->secretKey)) {
            throw new InvalidConfigException('Required `key` param isn\'t set.');
        }

        if (empty($this->verifyUrl)) {
            throw new InvalidConfigException('Required `verifyUrl` param isn\'t set.');
        }
    }
 
}
