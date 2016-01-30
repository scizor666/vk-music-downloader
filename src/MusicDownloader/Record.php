<?php
/**
 * Created by PhpStorm.
 * User: atepliashin
 * Date: 1/31/16
 * Time: 12:53 AM
 */

namespace MusicDownloader;

class Record {
    private $artist;
    private $title;
    private $url;

    /**
     * Record constructor.
     * @param $artist
     * @param $title
     * @param $url
     */
    public function __construct($artist, $title, $url) {
        $this->artist = $artist;
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getArtist() {
        return $this->artist;
    }

    /**
     * @param mixed $artist
     */
    public function setArtist($artist) {
        $this->artist = $artist;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    public function save($path = '') {
        return file_put_contents($path . $this->artist . ' - ' . $this->title . '.mp3', file_get_contents($this->url));
    }
}