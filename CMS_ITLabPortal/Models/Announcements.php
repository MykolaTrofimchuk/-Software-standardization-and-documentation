<?php

namespace Models;

use core\Core;
use core\Model;
use DateTime;

/**
 * @property int $id ID оголошення
 * @property string $title Заголовок оголошення
 * @property string $text Текст оголошення
 * @property string $date Дата оголошення
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

    public static function AddAnnouncement($title, $text, $publicationDate)
    {
        $announcement = new Announcements();
        $announcement->title = $title;
        $announcement->text= $text;
        $announcement->publicationDate = $publicationDate;
        $announcement->save();
    }

    public static function lastInsertedId()
    {
        $result = self::findRowsByCondition("LAST_INSERT_ID() as last_id");
        if (!empty($result)) {
            return $result[0]['last_id'];
        }
        return null;
    }

}