<?php

namespace Helori\LaravelCrudui;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Image;
use Helori\LaravelMedias\Media;


class CrudUtilities
{
    public static function fillItem($request, $item, $fields, $uploads_dir)
    {
        foreach($fields as $field)
        {
            if($field['type'] == 'text' || $field['type'] == 'url' || $field['type'] == 'textarea' || $field['type'] == 'editor' || $field['type'] == 'email')
                $item->$field['name'] = $request->input($field['name']);
            else if($field['type'] == 'select')
                $item->$field['name'] = $request->input($field['name']);
            else if($field['type'] == 'checkbox')
                $item->$field['name'] = $request->has($field['name']) && $request->input($field['name']) == 'true';
            else if($field['type'] == 'date' || $field['type'] == 'datetime')
                $item->$field['name'] = $request->input($field['name']);
            else if($field['type'] == 'file')
                self::setFile($request, $item, $field['name'], $uploads_dir, isset($field['name_src_field']) ? $field['name_src_field'] : 'id');
            else if($field['type'] == 'image')
            {
                self::setFile($request, $item, $field['name'], $uploads_dir, isset($field['name_src_field']) ? $field['name_src_field'] : 'id');
            }
            else if($field['type'] == 'image-advanced')
            {
                // done with ajax for upload progress
                // check uploadImage()
            }
            else if($field['type'] == 'alias')
                $item->alias = Str::slug(($field['use_id'] ? $item->id.'-' : '').$item->$field['src'], '-');
            else if($field['type'] == 'json')
                $item->$field['name'] = json_decode($request->input($field['name']), true);
            else if($field['type'] == 'multicheck'){
                $item->$field['name'] = json_decode($request->input($field['name']), true);
            }
        }
        $item->save();
    }

    public static function uploadMedia(&$request, &$item, $multiple)
    {
        $title = $request->input('title');
        $collection = $request->input('collection');
        $width = $request->input('width');
        $height = $request->input('height');
        $modified = $request->input('modified');
        $mime = $request->input('mime');
        $format = $request->input('format');

        if($request->hasFile($collection) && $request->file($collection)->isValid())
        {
            $file_path = 'uploads/medias';
            // -----------------------------------------------------------
            //  Save item to be able to read its id and name_src_field
            // -----------------------------------------------------------
            $item->save();

            // -----------------------------------------------------------
            //  Get uploaded file infos
            // -----------------------------------------------------------
            $file = $request->file($collection);
            $file_ext = $file->guessExtension();

            // -----------------------------------------------------------
            //  Create or update the image
            // -----------------------------------------------------------
            if(!$multiple)
                $media = $item->getMedia($collection);

            if(!isset($media) || !$media){
                $media = new Media();
                $media->collection = $collection;
                $media->save();
            }
            else{
                $old_file = public_path().'/'.$media->filepath;
                if(is_file($old_file))
                    unlink($old_file);
            }

            $file_name = Str::slug($media->id.'_'.$title, '_');

            // -----------------------------------------------------------
            //  Move the image
            // -----------------------------------------------------------
            if(!is_dir($file_path))
                mkdir($file_path, 0777, true);
            $file->move(public_path().'/'.$file_path, $file_name.'.'.$file_ext);

            // -----------------------------------------------------------
            //  Resize and Re-format if required
            // -----------------------------------------------------------
            $abs_path = public_path().'/'.$file_path.'/'.$file_name.'.'.$file_ext;
            $img = Image::make($abs_path);

            /*if(false)
            {
                $width = isset($options['width']) ? intVal($options['width']) : null;
                $height = isset($options['height']) ? intVal($options['height']) : null;
                if($width !== null){
                    $img = $img->resize($width, null, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                if($height !== null){
                    $img = $img->resize(null, $height, function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                if(isset($options['format'])){
                    $quality = isset($options['quality']) ? intVal($options['quality']) : 90;
                    $img = $img->encode($options['format'], $quality);
                    $file_ext = trim(strtolower($options['format']));
                    $old_abs_path = $abs_path;
                    $abs_path = public_path().'/'.$file_path.'/'.$file_name.'.'.$file_ext;
                }
                $img->save($abs_path);
                if(isset($old_abs_path) && $old_abs_path != $abs_path && is_file($old_abs_path)){
                    unlink($old_abs_path);
                }    
            }*/

            // -----------------------------------------------------------
            //  Save the media
            // -----------------------------------------------------------
            $media->type = 'image';
            $media->mime = $img->mime();
            $media->size = $file->getClientSize();
            $media->extension = $file_ext;
            $media->filename = $file_name.'.'.$file_ext;
            $media->filepath = $file_path.'/'.$file_name.'.'.$file_ext;
            $media->width = $img->width();
            $media->height = $img->height();
            $media->size = filesize($abs_path);

            // -----------------------------------------------------------
            //  Associate the media to the item
            // -----------------------------------------------------------
            $media->mediable()->associate($item);

            if($multiple){
                $medias = $item->getMedias($collection);
                foreach($medias as &$m){
                    ++$m->position;
                    $m->save();
                }
                $media->position = 0;
                $media->save();
                
                return $medias = $item->getMedias($collection);
            }
            else{
                $media->save();
                return $item->getMedia($collection);
            }
        }
    }

    protected static function setFile(&$request, &$item, $field_name, $file_path, $name_src_field)
    {
        if($request->hasFile($field_name) && $request->file($field_name)->isValid())
        {
            if(!is_dir($file_path))
                mkdir($file_path, 0777, true);

            if(!$item->id)
                $item->save();

            if(isset($item->{$field_name}['path'])) {
                $old_file = public_path().'/'.$item->{$field_name}['path'];
                if(is_file($old_file)){
                    unlink($old_file);
                }    
            }

            $file = $request->file($field_name);
            $file_ext = $file->guessExtension();
            //$file_ext = 'jpg';
            $file_name = $item->id.'_'.($name_src_field != 'id' ? Str::slug($item->$name_src_field, '_').'_' : '').$field_name.'.'.$file_ext;

            $result = [];
            $result['mime'] = $file->getMimeType();
            $result['size'] = $file->getClientSize();
            $result['ext'] = $file_ext;
            $result['path'] = $file_path.'/'.$file_name;

            $file->move(public_path().'/'.$file_path, $file_name);

            if(strpos($result['mime'], 'image') !== false)
            {
                $abs_path = public_path().'/'.$file_path.'/'.$file_name;
                $img = Image::make($abs_path)->resize(900, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->resize(null, 900, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })/*->encode('jpg', 100)*/->save($abs_path);

                $result['mime'] = $img->mime();
                $result['size'] = filesize($abs_path);
                $result['width'] = $img->width();
                $result['height'] = $img->height();
            }

            $item->$field_name = $result;
        }
    }
}
