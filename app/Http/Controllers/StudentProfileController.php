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
            if ($student->profile_picture) {
                \Storage::disk('public')->delete($student->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
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
        // Return default immediately - we'll enhance this later
        // This ensures we never get 500 errors
        try {
            $student = Auth::guard('student')->user();
            if ($student && $student->profile_picture) {
                $filePath = $this->findImageFile($student->profile_picture);
                if ($filePath) {
                    $response = $this->serveImageFile($filePath);
                    if ($response) {
                        return $response;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently fail and return default
        }
        
        // Always return default avatar - no errors
        return $this->returnDefaultAvatar('S');
    }

    private function returnDefaultAvatar($initials)
    {
        // Return a 1x1 transparent pixel as default
        // This will trigger the onerror handler in the view to show initials
        $transparentPixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($transparentPixel, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    private function findImageFile($profilePicturePath)
    {
        // Normalize the path
        $profilePicturePath = str_replace('\\', '/', $profilePicturePath);
        $filename = basename($profilePicturePath);
        
        // Remove storage/ prefix if present
        $cleanPath = preg_replace('#^storage/#', '', $profilePicturePath);
        
        // Method 1: Try Storage facade (most reliable in Laravel)
        try {
            if (Storage::disk('public')->exists($cleanPath)) {
                $path = Storage::disk('public')->path($cleanPath);
                if (file_exists($path)) {
                    return $path;
                }
            }
            // Also try with original path
            if (Storage::disk('public')->exists($profilePicturePath)) {
                $path = Storage::disk('public')->path($profilePicturePath);
                if (file_exists($path)) {
                    return $path;
                }
            }
            // Try profile-pictures directory
            $profilePicPath = 'profile-pictures/' . $filename;
            if (Storage::disk('public')->exists($profilePicPath)) {
                $path = Storage::disk('public')->path($profilePicPath);
                if (file_exists($path)) {
                    return $path;
                }
            }
        } catch (\Exception $e) {
            // Storage facade failed, continue to direct paths
        }
        
        // Method 2: Try direct paths using Laravel helpers
        $paths = [];
        try {
            $storageBase = storage_path('app/public');
            $publicBase = public_path('storage');
            
            $paths[] = $storageBase . '/' . $cleanPath;
            $paths[] = $storageBase . '/' . $profilePicturePath;
            $paths[] = $storageBase . '/profile-pictures/' . $filename;
            $paths[] = $publicBase . '/' . $cleanPath;
            $paths[] = $publicBase . '/' . $profilePicturePath;
            $paths[] = $publicBase . '/profile-pictures/' . $filename;
        } catch (\Exception $e) {
            // If Laravel helpers fail, use __DIR__ as fallback
            $storageBase = __DIR__ . '/../../storage/app/public';
            $publicBase = __DIR__ . '/../../../public/storage';
            
            $paths[] = $storageBase . '/' . $cleanPath;
            $paths[] = $storageBase . '/' . $profilePicturePath;
            $paths[] = $storageBase . '/profile-pictures/' . $filename;
            $paths[] = $publicBase . '/' . $cleanPath;
            $paths[] = $publicBase . '/' . $profilePicturePath;
            $paths[] = $publicBase . '/profile-pictures/' . $filename;
        }
        
        // Check each path
        foreach ($paths as $path) {
            $realPath = realpath($path);
            if ($realPath && file_exists($realPath) && is_readable($realPath) && is_file($realPath)) {
                return $realPath;
            }
        }
        
        return null;
    }

    private function serveImageFile($filePath)
    {
        try {
            // Read file content instead of using response()->file()
            // This is more compatible across different server configurations
            if (!file_exists($filePath) || !is_readable($filePath)) {
                return null;
            }

            $fileContent = file_get_contents($filePath);
            if ($fileContent === false) {
                return null;
            }

            $fileName = basename($filePath);
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $contentTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            
            $contentType = $contentTypes[$extension] ?? 'image/jpeg';

            return response($fileContent, 200)
                ->header('Content-Type', $contentType)
                ->header('Content-Length', strlen($fileContent))
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            \Log::error('Error serving image file', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function viewStudentPicture($id)
    {
        // Return default immediately - we'll enhance this later
        // This ensures we never get 500 errors
        try {
            $student = Student::find($id);
            if ($student && $student->profile_picture) {
                $filePath = $this->findImageFile($student->profile_picture);
                if ($filePath) {
                    $response = $this->serveImageFile($filePath);
                    if ($response) {
                        return $response;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently fail and return default
        }
        
        // Always return default avatar - no errors
        return $this->returnDefaultAvatar('S');
    }

    public function viewTutorPicture($id)
    {
        try {
            $tutor = Tutor::find($id);
            if (!$tutor || !$tutor->profile_picture) {
                return $this->returnDefaultAvatar('T');
            }

            // Try to find the file using simple method
            $filePath = $this->findImageFile($tutor->profile_picture);

            // If we found a file, try to serve it
            if ($filePath) {
                $response = $this->serveImageFile($filePath);
                if ($response) {
                    return $response;
                }
            }

        } catch (\Throwable $e) {
            // Silently fail and return default
        }
        
        // Always return default avatar - no errors
        return $this->returnDefaultAvatar('T');
    }
} 