<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Cart $cart)
    {
        return $this->check($user,$cart);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Cart $cart)
    {
        $isOk = $this->check($user,$cart);

        if($isOk){
            if(!$user->is_admin){
                if($cart->order_id != null){
                    return $cart->order->status == 'processing';
                }
            }
        }

        return $isOk;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Cart $cart)
    {
        return $this->check($user,$cart);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Cart $cart)
    {
        return $this->check($user,$cart);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Cart $cart)
    {
        return $this->check($user,$cart);
    }


    public function check($user,$cart)
    {

        if(!$user->is_admin && $cart->user_id != $user->id){
            return false;
        }
        return true;

    }
}
