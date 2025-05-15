<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Gửi email test để kiểm tra cấu hình mail';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Đang gửi email test đến $email...");

        try {
            Mail::raw('Email test từ Laravel app', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('Test Mail từ Laravel App');
            });

            $this->info('Email đã được gửi thành công!');
        } catch (\Exception $e) {
            $this->error('Lỗi khi gửi email: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }

        return Command::SUCCESS;
    }
}
