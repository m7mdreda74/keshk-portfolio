<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Project;
use App\Models\ResumeItem;
use App\Models\Testimonial;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ──────────────────────────────────────────
    //  AUTH
    // ──────────────────────────────────────────

    private function generateToken(): string
    {
        return hash('sha256', config('app.admin_password') . config('app.key'));
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate(['password' => 'required|string']);

        if ($request->password !== config('app.admin_password')) {
            return response()->json(['message' => 'Incorrect password'], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'token'   => $this->generateToken(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function checkAuth(Request $request): JsonResponse
    {
        $token = $request->header('X-Admin-Token');
        $valid = $token && hash_equals($this->generateToken(), $token);
        return response()->json(['authenticated' => $valid]);
    }

    // ──────────────────────────────────────────
    //  PERSONAL INFO
    // ──────────────────────────────────────────

    public function getPersonalInfo(): JsonResponse
    {
        return response()->json(PersonalInfo::first());
    }

    public function updatePersonalInfo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'profile'       => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:50',
            'bio'           => 'required|string',
            'profile_image' => 'nullable|string',
            'hero_image'    => 'nullable|string',
            'resume_link'   => 'nullable|string',
            'linkedin'      => 'nullable|string',
            'github'        => 'nullable|string',
            'whatsapp'      => 'nullable|string',
            'telegram'      => 'nullable|string',
            'facebook'      => 'nullable|string',
            'instagram'     => 'nullable|string',
            'copyright'     => 'nullable|string',
        ]);

        $info = PersonalInfo::first();
        if ($info) {
            $info->update($data);
        } else {
            $info = PersonalInfo::create($data);
        }
        return response()->json($info);
    }

    // ──────────────────────────────────────────
    //  SKILLS
    // ──────────────────────────────────────────

    public function getSkills(): JsonResponse
    {
        return response()->json(Skill::orderBy('id')->get());
    }

    public function createSkill(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
        ]);
        return response()->json(Skill::create($data), 201);
    }

    public function updateSkill(Request $request, Skill $skill): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
        ]);
        $skill->update($data);
        return response()->json($skill);
    }

    public function deleteSkill(Skill $skill): JsonResponse
    {
        $skill->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  SERVICES
    // ──────────────────────────────────────────

    public function getServices(): JsonResponse
    {
        return response()->json(Service::orderBy('id')->get());
    }

    public function createService(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'required|string|max:100',
        ]);
        return response()->json(Service::create($data), 201);
    }

    public function updateService(Request $request, Service $service): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'required|string|max:100',
        ]);
        $service->update($data);
        return response()->json($service);
    }

    public function deleteService(Service $service): JsonResponse
    {
        $service->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  STATS
    // ──────────────────────────────────────────

    public function getStats(): JsonResponse
    {
        return response()->json(Stat::orderBy('id')->get());
    }

    public function createStat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'count' => 'required|integer|min:0',
        ]);
        return response()->json(Stat::create($data), 201);
    }

    public function updateStat(Request $request, Stat $stat): JsonResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'count' => 'required|integer|min:0',
        ]);
        $stat->update($data);
        return response()->json($stat);
    }

    public function deleteStat(Stat $stat): JsonResponse
    {
        $stat->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  PROJECTS
    // ──────────────────────────────────────────

    public function getProjects(): JsonResponse
    {
        return response()->json(Project::orderBy('id')->get());
    }

    public function createProject(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'required|string',
            'image'        => 'nullable|string',
            'details_link' => 'nullable|url',
        ]);
        return response()->json(Project::create($data), 201);
    }

    public function updateProject(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'required|string',
            'image'        => 'nullable|string',
            'details_link' => 'nullable|url',
        ]);
        $project->update($data);
        return response()->json($project);
    }

    public function deleteProject(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  RESUME ITEMS
    // ──────────────────────────────────────────

    public function getResumeItems(): JsonResponse
    {
        return response()->json(ResumeItem::orderBy('id')->get());
    }

    public function createResumeItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:education,experience',
            'title'        => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'duration'     => 'required|string|max:100',
            'description'  => 'required|string',
        ]);
        return response()->json(ResumeItem::create($data), 201);
    }

    public function updateResumeItem(Request $request, ResumeItem $resumeItem): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:education,experience',
            'title'        => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'duration'     => 'required|string|max:100',
            'description'  => 'required|string',
        ]);
        $resumeItem->update($data);
        return response()->json($resumeItem);
    }

    public function deleteResumeItem(ResumeItem $resumeItem): JsonResponse
    {
        $resumeItem->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  TESTIMONIALS
    // ──────────────────────────────────────────

    public function getTestimonials(): JsonResponse
    {
        return response()->json(Testimonial::orderBy('id')->get());
    }

    public function createTestimonial(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'quote' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'image' => 'nullable|string',
        ]);
        return response()->json(Testimonial::create($data), 201);
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'quote' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'image' => 'nullable|string',
        ]);
        $testimonial->update($data);
        return response()->json($testimonial);
    }

    public function deleteTestimonial(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // ──────────────────────────────────────────
    //  CONTACT MESSAGES
    // ──────────────────────────────────────────

    public function getMessages(): JsonResponse
    {
        return response()->json(ContactMessage::orderByDesc('created_at')->get());
    }

    public function deleteMessage(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
