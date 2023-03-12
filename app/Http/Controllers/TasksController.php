<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;   
class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        if (\Auth::check()) { // 認証済みの場合
           
      // 認証済みユーザを取得
            $user = \Auth::user();
           $tasks = $user->tasks()->orderBy('id', 'asc')->paginate(10);

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [     // 追加
            'tasks' => $tasks,        // 追加
        ]);                                 // 追加
  
 
       
        }
         return redirect('dashboard');
        
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        //
        if (\Auth::check()) { // 認証済みの場合
        
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
            }
                    // トップページへリダイレクトさせる
        return redirect('/');
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        
           if (\Auth::check()) { // 認証済みの場合
        
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
                // メッセージを作成
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id=\Auth::id();
        $task->save();

   // トップページへリダイレクトさせる
        return redirect('/');
}
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
           
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $task = Task::findOrFail($id);
            if($task->user_id== \Auth::id()){
            // メッセージ詳細ビューでそれを表示
      // メッセージ詳細ビューでそれを表示
                return view('tasks.show', [
                    'task' => $task,
                ]);
            }
                    // トップページへリダイレクトさせる
        return redirect('/');
   
        }
        
       
        
        // トップページへリダイレクトさせる
        return redirect('/');
        /*
            // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
        
        */
        
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
          if (\Auth::check()) { // 認証済みの場合
        
        // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
                // トップページへリダイレクトさせる
        return redirect('/');
           }
                   // トップページへリダイレクトさせる
        return redirect('/');
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        
        if (\Auth::check()) { // 認証済みの場合
        
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
                // idの値でメッセージを検索して取得
        $task = task::findOrFail($id);
        // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
            // トップページへリダイレクトさせる
        return redirect('/');
            
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        if (\Auth::check()) { // 認証済みの場合
        
        // idの値で投稿を検索して取得
        $task = task::findOrFail($id);
        
           // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        }
}
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}