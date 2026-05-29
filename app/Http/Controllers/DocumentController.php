<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $ownDocuments = $user->documents()->latest('updated_at')->get();
        $sharedDocuments = $user->sharedDocuments()->latest('updated_at')->get();

        $documents = $ownDocuments->merge($sharedDocuments)->sortByDesc('updated_at');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $documents = $documents->filter(function ($doc) use ($search) {
                return str_contains(strtolower($doc->title), $search);
            });
        }

        return view('dashboard', compact('documents'));
    }

    public function create(Request $request)
    {
        $document = Document::create([
            'title' => 'Dokumen Baru',
            'content' => '',
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('documents.show', $document->uuid);
    }

    public function show(string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();
        $user = Auth::user();

        $isOwner = $document->owner_id === $user->id;
        $isCollaborator = $document->collaborators()->where('user_id', $user->id)->exists();

        if (!$isOwner && !$isCollaborator) {
            $document->collaborators()->attach($user->id, ['role' => 'penyunting']);
        }

        return view('editor', [
            'document' => $document,
            'user' => $user,
            'isOwner' => $isOwner,
        ]);
    }

    public function update(Request $request, string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        if ($request->has('title')) {
            $document->title = $request->input('title');
        }

        if ($request->has('content')) {
            $document->content = $request->input('content');
        }

        $document->save();

        return response()->json(['success' => true, 'document' => $document]);
    }

    public function destroy(string $uuid)
    {
        $document = Document::where('uuid', $uuid)
            ->where('owner_id', Auth::id())
            ->firstOrFail();

        $document->delete();

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function versions(string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        $versions = $document->versions()
            ->with('savedByUser:id,name')
            ->get()
            ->map(function ($version) {
                return [
                    'id' => $version->id,
                    'version_number' => $version->version_number,
                    'saved_by' => $version->savedByUser->name ?? 'Tidak diketahui',
                    'created_at' => $version->created_at->diffForHumans(),
                    'created_at_formatted' => $version->created_at->format('d M Y H:i'),
                    'content_preview' => mb_substr(strip_tags($version->content), 0, 150) . '...',
                ];
            });

        return response()->json($versions);
    }

    public function saveVersion(Request $request, string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'content' => 'required|string',
        ]);

        $nextVersion = ($document->latest_version_number ?? 0) + 1;

        $document->content = $request->input('content');
        $document->save();

        DocumentVersion::create([
            'document_id' => $document->id,
            'content' => $request->input('content'),
            'version_number' => $nextVersion,
            'saved_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'version_number' => $nextVersion,
        ]);
    }

    public function save(Request $request, string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        $document->content = $request->content;
        $document->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function restoreVersion(string $uuid, int $versionId)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();
        $version = DocumentVersion::where('document_id', $document->id)
            ->where('id', $versionId)
            ->firstOrFail();

        $nextVersion = ($document->latest_version_number ?? 0) + 1;

        DocumentVersion::create([
            'document_id' => $document->id,
            'content' => $document->content,
            'version_number' => $nextVersion,
            'saved_by' => Auth::id(),
        ]);

        $document->update(['content' => $version->content]);

        return response()->json([
            'success' => true,
            'content' => $version->content,
            'version_number' => $nextVersion,
        ]);
    }

    public function history(string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        $versions = $document->versions()
            ->with('savedByUser')
            ->orderByDesc('version_number')
            ->get();

        return view('documents.history', compact('document', 'versions'));
    }

    public function restoreVersionPage(string $uuid, int $versionId)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();
        $version = DocumentVersion::where('document_id', $document->id)
            ->where('id', $versionId)
            ->firstOrFail();

        $nextVersion = ($document->latest_version_number ?? 0) + 1;

        DocumentVersion::create([
            'document_id' => $document->id,
            'content' => $document->content,
            'version_number' => $nextVersion,
            'saved_by' => Auth::id(),
        ]);

        $document->update(['content' => $version->content]);

        return redirect()->route('documents.history', $document->uuid)->with('success', 'Versi berhasil dipulihkan.');
    }

    public function saveSnapshot(Request $request, string $uuid)
    {
        $document = Document::where('uuid', $uuid)->firstOrFail();

        $document->update([
            'content' => $request->input('content'),
        ]);

        return response()->json(['success' => true]);
    }
}
