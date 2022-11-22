<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $user_id
 * @property mixed $responsible
 * @property array|\Illuminate\Contracts\Translation\Translator|\Illuminate\Foundation\Application|mixed|string|null $source
 * @property array|\Illuminate\Contracts\Translation\Translator|\Illuminate\Foundation\Application|mixed|string|null $small_text
 * @property mixed|string $text
 * @property mixed|string $source_url
 * @property int|mixed $approved
 * @property int|mixed $read
 * @property int|mixed $denied
 * @property int|mixed $new
 * @property int|mixed $for_all
 * @property mixed $type_id
 */
class Notification extends Model
{
    protected $fillable = [
        'source', 'source_url', 'user_id', 'small_text', 'text', 'status', 'read','denied', 'approved', 'for_all', 'responsible'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(NotificationType::class, 'type_id');
    }

    public function responsible()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id');
    }

    public static function getRowsForIndex($filter)
    {
        $result = Notification::whereHas('responsible', function ($q){
            $q->where('user_id', auth()->user()->id);
        });

        if (array_key_exists('status', $filter) && $filter['status']) {
            if($filter['status'] == 'new-read'){
                $result->where('approved', '=', '0');
                $result->where('denied', '=', '0');
            }else{
                $result->where($filter['status'], '1');
            }
        }

        if($filter['create-date']){
            $date = Carbon::parse($filter['create-date'])->format('Y-m-d');
            $result->whereDate('created_at', '=', $date);
        }


        if($filter['treatment-date']){
            $date = Carbon::parse($filter['treatment-date'])->format('Y-m-d');
            $result->whereDate('updated_at', '=', $date);
        }
        $result->orderBy('id', 'DESC');

        return $result->paginate();

    }
}
