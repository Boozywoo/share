<?php

namespace App\Services\Wishes;



use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Models\Wishes;
use App\Models\WishesComment;
use App\Models\WishesCommentFile;
use App\Models\WishesType;
use App\Services\Notification\NotificationService;
use App\Models\WishesHistory;
use Auth;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;
use Illuminate\Http\UploadedFile;

class WishesService
{
    private $wishes;
    private $notificationService;

    public static function create($id = null) : WishesService
    {
        if (!$id){
            $wishes = new Wishes();
        }else{
            $wishes = Wishes::findOrFail($id);
        }
        return new self($wishes);
    }


    public function __construct(Wishes $wishes)
    {
        $this->wishes = $wishes;
        $this->notificationService = new NotificationService();
    }

    public function getWishes(): Wishes
    {
        return $this->wishes;
    }

    public function getComments()
    {
        return $this->wishes->comments;
    }

    public function getWishesTypes()
    {
        return WishesType::where('status', '=', 1)->get()->pluck('name','id');
    }

    public function store($request){
        if($request->get('id')){
            if($request->exists('new_comment') && $request->get('new_comment') != ''){
                $this->newComment($request->get('new_comment'));
            }

            if (Auth::user()->id != $this->wishes->applicant->id || !Auth::user()->isSuperadmin || !Auth::user()->isModerator){
                $this->wishes->fill(request()->all());
                if($request->get('wishes_type')){
                    $this->wishes->wishes_type_id = $request->get('wishes_type');
                }
                $this->wishes->save();
                $this->addHistory('changeInfo', "Обновлено пользователем " . auth()->user()->full_name . '.');
            }

            return;
        }

        $this->wishes->status = 'new';
        $this->wishes->applicant_id = auth()->id();
        $this->wishes->delegate_id = Auth::user()->chief_user;

        $this->wishes->wishes_type_id = $request->get('wishes_type');
        $this->wishes->fill(request()->all());
        $this->wishes->save();

        if(!empty($files = request()->file('files'))){
            /**@var UploadedFile $file*/
            $directory = 'wishes/files';
            foreach ($files as $file){
                $src = Storage::disk('public')->putFile($directory, $file);
                $this->wishes->files()->create(
                    [
                        'wishes_id' => $this->wishes->id,
                        'original_name' => $file->getClientOriginalName(),
                        'name' => basename($src),
                        'src' => $src,
                        'size' => $file->getSize(),
                        'extension' => $file->getClientOriginalExtension(),
                        'type' => WishesCommentFile::getFileType($file->getClientOriginalExtension()),
                    ]
                );
            }
        }

        $this->notification('new');
    }

    public function newComment($commentText){
        $comment = new WishesComment();
        $comment->comment = $commentText;
        $comment->user_id = auth()->id();
        $comment->wishes_id = $this->wishes->id;
        $comment->save();
        if (request()->allFiles()){
            foreach (request()->allFiles()['files'] as $file){
                $fileName = 'wishes/files/'. rand(00000000, 99999999) . $file->getClientOriginalName();
                    if (\Storage::disk()->put('public/'.$fileName, file_get_contents($file))) {
                       $comment->files()->create([
                            'type' => WishesCommentFile::getFileType($file->getClientOriginalExtension()),
                            'file'  => $fileName,
                            'originalName'  => $file->getClientOriginalName(),
                            'wishes_comment_id' => $comment->id
                        ]);
                    }
            }
        }
        $this->addHistory('comment', "Добавлен комментарий от пользователя :" . auth()->user()->full_name . '.', WishesComment::class, $comment->id);
    }

    public function changeStatus($status){
        if ($this->wishes->status != $status){
            $this->wishes->status = $status;
            $this->wishes->save();
            $this->addHistory('changeStatus', "Пользователь ". auth()->user()->full_name . " сменил статус на ". $status . '.');
            if ($status == 'completed'){
                $this->notification('completed');
            }
        }
    }
    public function complete($request){
        $this->wishes->status = 'completed';
        $this->wishes->save();

        $comment = new WishesComment();
        $comment->comment = $request->get('comment_complete');
        $comment->user_id = auth()->id();
        $comment->wishes_id = $request->get('id');
        $comment->save();
        $this->addHistory('comment', "Добавлен комментарий от пользователя :" . auth()->user()->full_name . '.', WishesComment::class, $comment->id);

        $this->addHistory('changeStatus', "Пользователь ". auth()->user()->full_name . " завершил заявку.");
        $this->notification('completed');

    }

    public function getDelegateData() :array
    {
        return [
            'users' => User::getUserCompany($this->wishes->applicant->company_id),
        ];
    }

    public function delegate($request){
        $user = User::find($request->get('delegate_id'));
        $this->wishes->delegate_id = $request->get('delegate_id');
        $this->wishes->save();
        $this->addHistory('delegate', "Пользователь ". auth()->user()->full_name . 'делегировал заявку пользователю -' .$user->full_name . '.' );
        $this->notification('delegate');
    }

    private function addHistory($action, $text, $instance = null, $instance_id = null)
    {
        $history = new WishesHistory();
        $history->wishes_id = $this->wishes->id;
        $history->action = $action;
        $history->text = $text;
        $history->instance = $instance;
        $history->instance_id = $instance_id;
        $history->save();
    }

    public function getHistory()
    {
        return $this->wishes->history;
    }

    private function notification($type)
    {
        if ($this->wishes){
            switch ($type){
                case 'new':
                    // Notification create wishes
                    $data = [
                        'source' => trans('admin.notifications.text.wishes'),
                        'small_text' => trans('admin.notifications.text.small_text_wishes')
                    ];
                    $this->notificationService->notify($this->wishes, $data);
                    break;
                case 'updateInfo':
                    // Notification update info
                    break;
                case 'changeStatus':
                    // Notification change status
                    break;
                case 'completed':
                    // Notification completed status
                    $data = [
                        'source' => trans('admin.notifications.text.wishes_completed'),
                        'small_text' => trans('admin.notifications.text.small_text_wishes_completed')
                    ];
                    $this->notificationService->notify($this->wishes, $data);

                    break;
                case 'newComment':
                    // Notification new comment
                    break;
                case 'delegate':
                    $data = [
                        'source' => trans('admin.notifications.text.wishes_delegate'),
                        'small_text' => trans('admin.notifications.text.small_text_wishes_delegate')
                    ];
                    $this->notificationService->notify($this->wishes, $data);

                    break;

            }
        }
    }
}
