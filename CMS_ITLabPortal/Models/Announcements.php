<?php

namespace Models;

use core\Core;
use core\Model;
use DateTime;

/**
 * @property string $title Заголовок оголошення
 * @property string $text Текст оголошення
 * @property string $date Дата оголошення
 * @property int $id ID оголошення
*/
class Announcements extends Model
{
    public static $tableName = 'announcements';

    public static function SelectAll()
    {
        return $rows = self::findAll();
    }
    public static function SelectById($announcementId)
    {
        $result = self::findByCondition(['id' => $announcementId]);
        return !empty($result) ? (object) $result[0] : null;
    }

    public static function SelectPaginated($limit, $offset)
    {
        $rows = self::findByLimitAndOffset($limit, $offset);
        $validAnnouncements = [];
        foreach ($rows as $announcement) {
            $validAnnouncements[] = $announcement;
        }

        return $validAnnouncements;
    }

    public static function CountAll()
    {
        return self::findRowsByCondition('COUNT(*) as count');
    }

}