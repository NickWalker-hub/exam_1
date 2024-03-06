<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int|null $role_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $phone
 *
 * @property Request[] $requests
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id'], 'integer'],
            ['role_id', 'default', 'value' => 1],
            [['username', 'password', 'email', 'first_name', 'last_name', 'middle_name', 'phone'], 'required'],
            [['email', 'first_name', 'last_name', 'middle_name', 'phone'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['username', 'password'], 'string', 'min' => 6],
            [['first_name', 'last_name', 'middle_name'], 'match', 'pattern' => '/^[а-яА-ЯёЁ \-]*$/u', 'message' => 'ФИО должно содержать только символы кириллицы и/или тире, пробелы'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['phone'], 'unique'],
            [['phone'], 'match', 'pattern' => '/^\+?7\(\d{3}\)\-\d{3}\-\d{2}\-\d{2}$/', 'message' => 'Шаблон телефона "+7(ХХХ)-ХХХ-ХХ-ХХ"'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        $this->password = md5($this->password);
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'first_name' => 'Имя',
            'last_name' => 'Отчество',
            'middle_name' => 'Фамилия',
            'phone' => 'Телефон',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['user_id' => 'id']);
    }

   /**
    * Gets query for [[Role]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function isAdmin()
    {
        return $this->role->code === "admin";
    }

    public function getFullName()
        {
            return "{$this->middle_name} {$this->first_name} {$this->last_name}"  ;
        }
}
