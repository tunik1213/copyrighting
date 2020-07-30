<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
Use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewArticleNotification;
use App\Comment;

class Article extends Model
{
    protected $fillable = array(
        'authorId',
        'title',
        'slug',
        'annotation',
        'content',
        'finished',
        'moderatedBy'
    );

    public function url() : string
    {
        if (empty($this->slug))
            $p = $this->id;
        else
            $p = $this->slug;

        return url('/article/'.$p);
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'authorId');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'articleId');
    }

    public function public() : bool
    {
        return ($this->moderatedBy <> null);
    }

    public function canEdit() : bool
    {
        $user = Auth::user();
        if (!$user)
            return false;

        // суперадмин может все
        if ($user->superAdmin())
            return true;

        // модератор может редактировать все завершенные статьи, включая уже опубликованные
        if ($user->moderator && $this->finished == 1)
            return true;

        // юзер может редактировать свои незавершенные статьи (черновики)
        if ($this->finished == 0 && $this->authorId == $user->id)
            return true;


        return false;
    }

    public static function createNew() : Article
    {
        return self::firstOrCreate([
            'authorId'=>Auth::user()->id,
            'finished'=>false
        ]);
    }

    // отправляет всем подписчикам письмо о том что появилась новая статья
    public function newArticleEmailNotification() : void
    {
        $receivers = User::where('article_notifications','=',true)->get();
        foreach ($receivers as $receiver) {
            $email = (new NewArticleNotification($this, $receiver))
                ->onQueue('low');

            Mail::to($receiver)
                ->queue($email);
        }

    }

    public function lastComment() : ?Comment
    {
        return Comment::where('articleId',$this->id)
            ->orderBy('id','desc')
            ->take(1)
            ->get()[0] ?? null;

    }

    public function lastModificationDate() : Carbon
    {
        return max(
            $this->updated_at,
            $this->lastComment()->updated_at ?? null
        );
    }
}
