<?php

namespace InsiteApps\Assets;

use SilverStripe\Assets\Image_Backend;
use SilverStripe\Core\Extension;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\DataExtension;

class ImageExtension extends Extension
{
    
    public function Square($width)
    {
        $variant = $this->owner->variantName(__FUNCTION__, $width);
        return $this->owner->manipulateImage($variant, function ( Image_Backend $backend) use($width) {
            $clone = clone $backend;
            $resource = clone $backend->getImageResource();
            $resource->fit($width);
            $clone->setImageResource($resource);
            return $clone;
        });
    }
    
    public function Blur($amount = null)
    {
        $variant = $this->owner->variantName(__FUNCTION__, $amount);
        return $this->owner->manipulateImage($variant, function (Image_Backend $backend) use ($amount) {
            $clone = clone $backend;
            $resource = clone $backend->getImageResource();
            $resource->blur($amount);
            $clone->setImageResource($resource);
            return $clone;
        });
    }
}
