<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Tutor;
use App\Models\Student;

class TutorProfileController extends Controller
{
    public function edit()
    {
        $tutor = Auth::guard('tutor')->user();
        return view('tutor.profile.edit', compact('tutor'));
    }

    public function update(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email,' . $tutor->id,
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($tutor->profile_picture) {
                Storage::disk('public')->delete($tutor->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $tutor->profile_picture = $path;
        }

        // Update details
        $tutor->first_name = $validated['first_name'];
        $tutor->last_name = $validated['last_name'];
        $tutor->email = $validated['email'];
        // tutor_id is automatically generated and cannot be changed
        $tutor->specialization = $validated['specialization'] ?? null;
        $tutor->phone = $validated['phone'] ?? null;
        $tutor->bio = $validated['bio'] ?? null;

        // Handle password change
        if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
            if (Hash::check($validated['current_password'], $tutor->password)) {
                $tutor->password = Hash::make($validated['new_password']);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $tutor->save();
        // Refresh session
        Auth::guard('tutor')->setUser($tutor);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function profilePicture()
    {
        // Ultra-safe: catch everything and never throw 500
        try {
            $tutor = null;
            try {
                $tutor = Auth::guard('tutor')->user();
            } catch (\Exception $e) {
                return $this->returnDefaultAvatar('T');
            }
            
            if (!$tutor || !$tutor->profile_picture) {
                return $this->returnDefaultAvatar('T');
            }

            $profilePicturePath = $tutor->profile_picture;
            if (empty($profilePicturePath)) {
                return $this->returnDefaultAvatar('T');
            }
            
            // Normalize the path - remove any storage/ prefix
            $cleanPath = preg_replace('#^storage/#', '', trim($profilePicturePath));
            $filename = basename($cleanPath);
            
            // Build comprehensive list of paths to try
            $pathsToTry = [
                $cleanPath,  // Original path from database (e.g., "profile-pictures/filename.jpg")
                $profilePicturePath,  // Original path with storage/ prefix if present
                'profile-pictures/' . $filename,  // Just filename in profile-pictures directory
            ];
            
            // Try Storage facade with each path variation
            foreach ($pathsToTry as $pathToTry) {
                try {
                    if (Storage::disk('public')->exists($pathToTry)) {
                        $fileContent = Storage::disk('public')->get($pathToTry);
                        if ($fileContent && strlen($fileContent) > 0) {
                            $extension = strtolower(pathinfo($pathToTry, PATHINFO_EXTENSION));
                            $contentType = $this->getContentType($extension);
                            
                            return response($fileContent, 200)
                                ->header('Content-Type', $contentType)
                                ->header('Content-Length', strlen($fileContent))
                                ->header('Cache-Control', 'public, max-age=3600');
                        }
                    }
                } catch (\Exception $e) {
                    // This path failed, try next one
                    continue;
                }
            }
            
            // Fallback to file path method - try to find file directly
            try {
                $filePath = $this->findImageFile($profilePicturePath);
                if ($filePath) {
                    $response = $this->serveImageFile($filePath);
                    if ($response) {
                        return $response;
                    }
                }
            } catch (\Exception $e) {
                // File path method failed, continue
            }
        } catch (\Throwable $e) {
            // Catch absolutely everything
        }
        
        // Always return something valid - never throw 500
        return $this->returnDefaultAvatar('T');
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
        if (empty($profilePicturePath)) {
            return null;
        }

        // Normalize the path
        $profilePicturePath = str_replace('\\', '/', trim($profilePicturePath));
        $filename = basename($profilePicturePath);
        
        // Remove storage/ prefix if present
        $cleanPath = preg_replace('#^storage/#', '', $profilePicturePath);
        
        // Build comprehensive list of paths to try
        $pathsToTry = [];
        
        // Method 1: Try Storage facade (most reliable in Laravel)
        try {
            // Try clean path
            if (Storage::disk('public')->exists($cleanPath)) {
                $path = Storage::disk('public')->path($cleanPath);
                if ($path && file_exists($path) && is_file($path)) {
                    return $path;
                }
            }
            // Try original path
            if (Storage::disk('public')->exists($profilePicturePath)) {
                $path = Storage::disk('public')->path($profilePicturePath);
                if ($path && file_exists($path) && is_file($path)) {
                    return $path;
                }
            }
            // Try profile-pictures directory with just filename
            $profilePicPath = 'profile-pictures/' . $filename;
            if (Storage::disk('public')->exists($profilePicPath)) {
                $path = Storage::disk('public')->path($profilePicPath);
                if ($path && file_exists($path) && is_file($path)) {
                    return $path;
                }
            }
        } catch (\Exception $e) {
            // Storage facade failed, continue to direct paths
        }
        
        // Method 2: Try direct paths using Laravel helpers
        try {
            $storageBase = storage_path('app/public');
            $publicBase = public_path('storage');
            
            $pathsToTry[] = $storageBase . '/' . $cleanPath;
            $pathsToTry[] = $storageBase . '/' . $profilePicturePath;
            $pathsToTry[] = $storageBase . '/profile-pictures/' . $filename;
            $pathsToTry[] = $publicBase . '/' . $cleanPath;
            $pathsToTry[] = $publicBase . '/' . $profilePicturePath;
            $pathsToTry[] = $publicBase . '/profile-pictures/' . $filename;
        } catch (\Exception $e) {
            // If Laravel helpers fail, use __DIR__ as fallback
        }
        
        // Method 3: Use __DIR__ as absolute fallback
        $storageBase = __DIR__ . '/../../storage/app/public';
        $publicBase = __DIR__ . '/../../../public/storage';
        
        $pathsToTry[] = $storageBase . '/' . $cleanPath;
        $pathsToTry[] = $storageBase . '/' . $profilePicturePath;
        $pathsToTry[] = $storageBase . '/profile-pictures/' . $filename;
        $pathsToTry[] = $publicBase . '/' . $cleanPath;
        $pathsToTry[] = $publicBase . '/' . $profilePicturePath;
        $pathsToTry[] = $publicBase . '/profile-pictures/' . $filename;
        
        // Check each path
        foreach ($pathsToTry as $path) {
            // Try with realpath first
            $realPath = realpath($path);
            if ($realPath && file_exists($realPath) && is_readable($realPath) && is_file($realPath)) {
                return $realPath;
            }
            // Also try the path directly (in case realpath fails but file exists)
            if (file_exists($path) && is_readable($path) && is_file($path)) {
                return $path;
            }
        }
        
        return null;
    }

    private function getContentType($extension)
    {
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];
        return $contentTypes[$extension] ?? 'image/jpeg';
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
            $contentType = $this->getContentType($extension);

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

    public function viewTutorPicture($id)
    {
        // Ultra-safe: catch everything and never throw 500
        try {
            $tutor = null;
            try {
                $tutor = Tutor::find($id);
            } catch (\Exception $e) {
                return $this->returnDefaultAvatar('T');
            }
            
            if (!$tutor || !$tutor->profile_picture) {
                return $this->returnDefaultAvatar('T');
            }

            $profilePicturePath = $tutor->profile_picture;
            if (empty($profilePicturePath)) {
                return $this->returnDefaultAvatar('T');
            }
            
            // Normalize the path - remove any storage/ prefix
            $cleanPath = preg_replace('#^storage/#', '', trim($profilePicturePath));
            $filename = basename($cleanPath);
            
            // Build comprehensive list of paths to try
            $pathsToTry = [
                $cleanPath,  // Original path from database (e.g., "profile-pictures/filename.jpg")
                $profilePicturePath,  // Original path with storage/ prefix if present
                'profile-pictures/' . $filename,  // Just filename in profile-pictures directory
            ];
            
            // Try Storage facade with each path variation
            foreach ($pathsToTry as $pathToTry) {
                try {
                    if (Storage::disk('public')->exists($pathToTry)) {
                        $fileContent = Storage::disk('public')->get($pathToTry);
                        if ($fileContent && strlen($fileContent) > 0) {
                            $extension = strtolower(pathinfo($pathToTry, PATHINFO_EXTENSION));
                            $contentType = $this->getContentType($extension);
                            
                            return response($fileContent, 200)
                                ->header('Content-Type', $contentType)
                                ->header('Content-Length', strlen($fileContent))
                                ->header('Cache-Control', 'public, max-age=3600');
                        }
                    }
                } catch (\Exception $e) {
                    // This path failed, try next one
                    continue;
                }
            }
            
            // Fallback to file path method - try to find file directly
            try {
                $filePath = $this->findImageFile($profilePicturePath);
                if ($filePath) {
                    $response = $this->serveImageFile($filePath);
                    if ($response) {
                        return $response;
                    }
                }
            } catch (\Exception $e) {
                // File path method failed, continue
            }
        } catch (\Throwable $e) {
            // Catch absolutely everything
        }
        
        // Always return something valid - never throw 500
        return $this->returnDefaultAvatar('T');
    }

    public function viewStudentPicture($id)
    {
        // Ultra-safe: catch everything and never throw 500
        try {
            $student = null;
            try {
                $student = Student::find($id);
            } catch (\Exception $e) {
                return $this->returnDefaultAvatar('S');
            }
            
            if (!$student || !$student->profile_picture) {
                return $this->returnDefaultAvatar('S');
            }

            $profilePicturePath = $student->profile_picture;
            if (empty($profilePicturePath)) {
                return $this->returnDefaultAvatar('S');
            }
            
            $cleanPath = preg_replace('#^storage/#', '', $profilePicturePath);
            
            // Try Storage facade first - wrap in try-catch
            try {
                if (Storage::disk('public')->exists($cleanPath)) {
                    $fileContent = Storage::disk('public')->get($cleanPath);
                    if ($fileContent && strlen($fileContent) > 0) {
                        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                        $contentType = $this->getContentType($extension);
                        
                        return response($fileContent, 200)
                            ->header('Content-Type', $contentType)
                            ->header('Content-Length', strlen($fileContent))
                            ->header('Cache-Control', 'public, max-age=3600');
                    }
                }
            } catch (\Exception $e) {
                // Storage failed, continue to fallback
            }
            
            // Fallback to file path method
            try {
                $filePath = $this->findImageFile($profilePicturePath);
                if ($filePath) {
                    $response = $this->serveImageFile($filePath);
                    if ($response) {
                        return $response;
                    }
                }
            } catch (\Exception $e) {
                // File path method failed, continue
            }
        } catch (\Throwable $e) {
            // Catch absolutely everything
        }
        
        // Always return something valid - never throw 500
        return $this->returnDefaultAvatar('S');
    }
} 