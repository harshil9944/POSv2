<?php


class Zebra
{
    public $zebra;
    public function __construct()
    {
        $this->zebra = new Zebra_Image();
        $this->zebra->auto_handle_exif_orientation = true;
        $this->zebra->jpeg_quality = 80;
        $this->zebra->preserve_aspect_ratio = true;
        $this->zebra->enlarge_smaller_images = false;
        $this->zebra->preserve_time = true;
        $this->zebra->handle_exif_orientation_tag = true;
    }

    public function resize_crop_center($params) {
        $this->zebra->source_path = $params['source'];
        $this->zebra->target_path = $params['destination'];

        return $this->zebra->resize($params['width'],$params['height'],ZEBRA_IMAGE_CROP_CENTER);
    }
}
