<?php

namespace App\UseCases\Blog;

use App\Entity\Blog\Blog;
use App\Entity\User\User;

class BlogFavoritesService {

    public function addToFavorites(Blog $blog, User $user): Blog {
        $user->addToBlogFavorites($blog->id);
        return $blog;
    }

    public function deleteFromFavorites(Blog $blog, User $user): Blog {
        $user->removeFromBlogFavorites($blog->id);
        return $blog;
    }
}
