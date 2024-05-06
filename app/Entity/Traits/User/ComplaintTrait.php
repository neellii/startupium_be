<?php
namespace App\Entity\Traits\User;

use App\Entity\Comment\Comment;
use App\Entity\Project\Project;

trait ComplaintTrait
{
    // PROJECT COMPLAINTS
    public function projectComplaints()
    {
        return $this->belongsToMany(Project::class, 'project_complaints_ref', 'user_id', 'project_id')
            ->withPivot('complaint_id');
    }

    public function hasInProjectComplaints($projectId): bool
    {
        return $this->projectComplaints()->where('id', $projectId)->exists();
    }

    public function addToProjectComplaints($projectId, array $attributes): void
    {
        if ($this->hasInProjectComplaints($projectId)) {
            throw new \DomainException('This project is already added to complaints.');
        }
        $this->projectComplaints()->attach($projectId, $attributes);
    }

    public function removeFromProjectComplaints($projectId, array $attributes): void
    {
        if (!$this->hasInProjectComplaints($projectId)) {
            throw new \DomainException('This project is already removed from complaints.');
        }
        $this->projectComplaints()->detach($projectId, $attributes);
    }


    // COMMENTS COMPLAINTS
    public function commentComplaints()
    {
        return $this->belongsToMany(Comment::class, 'comment_complaints_ref', 'user_id', 'comment_id')
            ->withPivot('complaint_id');
    }

    public function hasInCommentComplaints($commentId): bool
    {
        return $this->commentComplaints()->where('id', $commentId)->exists();
    }

    public function addToCommentComplaints($commentId, array $attributes): void
    {
        if ($this->hasInCommentComplaints($commentId)) {
            throw new \DomainException('This comment is already added to complaints.');
        }
        $this->commentComplaints()->attach($commentId, $attributes);
    }

    public function removeFromCommentComplaints($commentId, array $attributes): void
    {
        if (!$this->hasInCommentComplaints($commentId)) {
            throw new \DomainException('This comment is already removed from complaints.');
        }
        $this->commentComplaints()->detach($commentId, $attributes);
    }
}
