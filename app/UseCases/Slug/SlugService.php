<?php
namespace App\UseCases\Slug;

class SlugService {

    public function generate($phrase, $limit = 400)
    {
        $slug = str()->slug($phrase);
        if( strlen($slug) > $limit ) return str()->substr($slug, 0, $limit);
        else return $slug;
    }
}
