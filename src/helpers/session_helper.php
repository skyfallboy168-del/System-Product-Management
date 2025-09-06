<?php

// Check if user is logged in
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}
