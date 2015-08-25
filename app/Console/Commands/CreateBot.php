<?php

namespace Quoterr\Console\Commands;

use Illuminate\Console\Command;
use Quoterr\User;

class CreateBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quoterr:create-bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default user.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::whereEmail('bot@quoterr.me')->first();

        if (!$user) {
            $user = new User([
                'name'         => 'Quoterr Bot',
                'email'        => 'bot@quoterr.me',
                'is_admin'     => true,
                'is_moderator' => true,
            ]);

            if (!$user->save()) {
                $this->error('Failed to create user bot.');
                array_map(function ($x) {
                    (new Dumper)->dump($x);
                }, $user->getErrors()->toArray());
            } else {
                $this->info('User bot created.');
            }
        } else {
            $this->warn('User bot already exists.');
        }
    }
}
