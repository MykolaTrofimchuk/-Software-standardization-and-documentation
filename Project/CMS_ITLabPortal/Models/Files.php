<?php

namespace Models;

use core\Model;

/**
 * @property int $id ID картинки
 * @property int $announcement_id ID оголошення
 * @property string $image_path Шлях до картинки
 */

class Files extends Model
{
    public static $tableName = 'files';

    public static function AddImages($announcementId, $imagePath)
    {
        $image = new Files();
        $image->announcement_id = $announcementId;
        $image->image_path = $imagePath;
        $image->save();
    }

    public static function FindPathByAnnouncementId($announcementId)
    {
        $rows = self::findRowsByCondition('image_path', ['announcement_id' => $announcementId]);
        if (!empty($rows))
            return implode(',', $rows[0]);
        else
            return null;
    }
}