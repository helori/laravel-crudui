<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Helori\LaravelCrudui\CrudUtilities;
use Helori\LaravelCrudui\Media;
use Image;

class MediasBaseController extends Controller
{
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
    }

    public function getMedia(Request $request, $id, $collection, $model = null)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        return $item->getMedia($collection);
    }

    public function getMedias(Request $request, $id, $collection, $model = null)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        return $item->getMedias($collection);
    }

    public function postUploadMedia(Request $request)
    {
        return $this->uploadMedia($request, $request->input('id'), false);
    }

    public function postUploadMedias(Request $request)
    {
        return $this->uploadMedia($request, $request->input('id'), true);
    }

    protected function uploadMedia(&$request, $itemId, $multiple)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);

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

            $mime = $file->getMimeType();
            $size = $file->getClientSize();

            // -----------------------------------------------------------
            //  Move the image
            // -----------------------------------------------------------
            if(!is_dir($file_path))
                mkdir($file_path, 0777, true);
            $file->move(public_path().'/'.$file_path, $file_name.'.'.$file_ext);

            $abs_path = public_path().'/'.$file_path.'/'.$file_name.'.'.$file_ext;

            // -----------------------------------------------------------
            //  Resize and Re-format if required
            // -----------------------------------------------------------
            $is_image = (strpos($mime, 'image') !== false);
            if($is_image)
            {
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
                $media->type = 'image';
                $media->width = $img->width();
                $media->height = $img->height();
            }

            // -----------------------------------------------------------
            //  Save the media
            // -----------------------------------------------------------
            $media->mime = $mime;
            $media->size = $size;
            $media->title = $title;
            $media->extension = $file_ext;
            $media->filename = $file_name.'.'.$file_ext;
            $media->filepath = $file_path.'/'.$file_name.'.'.$file_ext;
            //$media->size = filesize($abs_path);

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

    public function postDeleteMedia(Request $request)
    {
        $itemId = $request->input('id');
        $mediaId = $request->input('mediaId');

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = $item->medias()->where('id', $mediaId)->first();
        $collection = $media->collection;

        if($media){
            $old_file = public_path().'/'.$media->filepath;
            if(is_file($old_file))
                unlink($old_file);
            $media->delete();
        }
        return $item->getMedias($collection);
    }

    public function postRenameMedia(Request $request)
    {
        $itemId = $request->input('id');
        $mediaId = $request->input('mediaId');
        $title = $request->input('title');

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = $item->medias()->where('id', $mediaId)->first();
        $collection = $media->collection;

        if($media){
            $file_dir = dirname($media->filepath);
            $file_name = Str::slug($media->id.'_'.$title, '_').'.'.$media->extension;
            $new_path = $file_dir.'/'.$file_name;

            if(file_exists(public_path().'/'.$media->filepath)){
                rename(public_path().'/'.$media->filepath, public_path().'/'.$new_path);
            }
            
            $media->title = $title;
            $media->filename = $file_name;
            $media->filepath = $new_path;
            $media->save();
        }
        return $media;
    }

    public function postUpdateMediasPosition(Request $request)
    {
        $itemId = intVal($request->input('id'));
        $mediaId = intVal($request->input('mediaId'));

        $item = call_user_func(array($this->class_name, 'findOrFail'), $itemId);
        $media = Media::findOrFail($mediaId);
        $collection = $media->collection;
        $medias = $item->getMedias($collection);

        $oldPos = $media->position;
        $newPos = intVal($request->input('position'));

        if($oldPos != $newPos)
        {
            if($oldPos < $newPos)
                $range = [$oldPos + 1, $newPos];
            else
                $range = [$newPos, $oldPos - 1];

            // whereBetween does not exist for collections as Laravel 5.2!
            //$medias = $medias->whereBetween('position', $range);

            $rangeList = [];
            for($i=$range[0]; $i<=$range[1]; ++$i)
                $rangeList[] = $i;
            $medias = $medias->whereIn('position', $rangeList);
            
            // increment and devrement does not exist for collections as Laravel 5.2!
            if($oldPos < $newPos){
                foreach($medias as &$m){
                    --$m->position;
                    $m->save();
                }
            }else{
                foreach($medias as &$m){
                    ++$m->position;
                    $m->save();
                }
            }
            $media->position = $newPos;
            $media->save();
        }
    }
}
