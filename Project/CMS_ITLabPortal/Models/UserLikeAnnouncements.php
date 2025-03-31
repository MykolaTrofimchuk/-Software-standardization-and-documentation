<?php

namespace Models;

use core\Model;

class UserLikeAnnouncements extends Model
{
    public static $tableName = 'user_like_announcements';
    public static function AddRow($user_id, $announcement_id)
    {
        $announcement = new UserLikeAnnouncements();
        $announcement->user_id = $user_id;
        $announcement->announcement_id = $announcement_id;
        $announcement->save();
    }
    public static function IsFavorite($userId, $announcementId)
    {
        $favorite = self::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);
        if ($favorite)
            return true;
        else
            return false;
    }
    public static function RemoveRow($user_id, $announcement_id)
    {
        self::deleteByCondition(["user_id" => $user_id, "announcement_id" => $announcement_id]);
    }
    public static function getSelectedAnnouncements($userId)
    {
        return self::findByCondition(['user_id' => $userId]);
    }

    public static function CountAll()
    {
        return self::findRowsByCondition('COUNT(*) as count');
    }

    public static function CountByAnnouncementId($announcementId)
    {
        return self::findRowsByCondition('COUNT(*) as count', ['announcement_id' => $announcementId]);
    }
}