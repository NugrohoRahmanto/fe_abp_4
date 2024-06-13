<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use App\Utils\GetUserInfo;

class ShopController extends Controller
{
    public function getAllShop(){
        try{
            // $token = $_COOKIE['token'];

                $headers = [
                    'Accept' => 'application\json',
                    // 'Authorization' => 'Bearer '.$token
                ];

                $response = Http::withHeaders($headers)->get($_ENV['BACKEND_API_ENDPOINT'].'/shop/all');
                // dd($response);
                $data = $response->json();
                // dd($data);
                if ($data['status'] == 'success') {
                    return view('shop.shops',['shops'=>$data['data']]);
                } else {
                    return view('errors.404');
                }
            }catch(Exception $error){
                return "Error: ".$error->getMessage();
            }

    }

    public function getShopMenuByUserId(Request $request){
        try{
            $headers = [
                'Accept' => 'application\json',
            ];
            // dd($headers);

            $shop_id = $request->shop_id;
            $shop_name = $request->shop_name;
            $api_request = [
                'shop_id' => $shop_id,
            ];
            $response = Http::withHeaders($headers)->get($_ENV['BACKEND_API_ENDPOINT'].'/menu/byShop', $api_request);
            $data = $response->json();
            // dd($data['data']);
            if (isset($data)) {
                if ($data['status'] == 'success') {
                    return view('shop.myShopMenu', ['menus'=>$data['data'], 'shop_id' => $shop_id, 'shop_name' => $shop_name]);
                }else{
                    return view('shop.myShopMenu', ['shop_id' => $shop_id, 'shop_name' => $shop_name]);
                }
                // return view('shop.myShop',['shop'=>$data['data']], ['menus'=>$data2['data']]);
            } else {
                return view('errors.404');
            }

        }catch(Exception $error){
            return "Error: ".$error->getMessage();
        }
    }


    public function getShopByUserId(Request $request){
        try{
            
            $token = $_COOKIE['token'];
            $headers = [
                'Accept' => 'application\json',
                'Authorization' => 'Bearer '.$token
            ];

            $user = $user = GetUserInfo::getUserInfo();

            $user_id = $user['data']['id'];

            $api_request = [
                'user_id' => $user_id,
            ];

            $response = Http::withHeaders($headers)->get($_ENV['BACKEND_API_ENDPOINT'].'/shop/byUser', $api_request);
            $data = $response->json();
            // dd($data['data']);

            if ($data['status'] == 'success') {
                    return view('shop.myShop',['shops'=>$data['data']], ['user_id'=>$user_id]);
            } else {
                return view('errors.404');
            }

        }catch(Exception $error){
            return "Error: ".$error->getMessage();
        }
    }

    public function addShop(Request $request){
        try{
            
            $token = $_COOKIE['token'];
            $headers = [
                'Accept' => 'application\json',
                'Authorization' => 'Bearer '.$token
            ];
            // dd($headers);

            $namaToko = $request->namaToko;
            $nomorToko = $request->nomorToko;
            $lokasiToko = $request->lokasiToko;
            $user_id = $request->user_id;

            $api_request = [
                'namaToko' => $namaToko,
                'nomorToko' => $nomorToko,
                'lokasiToko' => $lokasiToko,
                'user_id' => $user_id,
            ];

            $response = Http::withHeaders($headers)->post($_ENV['BACKEND_API_ENDPOINT'].'/shop/add', $api_request);
            $data = $response->json();
            // dd($data);
            if ($data['status'] == 'success') {
                toastr()->success('Shop added succesfully', 'Shop');
                // return redirect('/index');
                return redirect('/shop/byUser');

            } else {
                toastr()->error('Failed to add shop', 'Shop');
                return redirect('/index');
            }

        }catch(Exception $error){
            return "Error: ".$error->getMessage();
        }
    }

    public function editShop(Request $request){
        try{
            
            $token = $_COOKIE['token'];
            $headers = [
                'Accept' => 'application\json',
                'Authorization' => 'Bearer '.$token
            ];
            // dd($headers);

            $id = $request->id;
            $id = intval($id);
            $namaToko = $request->namaToko;
            $nomorToko = $request->nomorToko;
            $lokasiToko = $request->lokasiToko;
            $user_id = $request->user_id;
            if(isset($request->image)){
                $destination_path = 'public/images/shop';    
                $image = $request->file('image');
                $image_name = $id.'_'.time().'_'.$image->getClientOriginalName();
                // $image->move(public_path('images'), $image_name);
                $path = $request->file('image')->storeAs($destination_path, $image_name);
                $path2 = 'storage/images/shop/'.$image_name;
                // dd($id);
                $api_request2 = [
                    'id' => $id,
                    'image' => $path2,
                ];
                $response2 = Http::withHeaders($headers)->put($_ENV['BACKEND_API_ENDPOINT'].'/shop/add/image', $api_request2);
                $data2 = $response2->json();
                // dd($data2, $response2, $api_request2);
            }

            $api_request = [
                'id' => $id,
                'namaToko' => $namaToko,
                'nomorToko' => $nomorToko,
                'lokasiToko' => $lokasiToko,
                'user_id' => $user_id,
            ];

            $response = Http::withHeaders($headers)->put($_ENV['BACKEND_API_ENDPOINT'].'/shop/edit', $api_request);
            $data = $response->json();
            // dd($data);
            if ($data['status'] == 'success') {
                toastr()->success('Shop edit succesfully', 'Shop');
                // return redirect('/index');
                return redirect('/shop/byUser');


            } else {
                toastr()->error('Failed to edit shop', 'Shop');
                return redirect('/index');
            }

        }catch(Exception $error){
            return "Error: ".$error->getMessage();
        }
    }
    
    public function deleteShop(Request $request){
        try{
            
            $token = $_COOKIE['token'];
            $headers = [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ];
            // dd($headers);

            
            $shopId = $request->shopId;
            

            $api_request = [
                'id' => $shopId
            ];

            $response = Http::withHeaders($headers)->delete($_ENV['BACKEND_API_ENDPOINT'].'/shop/delete', $api_request);
            $data = $response->json();

            
            // dd($data);
            if ($data['status'] == 'success') {
                toastr()->success('Shop deleted succesfully', 'Shop');
                return redirect('/index');
            } else {
                toastr()->error('Shop deleted unsuccesful', 'Shop');
                return redirect('/index');
            }
            

        }catch(Exception $error){
            return "Error: ".$error->getMessage();
        }
    }


    public function getAllPaidedMenuByShop(Request $request){
        try{
            $token = $_COOKIE['token'];

                $headers = [
                    'Accept' => 'application\json',
                    'Authorization' => 'Bearer '.$token
                ];

                $shop_id = $request->shop_id;

                $api_request = [
                    'shop_id' => $shop_id
                ];
                // dd($api_request);
                $response = Http::withHeaders($headers)->get($_ENV['BACKEND_API_ENDPOINT'].'/menu/all/paid/byShop', $api_request);
                $data = $response->json();
                // dd($data);

                if ($data['status'] == 'success') {
                    return view('shop.pesanan',['menus'=>$data['data']]);
                } else {
                    return view('errors.404');
                }
            }catch(Exception $error){
                return "Error: ".$error->getMessage();
            }

    }

    public function donePaidedMenuByShop(Request $request){
        try{
            $token = $_COOKIE['token'];

                $headers = [
                    'Accept' => 'application\json',
                    'Authorization' => 'Bearer '.$token
                ];

                $shop_id = $request->shop_id;
                $menu_id = $request->menu_id;
                $booking_id = $request->booking_id;

                $api_request = [
                    'shop_id' => $shop_id,
                    'menu_id' => $menu_id,
                    'booking_id' => $booking_id,

                ];
                // dd($api_request);
                $response = Http::withHeaders($headers)->post($_ENV['BACKEND_API_ENDPOINT'].'/menu/done/paid/byShop', $api_request);
                $data = $response->json();
                // dd($data);

                if ($data['status'] == 'success') {
                    toastr()->success('Pesanan selesai', 'Shop');
                    return redirect('/shop/byUser');
                } else {
                    return view('errors.404');
                }
            }catch(Exception $error){
                return "Error: ".$error->getMessage();
            }

    }
    
}