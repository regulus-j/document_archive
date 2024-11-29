<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Controller\Controller\User;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Team;

class FolderController extends Controller
{
    //
    public function index()
    {
        return 0;
    }

    public function getTeamsAndFolders()
    {
        $teams = Team::all();
        $folders = Storage::directories('/path/to/folders');
        return response()->json(['teams' => $teams, 'folders' => $folders]);
    }

    public function create()
    {
        $teams = Team::all();
        return view('documents.partials.folder_create', ['teams' => $teams]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'exists:teams,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'team_id' => $request->team_id,
        ]);

        return back()->with('success', 'Folder created successfully.');
    }
}
