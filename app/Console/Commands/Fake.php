<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Faker\Factory as Faker;

class Fake extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fake project data';

    protected $user;

    /**
     * Create a new command instance.
     *
     * @param User $user
     */
    public function __construct(User $user) {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->fakeData();
        $this->info("Fake data view.");
        $this->printData();
    }

    private function printData() {
        $headers = ['ID', '姓名', '信箱', '建立日期', '更新日期'];
        $users = $this->user->where('id','<', 30)->get();

        return $this->table($headers, $users);
    }

    /**
     * 利用 Faker 製作假資料
     */
    private function fakeData() {
        $faker = Faker::create();

        foreach (range(1, 100) as $num) {
            $this->user->create([
                'name'     => $faker->name,
                'email'    => $faker->email,
                'password' => $faker->bothify('##?##?##?'),
            ]);
        }
    }
}
