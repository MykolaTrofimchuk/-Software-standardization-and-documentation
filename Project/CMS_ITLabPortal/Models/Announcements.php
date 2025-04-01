<?php

namespace Models;

use core\Core;
use core\Model;
use DateTime;

/**
 * Клас для роботи з таблицею в БД, пов'язаною з Оголошеннями (Announcements)
 *
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

    public static function CountAll($where = null, $tableParams = '')
    {
        $result = self::findRowsByCondition("COUNT(*) as count", $where, $tableParams);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
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

    public static function EditAnnouncementInfo($announcementId, $dataToUpdate)
    {
        $announcement = Announcements::selectRowById($announcementId, 'Models\Announcements');

        if ($announcement) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    $announcement->{$field} = $value;
                }
            }
            var_dump($announcement);
            $announcement->save();
            return true;
        } else {
            return false;
        }
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }

    public static function SelectByFieldLike($field, $searchTerm, $limit = null, $offset = 0)
    {
        $validFields = ['title', 'text', 'publicationDate'];
        if (!in_array($field, $validFields)) {
            throw new \InvalidArgumentException("Невірне поле для пошуку: {$field}");
        }

        $where = ["{$field} LIKE" => "%{$searchTerm}%"];
        return Core::get()->db->select_like(self::$tableName, "*", $where, $limit, $offset);
    }
}