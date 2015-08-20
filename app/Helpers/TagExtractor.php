<?php namespace Quoterr\Helpers;

use File;

/**
 * This file belongs to quoterr.me.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class TagExtractor
{
    private $filename = 'resources/files/stop_words.txt';

    protected $ignoredWords = [];

    /**
     * TagExtractor constructor.
     *
     * @param null $filename
     */
    public function __construct($filename = null)
    {
        if (File::exists($filename)) {
            $this->filename = $filename;
        }
    }

    public function getTags($text)
    {
        $text = preg_replace('/[0-9\W]/', ',', $text);
        $words = explode(',', $text);
        $words = array_map(function ($word) {
            return strtolower(trim($word));
        }, $words);
        $tags = [];
        foreach ($words as $word) {
            if (!in_array($word, $this->getIgnoredWords(), true)) {
                $tags[] = ucwords($word);
            }
        }

        return array_unique($tags);
    }

    public function getIgnoredWords()
    {
        if (!empty($this->ignoredWords)) {
            return $this->ignoredWords;
        }

        if (File::exists(base_path("{$this->filename}.dat"))) {
            $this->ignoredWords = unserialize(File::get(base_path("{$this->filename}.dat")));

            return $this->ignoredWords;
        }

        $stream = File::get(base_path($this->filename));
        $stream = preg_replace('/[0-9\W]/', ',', $stream);
        $words = explode(',', $stream);
        $this->ignoredWords = array_map(function ($word) {
            return strtolower(trim($word));
        }, $words);
        sort($this->ignoredWords);
        $serialized = serialize($this->ignoredWords);

        File::put(base_path("{$this->filename}.dat"), $serialized);

        return $this->ignoredWords;
    }
}