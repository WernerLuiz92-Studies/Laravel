<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Numero de tentativas, usado quando é utilizado um limitador, pois por padrão
    // a fila irá tentar executar 3 vezes e cancelar o job. Com o valor 0, o job será executado
    // independente da quantidade de tentativas.
    public $tries = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public User $user,
    ) {
        //
    }

    public function middleware()
    {
        return [
            new RateLimited('emails-limit'),
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(
            new \App\Notifications\MaintenanceNotice()
        );
    }
}