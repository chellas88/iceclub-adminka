<?php

namespace App\Http\Controllers;

use Adminka\AmoCRM\AmoCRM;
use Adminka\AmoCRM\AmoOAuth;
use App\Models\AmoApp;
use Illuminate\Http\Request;

class AmoController extends Controller
{
    public function index()
    {
        $this->loadAmoUsers();
    }

    private function loadAmoUsers()
    {
        $amo_apps = new AmoApp();
        $api_data = $amo_apps->getApiData(1);
        $amo = new AmoCRM($api_data);
        $amo_users = $amo->getUsers();
        dd($amo_users);
    }

    public function amoApp()
    {
        $amo_apps = new AmoApp();
        $auth_data = $amo_apps->getAuthData(1);
        $amo_auth = new AmoOAuth($auth_data);
        $url = $amo_auth->getRedirectUrl("7753fffd7492218f88653ad654df3bc6");
        return view('amo', ['app_url' => $url]);
    }

    public function amoAuth(Request $request)
    {
        if (isset($request["code"])) {
            $amo_apps = new AmoApp();
            $auth_data = $amo_apps->getAuthData(1);
            $amo_auth = new AmoOAuth($auth_data);
            $amo_auth->setExternalSaveToken(function ($token_data) {
                $this->saveToken($token_data);
            });
            $amo_auth->getAccessToken($request["code"]);
            $api_data = $amo_apps->getApiData(1);
            $amo = new AmoCRM($api_data);
            $info = $amo->accountInfo("users_groups");
            $update_param = [
                "account_name" => $info["name"],
                "account_id" => $info["id"],
            ];
            $amo_apps->where("id", $api_data["auth_id"])->update($update_param);
            return redirect()->route('home');
        } else {
            abort(503);
        }
    }

    public function saveToken($token_data)
    {
        $amo_app = new AmoApp();
        $update_param = [
            "access_token" => $token_data["access_token"],
            "refresh_token" => $token_data["refresh_token"]
        ];
        if (isset($token_data["expires_in"])) {
            $update_param["expires"] = date("Y-m-d H:i:s", time() + $token_data["expires_in"]);
        } elseif (isset($token_data["expired_in"])) {
            $update_param["expires"] = date("Y-m-d H:i:s", $token_data["expired_in"]);
        }
        $amo_app->where("id", $token_data["auth_id"])->update($update_param);
    }
}
