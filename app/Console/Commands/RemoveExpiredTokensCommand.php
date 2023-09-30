<?php

namespace App\Console\Commands;

use App\Models\UserToken;
use Illuminate\Console\Command;

class RemoveExpiredTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:remove-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserToken::where('expires_at', '<', now())->delete();
        echo 'Expired tokens successfully deleted!';

    }
}
