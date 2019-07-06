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

    public function setMessageAttribute($message) {
        if (strlen(trim($message)) < 2) {
            throw new \ErrorException("Message length must be more than two characters");
        } else if (strlen($message) > 500) {
            throw new \ErrorException("Message length cannot be greater than 500");
        }

        $this->attributes["message"] = $message;
    }

    function isValidIp($ip_address) {
        return preg_match("/\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}/", $ip_address);
    }

    public function setIpAddressAttribute($ip_address) {
        if (!$this->isValidIp($ip_address)) {
            throw new \ErrorException("Invalid ip address");
        } else if (strlen($ip_address) > 500) {
            throw new \ErrorException("Message length cannot be greater than 500");
        }

        $this->attributes["ip_address"] = $ip_address;
    }
}
