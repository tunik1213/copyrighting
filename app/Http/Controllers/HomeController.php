<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->except(['getMiniAvatarImage','profile']);
    }

    public function index()
    {
        $user = auth()->user();
        return view('home.index',['user' => $user]);
    }

    public function updateUser()
    {
        $user = auth()->user();
        $user->name = $_POST['name'];
        $user->specialization = $_POST['specialization'];
        $user->gender = ($_POST['gender']=='') ? null : (bool)$_POST['gender'];

        if (strtotime($_POST['birthdate']))
            $user->birthdate = $_POST['birthdate'];

        if (!empty($_FILES['avatar']['tmp_name'])){
            $user->avatar = Image::make($_FILES['avatar']['tmp_name'])
                ->fit(800)
                ->encode('jpg', 75);
            $user->avatar_mini = Image::make($_FILES['avatar']['tmp_name'])
                ->fit(50)
                ->encode('jpg', 75);
        }

        $user->save();
        return view('home.index',['user' => $user]);
    }

    public function getAvatarImage()
    {
        $user = auth()->user();
        $avatar = $user->avatar;
        if ($avatar == null) {
            $avatar = file_get_contents(public_path().'/avatars/no_foto.jpeg');
        }

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($avatar));

        echo($avatar);
        exit;
    }

    public function getMiniAvatarImage($userId = null)
    {
        $user = ($userId === null) ? auth()->user() : User::find($userId);
        $avatar = $user->avatar_mini;
        if ($avatar == null) {
            $avatar = file_get_contents(public_path().'/avatars/no_foto_mini.jpeg');
        }

        header("Content-Type: image/jpg");
        header("Content-Length: " . strlen($avatar));

        echo($avatar);
        exit;
    }

    public function profile($userId)
    {
        $user = User::find($userId);

        $articles = Article::where('moderatedBy','<>',null)
            ->where('authorId','=',$user->id)
            ->orderBy('id', 'desc')
            ->simplePaginate(10);

        $comments = Comment::where('authorId','=',$user->id)
            ->where('parentId',0)
            ->orderBy('id', 'desc')
            ->simplePaginate(50);

        return view('home.profile',
            [
                'user' => $user,
                'articles' => $articles,
                'comments' => $comments
            ]
        );
    }

}
