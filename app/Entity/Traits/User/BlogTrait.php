<?php
namespace App\Entity\Traits\User;

use DomainException;
use App\Entity\Blog\Blog;

trait BlogTrait {

    public function favoritesBlogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_favorites_ref', 'user_id', 'blog_id');
    }

    public function hasInBlogFavorites($id): bool
    {
        return $this->favoritesBlogs()->where('id', 'like', $id)->exists();
    }

    public function addToBlogFavorites($blogId): void
    {
        if ($this->hasInBlogFavorites($blogId)) {
            throw new DomainException(config('constants.blog_already_in_favorites'));
        }
        // если отсутствует, то прикрепи блог
        $this->favoritesBlogs()->attach($blogId);
    }

    public function removeFromBlogFavorites($blogIds): void
    {
        if (!$this->hasInBlogFavorites($blogIds)) {
            throw new DomainException(config('constants.blog_already_out_favorites'));
        }
        // если присутствует, то открепи блог
        $this->favoritesBlogs()->detach($blogIds);
    }
}
