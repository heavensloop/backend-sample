<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ["id"];

    public static function addNew($ip_address, $episode_id, $message) {
        $comment = new self([
            "ip_address" => $ip_address,
            "episode_id" => $episode_id,
            "message" => $message
        ]);

        $comment->save();

        return $comment;
    }

    function isValidIp($ip_address) {
        return preg_match("/\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}/", $ip_address);
    }

    protected function guardFromInvalidIp() {
        $ip_address = $this->attributes["ip_address"];

        if (!$this->isValidIp($ip_address)) {
            throw new \ErrorException("Invalid IP Address", 422);
        }
    }

    protected function guardFromLongMessage() {
        $message = $this->attributes["message"];

        if (strlen(trim($message)) < 2) {
            throw new \ErrorException("Message length must be more than two characters", 400);
        } else if (strlen($message) > 500) {
            throw new \ErrorException("Message length cannot be greater than 500", 413);
        }
    }

    public function save($options=[]) {
        $this->guardFromInvalidIp();
        $this->guardFromLongMessage();

        return parent::save($options);
    }

    public static function scopeForEpisode($query, $episode_id) {
        return $query->whereEpisodeId($episode_id);
    }
}
