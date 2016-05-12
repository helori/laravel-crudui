<?php

namespace Helori\LaravelCrudui;

trait HasMedia
{
    // Polymorphic relation
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // --------------------------------------------------------------
    //  Single Media
    // --------------------------------------------------------------
    public function hasMedia($collection)
    {
        return $this->getMedia($collection) != false;
    }

    public function getMedia($collection)
    {
        return $this->medias()->where('collection', $collection)->first();
    }

    public function mediaPath($collection)
    {
        $media = $this->getMedia($collection);
        if($media && is_file($media->filepath)){
            return url($media->filepath.'?'.filemtime($media->filepath));
        }
    }

    // --------------------------------------------------------------
    //  Multiple Medias
    // --------------------------------------------------------------
    public function getMedias($collection)
    {
        return $this->medias()->where('collection', $collection)->orderBy('position', 'asc')->get();
    }

    public function hasMedias($collection)
    {
        return count($this->getMedias($collection)) > 0;
    }

    public function mediasPath($collection)
    {
        $medias = $this->getMedias($collection);
        $paths = [];
        foreach($medias as $media){
            if(is_file($media->filepath)){
                $paths[] = url($media->filepath.'?'.filemtime($media->filepath));
            }
        }
        return $paths;
    }
}
