<?php

namespace App\Controllers;

use App\Models\Article;
use Base;
use League\CommonMark\CommonMarkConverter;
use Template;

class ArticleController
{
    public function index(Base $f3): void
    {
        $articles = new Article($f3->get('DB'));
        $page = max(1, (int) $f3->get('GET.page'));

        $result = $articles->paginate($page - 1, 20, null, ['order' => 'article_date DESC NULLS LAST']);
        $result['subset'] = array_map(fn (Article $a) => $a->cast(), $result['subset']);

        $f3->set('pagination', $result);
        $f3->set('content', 'articles/list.htm');
        echo Template::instance()->render('layout.htm');
    }

    public function show(Base $f3, array $params): void
    {
        $article = new Article($f3->get('DB'));
        $article->load(['slug = ?', $params['slug']]);

        if ($article->dry()) {
            $f3->error(404);
            return;
        }

        $data = $article->cast();
        $data['body'] = (new CommonMarkConverter())->convert($data['body'])->getContent();

        $f3->set('article', $data);
        $f3->set('content', 'articles/show.htm');
        echo Template::instance()->render('layout.htm');
    }
}
