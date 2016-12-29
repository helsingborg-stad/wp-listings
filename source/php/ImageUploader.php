<?php

namespace WpListings;

class ImageUploader
{
    /**
     * Uploads a base64 encoded image string as image file
     * @param  string $base64 Base64 encoded image string
     * @return array          Path and url in a array
     */
    public function uploadBase64($base64) : array
    {
        // Decode the base64 image
        $base64 = str_replace(' ', '+', $base64);
        $base64 = explode(',', $base64);
        $decodedImage = base64_decode($base64[1]);

        // Get and create upload dir if needed
        $uploadDir = \WpListings\App::$uploadDir . '/images';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        $fileType = preg_match_all('/data:image\/(.*);/', $base64[0], $matches);

        if (!isset($matches[1][0])) {
            throw new \Error('Could not get file extension from base64 string.');
        }

        $fileType = $matches[1][0];

        switch ($fileType) {
            case 'jpeg':
                $fileType = 'jpg';
                break;
        }

        $imagePath = $uploadDir . '/' . uniqid() . '.' . $fileType;
        file_put_contents($imagePath, $decodedImage);

        // Resize on upload
        $resized = $this->resize($imagePath, 800, 800, false);

        return array(
            'path' => $resized,
            'url'  => $this->pathToUrl($resized)
        );
    }

    /**
     * Resize/crop profile image to given size
     * @param  string  $path      Path to original image
     * @param  integer  $width    Width in pixels
     * @param  integer  $height   Height in pixels
     * @param  boolean $crop      Crop or just resize? true to crop
     * @return string             The cropped image's path
     */
    public function resize($path, $width, $height, $crop = true)
    {
        $image = wp_get_image_editor($path);

        if (is_wp_error($image)) {
            return;
        }

        $image->set_quality(80);
        $image->resize($width, $height, $crop);
        $image->save($path);

        return $path;
    }

    /**
     * Rewrite profile image path to url
     * @param  string $path   The path
     * @return string         The url
     */
    private function pathToUrl($path)
    {
        $path = explode('wp-content/', $path)[1];
        $url = content_url($path);

        return $url;
    }

    /**
     * Rewrite profile image to path
     * @param  string $url The path
     * @return string      The url
     */
    private function urlToPath($url)
    {
        $url = explode('wp-content/', $url)[1];
        $path = WP_CONTENT_DIR . '/' . $url;

        return $path;
    }
}
