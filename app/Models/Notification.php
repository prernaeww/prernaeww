<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{

    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [

        'user_id',
        'order_id',
        'title',
        'message',
        'type',
        'seen'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = ['time_ago', 'image'];


    public function getTimeAgoAttribute()
    {
        $now = new \DateTime;
        $ago = new \DateTime($this->created_at);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        $full = FALSE;
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';

    }

    public function getImageAttribute() {
        return url('/images/logo.png');
    }

}

