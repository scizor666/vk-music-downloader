<?php
/**
 * Created by PhpStorm.
 * User: atepliashin
 * Date: 12/13/15
 * Time: 16:57
 */

namespace MusicDownloader;

class AudioGrabber extends ServiceAccessor {
    private $access_token;
    private $user_id;

    public function __construct($access_token, $user_id=null) {
        parent::__construct();
        $this->access_token = $access_token;
        $this->user_id = $user_id;
    }

    /**
     * @return Record[]
     */
    public function all_records() {
        $result = $this->curl->get(self::API_URL_BASE . '/method/audio.get', ['access_token' => $this->access_token] +
            ($this->user_id ? [] : ['owner_id' => $this->user_id]));
        $records_array = $result->response ? $result->response : [];
        $records = [];
        foreach($records_array as $record) {
            array_push($records, new Record($record->artist, $record->title, $record->url));
        }
        return $records;
    }

    /**
     * @param Record[] $records
     * @param String $path
     */
    public function save_records($records, $path = '') {
        foreach($records as $record) {
            $record->save($path);
        }
    }
}