<?php
namespace App\UseCases\Comment;

use Illuminate\Http\Request;
use App\Entity\Comment\Comment;
use App\Entity\Complaint\Complaint;
use Illuminate\Support\Facades\DB;

class ComplaintsService
{
    public function add(Request $request, string $commentId): Comment
    {
        $user = authUser();
        return DB::transaction(function () use ($request, $user, $commentId) {
            $complaint = Complaint::query()->create([
                'reason' => $request['reason'],
            ]);
            $comment = $this->saveData($user, $commentId, 'addToCommentComplaints', [
                'complaint_id' => $complaint->id
            ]);
            return $comment;
        });
    }

    public function remove(Request $request, string $commentId): void
    {
        $user = $request->user();
        $this->saveData($user, $commentId, 'removeFromCommentComplaints');
    }

    private function saveData($user, $commentId, $method, array $attributes = [])
    {
        $comment = findComment($commentId);
        $user->$method($comment->id, $attributes);
        return $comment;
    }
}
