<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuthorMagazine extends Pivot
{
    public function addAuthors(array $author_ids, int $magazine_id)
    {
        foreach ($author_ids as $author_id) {
            AuthorMagazine::firstOrCreate(
                [
                    'author_id' => $author_id,
                    'magazine_id' => $magazine_id
                ],
                [
                    'author_id' => $author_id,
                    'magazine_id' => $magazine_id
                ]
            );
        }
    }

    public function updateAuthors(array $author_ids, int $magazine_id)
    {

        AuthorMagazine::addAuthors($author_ids, $magazine_id);

        AuthorMagazine::where('magazine_id', $magazine_id)->whereNotIn('author_id', $author_ids)->delete();
    }
}
