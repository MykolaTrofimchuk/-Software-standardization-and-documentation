<?php

namespace Models;

use core\Model;

/**
 * Клас, який працює з таблицею "user_like_announcements", що містить інформацію про вподобані оголошення користувачами.
 *
 * Цей клас містить методи для додавання, видалення, перевірки наявності та отримання вподобаних оголошень користувачами.
 */
class UserLikeAnnouncements extends Model
{
    /**
     * Назва таблиці в базі даних.
     *
     * @var string
     */
    public static $tableName = 'user_like_announcements';

    /**
     * Додає новий запис у таблицю "user_like_announcements".
     *
     * @param int $user_id Ідентифікатор користувача.
     * @param int $announcement_id Ідентифікатор оголошення.
     */
    public static function AddRow($user_id, $announcement_id)
    {
        $announcement = new UserLikeAnnouncements();  // Створюємо новий об'єкт
        $announcement->user_id = $user_id;            // Призначаємо користувача
        $announcement->announcement_id = $announcement_id;  // Призначаємо оголошення
        $announcement->save();  // Зберігаємо запис у базі даних
    }

    /**
     * Перевіряє, чи є оголошення вподобаним користувачем.
     *
     * @param int $userId Ідентифікатор користувача.
     * @param int $announcementId Ідентифікатор оголошення.
     * @return bool true, якщо оголошення є вподобаним, false — якщо ні.
     */
    public static function IsFavorite($userId, $announcementId)
    {
        // Шукаємо запис в таблиці по умові user_id та announcement_id
        $favorite = self::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);
        return $favorite ? true : false;  // Повертаємо результат перевірки
    }

    /**
     * Видаляє запис про вподобання оголошення користувачем.
     *
     * @param int $user_id Ідентифікатор користувача.
     * @param int $announcement_id Ідентифікатор оголошення.
     */
    public static function RemoveRow($user_id, $announcement_id)
    {
        // Видаляємо запис по умові user_id та announcement_id
        self::deleteByCondition(["user_id" => $user_id, "announcement_id" => $announcement_id]);
    }

    /**
     * Отримує список всіх вподобаних оголошень для заданого користувача.
     *
     * @param int $userId Ідентифікатор користувача.
     * @return array Список оголошень, що вподобані користувачем.
     */
    public static function getSelectedAnnouncements($userId)
    {
        // Шукаємо всі оголошення користувача за user_id
        return self::findByCondition(['user_id' => $userId]);
    }

    /**
     * Підраховує загальну кількість вподобаних оголошень в таблиці.
     *
     * @return int Кількість всіх записів у таблиці.
     */
    public static function CountAll()
    {
        // Підраховуємо всі записи в таблиці
        return self::findRowsByCondition('COUNT(*) as count');
    }

    /**
     * Підраховує кількість вподобаних оголошень для конкретного оголошення.
     *
     * @param int $announcementId Ідентифікатор оголошення.
     * @return int Кількість користувачів, які вподобали це оголошення.
     */
    public static function CountByAnnouncementId($announcementId)
    {
        // Підраховуємо записи по конкретному оголошенню
        return self::findRowsByCondition('COUNT(*) as count', ['announcement_id' => $announcementId]);
    }
}
