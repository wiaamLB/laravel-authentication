<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $data = Page::select('id','title','alias','created_at','updated_at')->get();
        return response(['success' => true, 'data' =>$data]);
    }

    public function show($id)
    {

        $data = Page::where(['id' => $id])->firstOrFail();

        return response(['success' => true, 'data' => $data]);
    }

    public function store()
    {

        $data = request()->validate([
            'title' => ['required'],
            'alias' => ['required'],
            'active' => ['required'],
            'page_title' => ['required'],
            'description' => ['required'],
            'content' => ['required'],
            'meta_name' => [''],
            'meta_content' => [''],
            'keywords' => [''],
            'image' => [''],
            'image_thumb' => [''],
        ]);

        if (request('image')) {
            $location_image = copy_file_storage_s3_admin(request('image'), request('alias') . '/images');
            $data['image'] = $location_image;
        }
        if (request('image_thumb')) {
            $location_image_thumb = copy_file_storage_s3_admin(request('image_thumb'), request('alias') . '/images_thumb');
            $data['image'] = $location_image_thumb;
        }

        Page::updateOrCreate(['alias' => $data['alias']], $data);

        return response(['success' => true, 'data' => 'Page Created']);

    }

    public function delete($id){
        $data = Page::where(['id' => $id])->firstOrFail();
        $data->delete();
        return response(['success' => true, 'data' => 'Page Deleted']);

    }
}
