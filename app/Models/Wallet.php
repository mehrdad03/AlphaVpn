<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function deposit(array $formData)
    {

        Wallet::query()->create([
            'user_id' => Auth::user()->id,
            'wallet_address' => $formData['wallet_address'],
            'hash' => $formData['hash'],
            'type' => 'deposit',
            'amount' => $formData['amount'],
        ]);
    }

    public function withdrawal(array $formData)
    {
        // dd('dwad');
        Wallet::query()->create([
            'user_id' => Auth::user()->id,
            'wallet_address' => $formData['wallet_address'],
            'type' => 'withdraw',
            'amount' => $formData['amount'],
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function insertCommissions($user_id, $order)
    {

        $upLines = (new \App\Models\User())->getUpLines($user_id);

        $profits = ['20', '10', '5'];

        $transactions = [];
        $userWithProfits = [];
        //dd(($order));


     //   dd($upLines);
        for ($i = 0; $i < count($profits); $i++) {
            if (isset($upLines[$i])){

                $userWithProfits[] = ($i + 1) . '-' . $upLines[$i] . '-' . $profits[$i] . '-' . serialize($order);
            }

        }

        foreach ($userWithProfits as $transaction) {

            $transaction = explode('-', $transaction);
            $level = $transaction[0];
            $upLine = $transaction[1];
            $commission = $transaction[2];
            $commission_amount = ($commission * $order->price) / 100;
            $product = $transaction[3];
            $level_percent = $transaction[2];
            $data = [
                'product' => $product,
                'commission' => $level_percent,
                'level' => $level,
                'amount' => $order->price,
            ];

            Wallet::query()->create([
                'user_id' => $upLine,
                'amount' => $commission_amount,
                'type' => 'commission',
                'description' => serialize($data),
                'status' => 'confirmed',
            ]);

            //$this->insertUserCommissionToWallet($upLine, $commission, $transaction_id);

        }
    }

}
