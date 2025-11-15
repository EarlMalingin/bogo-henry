<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\Tutor;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            // student_id is automatically generated and cannot be changed
            'year_level' => 'required|string',
            'course' => 'required|string',
            'subjects_interest' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|confirmed|min:8',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Use the disk specified in .env, or default to 'public'
            $diskName = env('FILESYSTEM_DISK', 'public');
            
            if ($student->profile_picture) {
                // Try to delete from both disks
                try {
                    \Storage::disk('public')->delete($student->profile_picture);
                } catch (\Exception $e) {
                    // Ignore if file doesn't exist
                }
                if ($diskName !== 'public') {
                    try {
                        \Storage::disk($diskName)->delete($student->profile_picture);
                    } catch (\Exception $e) {
                        // Ignore if disk doesn't exist or file doesn't exist
                    }
                }
            }
            
            // Store in the configured disk
            $path = $request->file('profile_picture')->store('profile-pictures', $diskName);
            $validated['profile_picture'] = $path;
        }

        // Handle password change
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (\Hash::check($request->input('current_password'), $student->password)) {
                $validated['password'] = bcrypt($request->input('new_password'));
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Remove student_id from validated data if present (should not be updated)
        unset($validated['student_id']);
        
        $student->update($validated);
        // Refresh the authenticated user in the session
        Auth::guard('student')->setUser($student->fresh());

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function profilePicture()
    {
        $student = Auth::guard('student')->user();

        if (!$student->profile_picture) {
            abort(404, 'Profile picture not found');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($student->profile_picture)) {
            abort(404, 'Profile picture file not found');
        }

        $filePath = Storage::disk('public')->path($student->profile_picture);
        $fileName = basename($student->profile_picture);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'image/jpeg';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function viewStudentPicture($id)
    {
        try {
            $student = Student::findOrFail($id);

            if (!$student->profile_picture) {
                abort(404, 'Profile picture not found');
            }

            // Determine which disk to use based on environment
            $diskName = env('FILESYSTEM_DISK', 'public');
            
            // Try multiple possible paths (for different hosting configurations)
            // Priority: public_html_storage (Hostinger) > public > standard storage
            $possiblePaths = [];
            
            // 1. Check public_html_storage (Hostinger setup)
            // Try multiple possible locations for public_html/storage
            $publicHtmlPaths = [
                base_path('public_html/storage/' . $student->profile_picture), // If Laravel is in public_html
                base_path('../public_html/storage/' . $student->profile_picture), // If Laravel is in a subdirectory
                dirname(base_path()) . '/public_html/storage/' . $student->profile_picture, // Parent directory
            ];
            
            if ($diskName === 'public_html_storage') {
                foreach ($publicHtmlPaths as $path) {
                    $possiblePaths[] = $path;
                }
            } else {
                // Still check if the directory exists
                foreach ($publicHtmlPaths as $path) {
                    if (file_exists(dirname($path))) {
                        $possiblePaths[] = $path;
                        break; // Only add one if directory exists
                    }
                }
            }
            
            // 2. Check public/storage (symlink or direct)
            $possiblePaths[] = public_path('storage/' . $student->profile_picture);
            
            // 3. Check standard Laravel storage
            $possiblePaths[] = Storage::disk('public')->path($student->profile_picture);
            $possiblePaths[] = storage_path('app/public/' . $student->profile_picture);
            $possiblePaths[] = base_path('storage/app/public/' . $student->profile_picture);
            
            // 4. Check if using custom disk
            if ($diskName !== 'public' && $diskName !== 'local') {
                try {
                    $possiblePaths[] = Storage::disk($diskName)->path($student->profile_picture);
                } catch (\Exception $e) {
                    // Disk doesn't exist, skip
                }
            }

            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path) && is_file($path)) {
                    $filePath = $path;
                    break;
                }
            }

            if (!$filePath) {
                // Log for debugging
                \Log::warning('Student profile picture not found', [
                    'student_id' => $id,
                    'profile_picture' => $student->profile_picture,
                    'filesystem_disk' => $diskName,
                    'tried_paths' => $possiblePaths,
                ]);
                abort(404, 'Profile picture file not found');
            }

            $fileName = basename($student->profile_picture);
            
            // Determine content type
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $contentTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            
            $contentType = $contentTypes[$extension] ?? 'image/jpeg';

            return response()->file($filePath, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            $student = Student::find($id);
            \Log::error('Error loading student profile picture: ' . $e->getMessage(), [
                'student_id' => $id,
                'profile_picture' => $student->profile_picture ?? 'not set',
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'Profile picture not found');
        }
    }

    public function viewTutorPicture($id)
    {
        $tutor = Tutor::findOrFail($id);

        if (!$tutor->profile_picture) {
            abort(404, 'Profile picture not found');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($tutor->profile_picture)) {
            abort(404, 'Profile picture file not found');
        }

        $filePath = Storage::disk('public')->path($tutor->profile_picture);
        $fileName = basename($tutor->profile_picture);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'image/jpeg';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
} 