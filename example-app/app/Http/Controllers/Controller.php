<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function welcome()
    {
        
        $users = User::all();
        return view('welcome', compact('users'));
    }
    public function userDetail($id)
    {
        $user = User::where('id', $id)->first();
        $addresses = Address::where('user_id', $user->id)->first();
        $profileImage = Document::where('documentable_id', $user->id)->first();
        $addressImage = Document::where('documentable_id', $user->id)->first();
        $addressTypes = AddressType::all();
        return view('userDetail', [
            'user' => $user,
            'addressTypes' => $addressTypes,
            'addresses' => $addresses,
            'profileImage' => $profileImage,
            'addressImage' => $addressImage,
        ]);
    }
    public function userUpdate(Request $request)
    {
        // dd($request->all());
        $addresses = Address::where('user_id', $request->user_id)->first();
        $user = User::where('id', $request->user_id)->first();

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            // 'email' => $request->email,
        ]);
        $addresses->update([
            'address_type_id' => $request->address_type,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]);

        $users = User::all();
        return view('welcome', compact('users'));
    }
    
    public function exportMembers(Request $request, $format)
    {
        $members = User::all();

        $fileName = 'members_' . time() . '.' . $format;

        if ($format === 'csv') {
            return $this->exportToCSV($members, $fileName);
        } elseif ($format === 'excel') {
        } elseif ($format === 'pdf') {
        }
    }

    private function exportToCSV($members, $fileName)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($members) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['id', 'first_name', 'last_name','email', 'created_at']);

            foreach ($members as $member) {
                fputcsv($file, [$member->id, $member->first_name,$member->last_name, $member->email, $member->created_at]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
