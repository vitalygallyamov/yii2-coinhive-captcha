<?php
/**
 * @link https://github.com/vitalygallyamov/yii2-coinhive-captcha
 * @copyright Copyright (c) 2017 Vitaly Gallyamov
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace vitalygallyamov\yii2\chcaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Yii2 Coin-Hive captcha widget (Js Miner).
 *
 * For example:
 *
 * ```php
 * <?= $form->field($model, 'reCaptcha')->widget(
 *  ChCaptcha::className(),
 *  ['siteKey' => 'your siteKey']
 * ) ?>
 * ```
 *
 * or
 *
 * ```php
 * <?= ChCaptcha::widget([
 *  'name' => 'ChCaptcha',
 *  'siteKey' => 'your siteKey',
 *  'widgetOptions' => ['class' => 'col-sm-offset-3']
 * ]) ?>
 * ```
 *
 * @see https://developers.google.com/recaptcha
 * @author HimikLab
 * @package himiklab\yii2\recaptcha
 */

class ChCaptcha extends InputWidget
{
    const CAPTCHA_HASHES_FIELD = 'coinhive-captcha-hashes';

    /** @var string Url source. */
    public $sourceUrl = "https://coinhive.com/lib/captcha.min.js";

    /** @var string Your public Site-Key. */
    public  $siteKey = '';

    /** @var integer The number of hashes that have to be accepted by the mining pool.
        Our pool uses a difficulty of 256, so your hashes goal should be a multiple of 256. */
    public $hashes = 1024;

    /** @var boolean Optional. Whether to automatically start solving the captcha (true|false). The default is false. */
    public $autostart = false;

    /** @var boolean Optional. Whether to hide the Coinhive logo and the What is this link. */
    public $whitelabel = false;

    /** @var string Optional. The name of a global JavaScript function that should be called when the goal is reached. */
    public $callback = '';

    /** @var string Optional. A CSS selector for elements that should be disabled until the goal is reached.
        Usually this will be your form submit button. */
    public $disableElements = '';

    public function run()
    {
        if(!$this->siteKey)
            $this->siteKey = Yii::$app->chCaptcha->siteKey;

        $this->checkCaptchaSettings();

        $divOptions = [
            'class' => 'coinhive-captcha',
            'data-key' => $this->siteKey,
            'data-autostart' => $this->autostart == true ? 'true' : 'false',
            'data-whitelabel' => $this->whitelabel == true ? 'true' : 'false',
            'data-callback' => $this->callback,
            'data-hashes' => $this->hashes,
            'data-disable-elements' => $this->disableElements
        ];

        $view = $this->view;
        $view->registerJsFile(
            $this->sourceUrl,
            ['position' => $view::POS_END, 'async' => true, 'defer' => true]
        );
        echo Html::hiddenInput(self::CAPTCHA_HASHES_FIELD, $this->hashes);
        echo Html::tag('div', '', $divOptions);

        if ($this->hasModel()) {
            $inputName = Html::getInputName($this->model, $this->attribute);
            $inputId = Html::getInputId($this->model, $this->attribute);
        } else {
            $inputName = $this->name;
            $inputId = 'chcaptcha-' . $this->name;
        }
        echo Html::hiddenInput($inputName, null, ['id' => $inputId]);
    }

    private function checkCaptchaSettings()
    {
        if(!$this->sourceUrl)
            throw new InvalidConfigException('Required `sourceUrl` param isn\'t set.');

        if(!$this->siteKey)
            throw new InvalidConfigException('Required `siteKey` param isn\'t set.');

        if(!$this->hashes)
            throw new InvalidConfigException('Required `hashes` param isn\'t set.');
    }
}