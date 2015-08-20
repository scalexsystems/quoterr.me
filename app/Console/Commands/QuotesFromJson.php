<?php

namespace Quoterr\Console\Commands;

use Illuminate\Console\Command;
use Quoterr\Author;
use Quoterr\Helpers\StreamingJsonListener;
use Quoterr\Helpers\TagExtractor;
use Quoterr\Quote;
use Quoterr\Tag;
use Symfony\Component\Console\Input\InputArgument;

class QuotesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quoterr:json {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load quotes from JSON file.';

    protected $count = 0;
    /**
     * @type
     */
    protected $tagExtractor;

    protected $classes = [];

    /**
     * QuotesFromJson constructor.
     *
     * @param \Quoterr\Helpers\TagExtractor $tagExtractor
     */
    public function __construct(TagExtractor $tagExtractor)
    {
        parent::__construct();
        $this->tagExtractor = $tagExtractor;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $filename = $this->argument('filename');
        $stream = fopen($filename, 'r');
        try {
            $listener = new StreamingJsonListener(function ($entry){
                $name = $this->clean($entry['author']);
                if (!str_contains('Quote', $name) && count(explode(' ', $name)) >= 2) {
                    $author = Author::firstOrCreate(['name' => $name]);
                    $quote = $this->clean($entry['text']);
                    $q = new Quote([
                        'content'   => $quote,
                        'user_id'   => 1,
                        'author_id' => $author->id,
                        'published' => true
                    ]);
                    if ($q->save()) {
                        $tags = $this->tagExtractor->getTags($q->content);
                        foreach ($tags as $tag) {
                            $t = Tag::whereSlug(str_slug($tag))->first();
                            if (!$t) {
                                $t = Tag::create(['name' => $tag]);
                            }
                            $t->quotes()->attach($q->id);
                        }
                        $this->count++;
                        $this->output->writeln("{$this->count}: <info>{$quote}</info>");
                    } else {
                        $this->output->writeln("{$this->count}: <error>{$quote}</error>");
                    }
                } else {
                    $this->classes[$name] = true;
                }
            });
            $parser = new \JsonStreamingParser_Parser($stream, $listener);
            $parser->parse();
            \File::put(base_path('classes.txt'), implode('\n', array_keys($this->classes)));
            $this->info("{$this->count} quotes pushed.");
        } catch (\JsonStreamingParser_ParsingError $e) {
            fclose($stream);
        }
    }

    protected function clean($input)
    {
        return preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
            return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
        }, $input);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['filename', InputArgument::REQUIRED, 'Name of the JSON file.'],
        ];
    }
}
