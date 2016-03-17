<?php

namespace Helori\LaravelCrudui;

trait HasMedia
{
    // Polymorphic relation
	public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getMedia($collection)
    {
        return $this->medias()->where('collection', $collection)->first();
    }

    public function getMedias($collection)
    {
        return $this->medias()->where('collection', $collection)->orderBy('position', 'asc')->get();
    }

    public function hasMedia($collection)
    {
        return $this->getMedia($collection) != false;
    }

    public function hasMedias($collection)
    {
        return count($this->getMedias($collection)) > 0;
    }
}
