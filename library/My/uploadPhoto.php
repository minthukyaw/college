<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of uploadPhoto
 *
 * @author Minthukyaw
 */
class My_uploadPhoto {
    
    const FLICKR_API_KEY = '15b189b2cb5a63a2cb53518ae7762e44';
    const FLICKR_API_SECRET = '44eac69d6843e012';
    const FLICKR_API_TOKEN = '72157633315974756-51ef2c0bd5f335b0';
    
    public static $flickrApi = null;
    
    private function _getFlickApi() {
        if (self::$flickrApi == null)
            self::$flickrApi = new Phlickr_Api(self::FLICKR_API_KEY, self::FLICKR_API_SECRET, self::FLICKR_API_TOKEN);

        return self::$flickrApi;
    }

    public function uploadToFlickr($filename, $tag = '') {

        // create an api
        $api = $this->_getFlickApi();
        // create an uploader
        $uploader = new Phlickr_Uploader($api);

        // create a DirectoryIterator (part of the Standard PHP Library)
        $id = $uploader->upload($filename, '', '', $tag);

        if ($id != '') {

            $photo = new Phlickr_Photo($api, $id);
            $url = $photo->buildImgUrl(Phlickr_Photo::SIZE_ORIGINAL);
            return array($id, $url);
        } else {
            throw new Exception('Unable to upload');
        }
    }
}




