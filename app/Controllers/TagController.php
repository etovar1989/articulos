<?php

namespace App\Controllers;

use App\Models\Tag;
use Base;
use Template;

class TagController
{
    public function show(Base $f3, array $params): void
    {
        $tag = new Tag($f3->get('DB'));
        $tag->load(['slug = ?', $params['slug']]);

        if ($tag->dry()) {
            $f3->error(404);
            return;
        }

        $articles = $f3->get('DB')->exec(
            'SELECT a.* FROM articles a
             JOIN article_tags at2 ON at2.article_id = a.id
             WHERE at2.tag_id = ?
             ORDER BY a.article_date DESC NULLS LAST',
            $tag->id
        );

        $f3->set('tag', $tag->cast());
        $f3->set('articles', $articles);
        $f3->set('content', 'tags/show.htm');
        echo Template::instance()->render('layout.htm');
    }
}
