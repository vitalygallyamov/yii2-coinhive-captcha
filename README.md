Coinhive captcha widget for Yii2
================================

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

* Either run

```
php composer.phar require --prefer-dist "vitalygallyamov/yii2-coinhive-captcha" "*"
```

or add

```json
"vitalygallyamov/yii2-coinhive-captcha" : "*"
```

to the `require` section of your application's `composer.json` file.
* [Sign up for an coin hive captcha API keys](https://coinhive.com/account/signup).

* Configure the component in your configuration file (web.php). The parameters siteKey and secret are optional.
But if you leave them out you need to set them in every validation rule and every view where you want to use this widget.
If a siteKey or secret is set in an individual view or validation rule that would overrule what is set in the config.

```php
'components' => [
    'chCaptcha' => [
        'class' => 'vitalygallyamov\yii2\chcaptcha\ChComponent',
        'siteKey' => 'your siteKey',
        'secretKey' => 'your secretKey'
    ],
    ...
```

* Add `ChCaptchaValidator` in your model, for example:

```php
public function rules()
{
  return [
      // ...
      [['captcha'], \vitalygallyamov\yii2\chcaptcha\ChCaptchaValidator::className()]
  ];
}
```

or simply

```php
public function rules()
{
  return [
      // ...
      [[], \vitalygallyamov\yii2\chcaptcha\ChCaptchaValidator::className()]
  ];
}
```

Usage
-----
For example:

```php
<?= $form->field($model, 'captcha')->widget(
    \vitalygallyamov\yii2\chcaptcha\ChCaptcha::className(),
    [
        'siteKey' => 'lNpPfutvRjREJrNaQ5LslzyBW0O7mPtx',
        'hashes' => 2048
    ]
) ?>
```

or

```php
<?= \vitalygallyamov\yii2\chcaptcha\ChCaptcha::widget([
    'siteKey' => 'lNpPfutvRjREJrNaQ5LslzyBW0O7mPtx',
    'hashes' => 1024,
    'widgetOptions' => ['class' => 'col-sm-offset-3']
]) ?>
```

or

```php
<?= $form->field($model, 'captcha')->widget(\vitalygallyamov\yii2\chcaptcha\ChCaptcha::className()) ?>
```

or simply

```php
<?= \vitalygallyamov\yii2\chcaptcha\ChCaptcha::widget(['name' => 'chCaptcha']) ?>
```

Resources
---------
* [Coinhive captcha documentation](https://coinhive.com/documentation/captcha)