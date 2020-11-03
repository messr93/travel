<?php

namespace App\Policies;

use App\Models\Offer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;


    public function createOffer(User $user){

        return ($user->role !== 'customer');
    }

    public function editOffer(User $user, Offer $offer){
        return $user->id === $offer->user_id;
    }

    public function updateOffer(User $user, Offer $offer){
        return $user->id === $offer->user_id;
    }

    public function changeStatus(User $user, Offer $offer){
        return $user->id === $offer->user_id;
    }

}
