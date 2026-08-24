<?php

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use Base;
use Template;

class CategoryController
{
    public function show(Base $f3, array $params): void
    {
        $category = new Category($f3->get('DB'));
        $category->load(['slug = ?', $params['slug']]);

        if ($category->dry()) {
            $f3->error(404);
            return;
        }

        $articles = new Article($f3->get('DB'));
        $related = $articles->find(['category_id = ?', $category->id], ['order' => 'article_date DESC NULLS LAST']) ?: [];

        $f3->set('category', $category->cast());
        $f3->set('articles', array_map(fn (Article $a) => $a->cast(), $related));
        $f3->set('content', 'categories/show.htm');
        echo Template::instance()->render('layout.htm');
    }
}
