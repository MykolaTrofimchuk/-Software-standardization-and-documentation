<?php

namespace Models;

use core\Model;

/**
 * Клас для роботи з файлами (зображеннями), що прикріплені до оголошень.
 *
 * @property int $id ID картинки
 * @property int $announcement_id ID оголошення, до якого прикріплена картинка
 * @property string $image_path Шлях до картинки
 */
class Files extends Model
{
    /**
     * Назва таблиці в базі даних
     *
     * @var string
     */
    public static $tableName = 'files';

    /**
     * Додає зображення до оголошення.
     *
     * @param int $announcementId ID оголошення, до якого додається зображення
     * @param string $imagePath Шлях до файлу зображення
     * @return void
     */
    public static function AddImages($announcementId, $imagePath)
    {
        $image = new Files();
        $image->announcement_id = $announcementId;
        $image->image_path = $imagePath;
        $image->save();
    }

    /**
     * Знаходить шлях до зображення за ID оголошення.
     *
     * @param int $announcementId ID оголошення
     * @return string|null Шлях до зображення або null, якщо не знайдено
     */
    public static function FindPathByAnnouncementId($announcementId)
    {
        $rows = self::findRowsByCondition('image_path', ['announcement_id' => $announcementId]);
        if (!empty($rows))
            return implode(',', $rows[0]);
        else
            return null;
    }
}
