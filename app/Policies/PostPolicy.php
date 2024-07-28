<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user === $post->user_id;
    }

   
}
