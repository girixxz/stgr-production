# TODO: Create Data Seeders

## Overview

Create seeders for all tables except users, with max 10 records per table.

## Tables to Seed

-   sales
-   product_categories
-   material_categories
-   material_textures
-   material_sleeves
-   material_sizes
-   shippings

## Steps

1. Create SalesSeeder.php
2. Create ProductCategorySeeder.php
3. Create MaterialCategorySeeder.php
4. Create MaterialTextureSeeder.php
5. Create MaterialSleeveSeeder.php
6. Create MaterialSizeSeeder.php
7. Create ShippingSeeder.php
8. Update DatabaseSeeder.php to call all new seeders
9. Test the seeders by running php artisan db:seed

## Table Structures

-   sales: id, sales_name (unique), phone_number (nullable), timestamps
-   product_categories: id, product_name (unique), timestamps
-   material_categories: id, material_name (unique), timestamps
-   material_textures: id, texture_name (unique), timestamps
-   material_sleeves: id, sleeve_name (unique), timestamps
-   material_sizes: id, size_name (unique), timestamps
-   shippings: id, shipping_name (unique), timestamps
