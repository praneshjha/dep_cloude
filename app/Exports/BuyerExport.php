<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BuyerExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::where('main_user_type',0)->where('user_type',0)->select('name','last_name','email','mobile','company_name','address','city','state','country','pin')->get();
        foreach ($users as $key => $value) {
            $value->usertype = "Buyer";
        }
        return $users;
    }
    public function headings(): array
    {
        return [
            'name',
            'last_name',
            'email',
            'mobile',
            'company_name',
            'address',
            'city',
            'state',
            'country',
            'pin',
            'usertype'
        ];
    }
}
