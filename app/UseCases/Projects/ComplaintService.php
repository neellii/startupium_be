<?php
namespace App\UseCases\Projects;

use App\Entity\Complaint\Complaint;
use Illuminate\Http\Request;
use App\Entity\Project\Project;
use App\Events\Project\ProjectEvent;
use Illuminate\Support\Facades\DB;

class ComplaintService
{
    // add to complaints
    public function add(Request $request, string $projectId): Project
    {
        $user = authUser();
        return DB::transaction(function () use ($request, $user, $projectId) {
            $complaint = Complaint::query()->create([
                'reason' => $request['reason'],
            ]);
            $project = saveData($user, $projectId, 'addToProjectComplaints', [
                'complaint_id' => $complaint->id
            ]);
            return $project;
        });
    }

    // remove from complaints
    public function remove(string $projectId): void
    {
        $user = authUser();
        $project = saveData($user, $projectId, 'removeFromProjectComplaints');

        /** @var Project $project */
        event(new ProjectEvent($project, $user, Project::REMOVE_FROM_REPORTS));
    }
}
