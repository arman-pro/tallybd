<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\LogActivity as AppLogActivity;
use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    

    public function drive_upload()
    {
         $file_name ="RahmanTraders-". date('d-M-y') . '-'. rand() . '.zip';
        \Artisan::call('backup:run --only-db --filename='.$file_name);
        $filePath = storage_path() . "/app/Laravel/".$file_name;
        if(file_exists($filePath)){
            $fileData = \File::get($filePath);
            if(\Storage::cloud('google')->put($file_name, $fileData)){
                unlink($filePath);
                return back()->with('message', 'Database backup successfull!');
            }
        }
    }

    public function backup()
    {
        $path    = storage_path() . "/app/backup/";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        return view('backup', compact('files'));
    }

    public function backupDownload()
    {
        Artisan::call('database:backup');
        return back();
    }


    public function singlebackupDelete($fileName)
    {
        $file_path    = storage_path() . "/app/backup/".$fileName;
        unlink($file_path);
        return back();
    }

    public function singlebackupDownload($fileName)
    {
        $file_path    = storage_path() . "/app/backup/".$fileName;
        return response()->download($file_path);
    }


    public function myTestAddToLog()
    {
        LogActivity::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity()
    {
        $logs = LogActivity::logActivityLists();
        return view('logActivity',compact('logs'));
    }

    public function logActivityDelete($id)
    {
        AppLogActivity::findOrFail($id)->delete();
        return back();
    }
     public function logActivityDeleteCron()
    {
        $logs = AppLogActivity::get();
        foreach($logs as $log){
            $log->delete();
        }
        echo "All Delete ok";
    }
}
