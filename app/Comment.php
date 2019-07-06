<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ["id"];

    public static function addNew($ip_address, $message) {
        $comment = new self([
            "ip_address" => $ip_address,
            "message" => $message
        ]);

        $comment->save();

        return $comment;
    }

    public function setMessageAttribute($message) {
        if (strlen($message) > 500) {
            throw new \ErrorException("Message length cannot be greater than 500");
        }

        $this->attributes["message"] = $message;
    }
}
