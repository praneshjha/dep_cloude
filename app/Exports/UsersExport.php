<?php

namespace App\Exports;
use DB;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::where('main_user_type',1)->where('user_type',0)->select('id as userId','name','last_name','email','mobile','company_name','address','city','state','country','pin')->get();
        foreach ($users as $key => $value) {
            $value->usertype = "Supplier";
            $servicesDest = DB::table('user_destinations')->where('user_id',$value->userId)->pluck('destination_name')->toArray();
            $strDest = implode(", ",$servicesDest);
            $value->destinations =  $strDest;
        }
        return $users;
    }
    public function headings(): array
    {
        return [
            'id',
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
            'usertype',
            'destinations_of_services'
        ];
    }
}
