<?php

namespace App\Http\Traits;

use App\Consts\RoleConst;

trait Content {
    /*
        管理者の場合はtrueを戻す
    */
    public function isAdmin($role)
    {
        if(RoleConst::ADMINISTRATOR == $role) {
            return true;
        } else {
            return false;
        }
    }

    /*
        店舗代表者の場合はtrueを戻す
    */
    public function isShopStaff($role)
    {
        if(RoleConst::SHOP_STAFF == $role) {
            return true;
        } else {
            return false;
        }
    }

}