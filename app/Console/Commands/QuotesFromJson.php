<?php

namespace Quoterr\Console\Commands;

use Illuminate\Console\Command;
use Quoterr\Author;
use Quoterr\Helpers\StreamingJsonListener;
use Quoterr\Quote;
use Quoterr\User;
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

    protected $user;

    protected $ignore = [
        'age',
        'amazing',
        'anger',
        'anniversary',
        'architecture',
        'art',
        'attitude',
        'beauty',
        'best',
        'birthday',
        'business',
        'car',
        'change',
        'christmas',
        'communication',
        'computers',
        'cool',
        'courage',
        'dad',
        'dating',
        'death',
        'design',
        'diet',
        'dreams',
        'easter',
        'education',
        'environmental',
        'equality',
        'experience',
        'failure',
        'faith',
        'family',
        'famous',
        'fear',
        'finance',
        'fitness',
        'food',
        'forgiveness',
        'freedom',
        'friendship',
        'funny',
        'future',
        'gardening',
        'god',
        'good',
        'government',
        'graduation',
        'greatness',
        'happiness',
        'health',
        'history',
        'home',
        'hope',
        'humor',
        'imagination',
        'inspirational',
        'intelligence',
        'jealousy',
        'knowledge',
        'leadership',
        'learning',
        'legal',
        'life',
        'loneliness',
        'love',
        'marriage',
        'medical',
        'men',
        'mom',
        'money',
        'morning',
        'motivation',
        'movies',
        'moving on',
        'music',
        'nature',
        'parenting',
        'patience',
        'patriotism',
        'peace',
        'pet',
        'poetry',
        'politics',
        'positive',
        'power',
        'relationship',
        'religion',
        'respect',
        'romantic',
        'saadi',
        'sad',
        'science',
        'seal',
        'smile',
        'society',
        'sports',
        'strength',
        'success',
        'sympathy',
        'teacher',
        'technology',
        'teen',
        'thankful',
        'thanksgiving',
        'time',
        'travel',
        'trust',
        'truth',
        'war',
        'wedding',
        'wisdom',
        'women',
        'work'
    ];
    protected $authors;

    /**
     * QuotesFromJson constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ignore = array_flip($this->ignore);
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
        $this->user = User::whereEmail('bot@quoterr.me')->first();
        try {
            $listener = new StreamingJsonListener(function ($entry) {
                $name = $this->clean($entry['author']);
                if (array_has($this->ignore, strtolower($name)) || str_contains(strtolower($name), 'quote')) {
//                    $quote = $this->clean($entry['text']);
//                    $this->output->write("{$this->count}: {$name} ");
//                    $this->error("{$quote}");
                } else {
                    $author = Author::firstOrCreate(['name' => $name]);
                    if (!array_has($this->authors, strtolower($name))) {
                        $this->info($name);
                        $this->authors[strtolower($name)] = true;
                    }
                    $quote = $this->clean($entry['text']);
                    $q = new Quote([
                        'content'   => $quote,
                        'user_id'   => $this->user->id,
                        'author_id' => $author->id,
                        'published' => true
                    ]);
                    if ($q->save()) {
                        $this->count++;
//                        $this->output->writeln("{$this->count}: {$name} <info>{$quote}</info>");
                    } else {
//                        $this->output->write("{$this->count}: {$name} ");
//                        $this->warn("{$quote}");
                    }
                }
            });
            $parser = new \JsonStreamingParser_Parser($stream, $listener);
            $parser->parse();
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
