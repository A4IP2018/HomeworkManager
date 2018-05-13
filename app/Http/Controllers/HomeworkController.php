<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Format;
use App\Homework;
use App\Role;
use App\StudentInformation;
use App\User;
use App\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HomeworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homeworks = Homework::orderBy('id', 'desc')->get();

        return view('homework', compact('homeworks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formats = Format::all();

        $currentTeacher = User::where('id', Auth::id())->whereHas('role', function ($query) {
            $query->where('rank', Role::$TEACHER_RANK);
        })->first();

        $teacherCourses = $currentTeacher->courses;

        return view('new-homework', compact('formats', 'currentTeacher', 'teacherCourses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'deadline' => 'required',
            'format' => 'required',
        ]);

        $selectedFormats = $request->input('format');
        $deadline = $request->input('deadline');
        $description = $request->input('description');
        $course = $request->input('course');
        $title = $request->input('name');

        $slug = str_slug($title);
        $count = Homework::where('slug', $slug)->count();

        $slug = $count > 0 ? ($slug . '-' . ($count + 1)) : $slug;

        $formats = Format::whereIn('id', $selectedFormats)->get();

        $homework = Homework::create([
            'course_id' => $course,
            'name' => $title,
            'description' => $description,
            'slug' => $slug,
            'category_id' => 1,
            'user_id' => Auth::id(),
            'deadline' => $deadline,
        ]);

        $homework->format()->sync($formats);

        return redirect()->back()->withErrors($validator);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $homework = Homework::where('slug', $slug)->first();
        $comments = Comment::where('homework_id', $homework->id)->get();

        return view('homework-sg', compact('comments', 'homework'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('edit-homework');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Upload view return
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadView($slug)
    {
        $homework = Homework::where('slug', $slug)->first();

        return view('upload', compact('homework'));
    }

    /**
     * Upload comment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadComment(Request $request)
    {
        $comment = $request->input('comments');
        $homeworkId = $request->input('homework-id');

        Comment::create([
            'comment' => $comment,
            'homework_id' => $homeworkId,
            'users_id' => Auth::id()
        ]);

        return redirect()->back();

    }


    /**
     * Upload homework functionality
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadHomework(Request $request)
    {

        $path = public_path() . '/files/';
        $image = $request->file('fileToUpload');

        $filename = time() . '.' . $image->getClientOriginalName();
        $fileType = $request->file('fileToUpload')->getClientOriginalExtension();
        $fileExtension = $request->file('fileToUpload')->guessExtension();

        $user_id = Auth::id();

        if ($user_id == null) {
            return redirect('/login')->withErrors('Trebuie sa fiti autentificat pentru a uploada o tema.');
        }

        if ($fileType != $fileExtension) {
            return redirect()->back()->withErrors('Fisier invalid: extensia nu corespunde cu continutul.');
        }
        $homework_id = $request->input('homework-id');

        $homework = Homework::find($homework_id);
        $extensions = $homework->format()->get();

        $extensionOk = 0;
        foreach ($extensions as $extension) {
            if (str_replace('.', '', $extension->extension_name)== $fileType) {
                $extensionOk = 1;
                break;
            }
        }

        if ($extensionOk == 0) {
            return redirect()->back()->withErrors('Extensie neacceptata.');
        }

        if ($request->file('fileToUpload')->getClientSize() > 500000) {
            return redirect()->back()->withErrors('Fisierul este prea mare.');
        }

        if ($image->move($path, $filename)) {

            \App\File::create([
                'user_id' => $user_id,
                'homework_id' => $homework_id,
                'file_name' => $filename
            ]);
            return redirect()->back()->withErrors('Fisier uploadat cu succes.');
        } else {
            return redirect()->back()->withErrors('Eroare la upload.');
        }
    }

    /**
     * Teacher gives/updates grade
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function updateGrade(Request $request)
    {
        $homeworkId = $request->input('homework-id');
        $grade = $request->input('grade');
        $userId = $request->input('user-id');

        Grade::create([
            'grade' => $grade,
            'user_id' => $userId,
            'homework_id' => $homeworkId
        ]);

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function studentUploadsView()
    {
        $files = \App\File::with('user', 'user.student_information')->get();
        return view('stud-uploads', compact('files'));
    }

    /**
     * @param $userId
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function studentUploadView($userId, $slug)
    {
        $user = User::find($userId);
        $homework = Homework::where('slug', $slug)->first();
        $grade = Grade::where('user_id', $user->id)->where('homework_id', $homework->id)->first();

        return view('stud-uploads-sg', compact('homework', 'user', 'grade'));
    }
}