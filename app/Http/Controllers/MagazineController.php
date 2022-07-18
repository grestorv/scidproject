<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorMagazine;
use App\Models\Magazine;
use Doctrine\Inflector\Rules\English\Inflectible;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MagazineController extends Controller
{
    public function list(Request $request)
    {
        if (!$request->has('page', 'perPage')) {
            $collection = Magazine::all();
        } else {
            $limit = $request->perPage;
            $offset = $request->perPage * ($request->page - 1);
            $collection = Magazine::take($limit)->skip($offset)->get();
        }

        return response($collection)->header('Content-Type', 'application/json');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if (!$request->has(['name', 'image', 'image_format', 'authors'])) {
            return response(['status' => false, 'error' => 'Нет одного из необходимых параметров'])->header(
                'Content-Type',
                'application/json'
            );
        }

        if ($request->has('authors') && empty($request->authors)) {
            return response(['status' => false, 'error' => 'Поле authors не может быть пустым'])->header(
                'Content-Type',
                'application/json'
            );
        }
        $imagePath = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . Str::uuid(
            ) . '.' . $request->image_format;

        $magazine = new Magazine();
        $magazine->name = $request->name;
        $magazine->description = $request->description;
        $magazine->publish_date = $request->publish_date;
        $magazine->image = $imagePath;
        $magazine->save();
        $image = base64_decode($request->image);
        file_put_contents($imagePath, $image);
        AuthorMagazine::addAuthors($request->authors, $magazine->id);

        return response($magazine)->header('Content-Type', 'application/json');
    }

    public function update(Request $request)
    {
        if ($request->has('authors') && empty($request->authors)) {
            return response(['status' => false, 'error' => 'Поле authors не может быть пустым'])->header(
                'Content-Type',
                'application/json'
            );
        }
        $magazine = Magazine::find($request->id);
        if (is_null($magazine)) {
            return response(['status' => false, 'error' => 'Объект с таким  id не найден'])->header(
                'Content-Type',
                'application/json'
            );
        }
        $updateArray = $request->except(['image', 'image_format', 'authors', 'id']);

        if ($request->has(['image', 'image_format'])) {
            $imagePath = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . Str::uuid(
                ) . '.' . $request->image_format;
            $image = base64_decode($request->image);
            file_put_contents($imagePath, $image);
            if (file_exists($magazine->image)) {
                unlink($magazine->image);
            }
            $updateArray['image'] = $imagePath;
        }
        $magazine->update($updateArray);

        if ($request->has('authors')) {
            AuthorMagazine::updateAuthors($request->authors, $request->id);
        }

        return response($magazine)->header('Content-Type', 'application/json');
    }

    public function delete(Request $request)
    {
        $magazine = Magazine::find($request->id);
        if (is_null($magazine)) {
            return response(['status' => false, 'error' => 'Объект с таким  id не найден'])->header(
                'Content-Type',
                'application/json'
            );
        }
        $magazine->delete();
        AuthorMagazine::where('magazine_id', $request->id)->delete();
        return response($magazine)->header('Content-Type', 'application/json');
    }
}
