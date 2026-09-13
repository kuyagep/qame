<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->has('is_active') ? true : false,
            'created_by' => auth()->id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement published successfully!',
                'data' => $announcement
            ]);
        }

        return redirect()->back()->with('success', 'Announcement published successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $announcement->is_active,
                'message' => 'Status updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Announcement status updated!');
    }

    public function destroy(Request $request, $id)
    {
        Announcement::findOrFail($id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Announcement deleted!');
    }
}
