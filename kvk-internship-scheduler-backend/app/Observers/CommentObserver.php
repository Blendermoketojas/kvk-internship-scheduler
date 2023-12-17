<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param \App\Models\Comment $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $internship = $comment->internship;
        $internship->logged_hours += $comment->logged_duration;
        $internship->save();
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param \App\Models\Comment $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        $internship = $comment->internship;

        $originalHours = $comment->getOriginal('logged_duration');
        $newHours = $comment->logged_duration;

        $internship->logged_hours += $newHours - $originalHours;
        $internship->save();
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param \App\Models\Comment $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $internship = $comment->internship;
        $internship->logged_hours -= $comment->logged_duration;
        $internship->save();
    }
}
