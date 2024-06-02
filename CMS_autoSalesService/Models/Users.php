<?php

namespace Models;

use core\Core;
use core\Model;

/**
 * @property int $id ID
 * @property string $login Логін
 * @property string $password Пароль
 * @property string $first_name Ім'я
 * @property string $last_name Прізвище
 * @property string $email Ел.пошта
 */
class Users extends Model
{
    public static $tableName = 'users';

    public static function FindLoginAndPassword($login, $password)
    {
        $rows = self::findByCondition(['login' => $login]);
        if (!empty($rows)) {
            $user = $rows[0];
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                error_log('Password verification failed.');
            }
        }
        return null;
    }

    public static function FindByLogin($login)
    {
        $rows = self::findByCondition(['login' => $login]);
        if (!empty($rows))
            return $rows[0];
        else
            return null;
    }

    public static function IsUserLogged()
    {
        return !empty(Core::get()->session->get('user'));
    }

    public static function RegisterUser($login, $password, $firstName, $lastName, $email = null)
    {
        $user = new Users();
        $user->login = $login;
        $user->password = password_hash($password, PASSWORD_DEFAULT); // Хешування пароля
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->save();
    }

    public static function LoginUser($user)
    {
        Core::get()->session->set('user', $user);
    }

    public static function LogoutUser()
    {
        Core::get()->session->remove('user');
    }
}