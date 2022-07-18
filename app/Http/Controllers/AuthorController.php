<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorMagazine;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function list(Request $request)
    {
        if (!$request->has('page', 'perPage')) {
            $collection = Author::all();
        } else {
            $limit = $request->perPage;
            $offset = $request->perPage * ($request->page - 1);
            $collection = Author::take($limit)->skip($offset)->get();
        }

        return response($collection)->header('Content-Type', 'application/json');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if (!$request->has(['name', 'surname'])) {
            return response(['status' => false, 'error' => 'Нет одного из необходимых параметров'])->header(
                'Content-Type',
                'application/json'
            );
        }

        if ($request->has('surname') && mb_strlen($request->surname) < 3) {
            return response(['status' => false, 'error' => 'Фамилия не может быть короче 3 символов'])->header(
                'Content-Type',
                'application/json'
            );
        }

        $author = new Author();
        $author->name = $request->name;
        $author->surname = $request->surname;
        $author->patronymic = $request->patronymic;
        $author->save();

        return response($author)->header('Content-Type', 'application/json');
    }

    public function update(Request $request)
    {
        if ($request->has('surname') && mb_strlen($request->surname) < 3) {

            return response(['status' => false, 'error' => 'Фамилия не может быть короче 3 символов'])->header(
                'Content-Type',
                'application/json'
            );
        }

        $author = Author::find($request->id);

        if (is_null($author)) {
            return response(['status' => false, 'error' => 'Объект с таким  id не найден'])->header(
                'Content-Type',
                'application/json'
            );
        }

        $author->update($request->only(['name', 'surname', 'patronymic']));

        return response($author)->header('Content-Type', 'application/json');
    }

    public function delete(Request $request)
    {
        $author = Author::find($request->id);

        if (is_null($author)) {
            return response(['status' => false, 'error' => 'Объект с таким  id не найден'])->header(
                'Content-Type',
                'application/json'
            );
        }

        $author->delete();
        AuthorMagazine::where('author_id', $request->id)->delete();
        return response($author)->header('Content-Type', 'application/json');
    }
}
