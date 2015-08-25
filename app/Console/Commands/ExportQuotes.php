<?php

namespace Quoterr\Console\Commands;

use Illuminate\Console\Command;
use Quoterr\Quote;
use Symfony\Component\Console\Input\InputArgument;

class ExportQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quoterr:export {filename?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export quotes to json.';

    protected $count = 0;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = $this->argument('filename') ?: 'quotes.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, '[');
        $this->info('Exporting quotes...');
        Quote::chunk(100, function ($quotes) use ($fp) {
            foreach ($quotes as $quote) {
                if ($this->count > 0) {
                    fwrite($fp, ',');
                }
                fwrite($fp, json_encode(['text' => $quote->content, 'author' => $quote->author->name]));
                $this->count++;
            }
            $this->info("Exported {$this->count} quotes.");
        });
        fwrite($fp, ']');
        fclose($fp);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['filename', InputArgument::OPTIONAL, 'Name of the JSON file.'],
        ];
    }
}
