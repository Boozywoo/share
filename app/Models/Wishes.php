<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use App\Traits\ModelTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Wishes extends Model
{
    use ImageableTrait, ModelTableTrait;

    protected $fillable = [
       'status', 'applicant_id', 'wishes_type_id', 'delegate_id', 'subject','comment'
    ];

    protected $hidden = [
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class,'applicant_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(WishesComment::class);
    }
    public function history()
    {
        return $this->hasMany(WishesHistory::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class,'delegate_id', 'id');
    }

    public function wishesType()
    {
        return $this->belongsTo(WishesType::class,'wishes_type_id', 'id');
    }

    public function getDelegateNameAttribute()
    {
        return $this->delegate ? $this->delegate->fullname : '----';
    }

    public function getLastCommentAttribute(){
        return $this->comments()->latest()->first() ?  str_limit($this->comments()->latest()->first()->comment, 50) : '';
    }
    public function getLastCommentsAttribute(){
        $commentString = '';
        if($this->comments()){
            foreach ($this->comments()->limit(5)->latest()->get() as $comment){
                $commentString .= "
                              <br>".$comment->created_at->format('d.m.Y H:i')." 
                              <br>".$comment->user->fullname.":
                              <br>".$comment->comment." 
                              <br> ";
            }
        }
        return $commentString ;
    }

    public function getDepartmentDelegateAttribute()
    {
        if ($this->delegate and $this->delegate->departament){
            return $this->delegate->departament->name;
        }
        return '----';
    }

    public static function getListByStatus($status)
    {
        $result = Wishes::orderBy('id',  'desc');

        if ($status){
          $result->where('status', '=', $status);
        }

        if(!Auth::user()->isSuperadmin && !Auth::user()->isModerator) {
            $resultCollect = collect();
            $result->get()->each(function (Wishes $wishes) use ($resultCollect) {
                if($wishes->access()) {
                    $resultCollect->push($wishes);
                }
            });
            return $resultCollect;
        }

        return $result;
    }

    public function access($user = null)
    {
        $wishes = $this;
        $user = $user ?? Auth::user();

        if ($user->isSuperadmin || $user->isModerator) {
            return true;
        }

        if ($wishes->applicant_id == $user->id || $wishes->delegate_id == $user->id) {
            return true;
        }

        if ($wishes->applicant->superior && $this->checkSuperiorAccess($wishes->applicant->superior, $user)) {
            return true;
        }

        if ($wishes->wishesType->departments) {
            $departments_notification = json_decode($wishes->wishesType->departments_notification) ?: [];
            foreach ($wishes->wishesType->departments as $department){
                if ($department->director_id == $user->id) {
                    return true;
                }

                if (in_array($department->id, $departments_notification, true) && $department->id == $user->department_id) {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkSuperiorAccess(User $superior, User $user)
    {
        if ($superior->id === $user->id) {
            return true;
        }

        if ($superior->superior) {
            return $this->checkSuperiorAccess($superior->superior, $user);
        }

        return false;
    }

    public function accessComplete()
    {
        return Auth::user()->id === $this->delegate_id;
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(WishesFile::class);
    }
}

