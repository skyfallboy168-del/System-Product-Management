<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . APP_NAME : APP_NAME; ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 960px; margin: 20px auto; padding: 0 20px; }
        header { border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        nav { display: flex; justify-content: space-between; align-items: center; }
        nav a { text-decoration: none; color: #007bff; }
        nav ul { list-style: none; padding: 0; margin: 0; display: flex; }
        nav ul li { margin-left: 20px; }
        .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; }
        footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ccc; text-align: center; font-size: 0.9em; color: #777; }
        .btn {
            display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer;
            background-color: #007bff; border: 1px solid #007bff; padding: .375rem .75rem; font-size: 1rem;
            line-height: 1.5; border-radius: .25rem; text-decoration: none;
        }
        .btn-secondary { background-color: #6c757d; border-color: #6c757d;}
        .btn-primary { background-color: #007bff; border-color: #007bff;}
        .btn-danger { background-color: #dc3545; border-color: #dc3545;}
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
        /* Dropdown styles */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; background-color: #f9f9f9; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1; list-style: none; padding: 0; margin: 0; border-radius: 4px;}
        .dropdown-content li a { color: black; padding: 12px 16px; text-decoration: none; display: block; }
        .dropdown-content li a:hover { background-color: #f1f1f1; }
        .dropdown:hover .dropdown-content { display: block; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="<?php echo URL_ROOT; ?>/public"><strong><?php echo APP_NAME; ?></strong></a>
            <ul>
                <li><a href="<?php echo URL_ROOT; ?>/public">Home</a></li>
                <li><a href="<?php echo URL_ROOT; ?>/public/pages/about">About</a></li>
                <?php if(isLoggedIn()) : ?>
                    <li><a href="<?php echo URL_ROOT; ?>/public/invoices">Invoices</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/public/quotes">Quotes</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/public/customers">Customers</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/public/products">Products</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)">Settings</a>
                        <ul class="dropdown-content">
                            <li><a href="<?php echo URL_ROOT; ?>/public/settings">General Settings</a></li>
                            <li><a href="<?php echo URL_ROOT; ?>/public/productcategories">Product Categories</a></li>
                            <li><a href="<?php echo URL_ROOT; ?>/public/suppliers">Suppliers</a></li>
                        </ul>
                    </li>
                    <li style="margin-left: 40px;">Welcome, <?php echo $_SESSION['user_name']; ?></li>
                    <li><a href="<?php echo URL_ROOT; ?>/public/users/logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="<?php echo URL_ROOT; ?>/public/users/register">Register</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/public/users/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
