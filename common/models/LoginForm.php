<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\Session;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'rememberMe' => 'Запомнить меня',
            'password' => 'Пароль',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login($backend = false)
    {
        if ($this->validate()) {

            $user = $this->getUser();
            if ($user) {
                $session = new Session();
                $session->open();
                $session['role'] = $user->role;
                if ($backend) {
                    if ($this->_user->role != User::ROLE_USER) {
                        return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
                    } else {
                        Yii::$app->session->setFlash('error', 'У данного пользователя не прав доступа. <br> Обратитесь к администратору.');
                        return false;
                    }
                } else {
                    return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
                }
            } else {
                return false;
            }
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
