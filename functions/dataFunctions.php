<?php

use Kw\Models\Active;
use Kw\Models\Recipe;
use Kw\Models\Ingredient;
use Kw\Models\Inventory;
use Kw\Models\Shopping;
use Kw\Models\Product;
use Kw\Models\TotalList;

function createProduct($newProductName, $newUnit){
    $currentItems = findData(Product::class);
    $nameOccupied = nameCheck($currentItems, $newProductName, 'productName');
    if($nameOccupied == 0){
        $saveData = new Product;
        $saveData->inputData($newProductName, $newUnit);
        $saveData->save();
    }
}

function refreshTotalList(){
    $query = mysqlConnect();
        $dropTable = $query->prepare("DROP TABLE TotalList");
        $dropTable->execute();

        $sql = "CREATE TABLE IF NOT EXISTS totalList (
            id INT(255) NOT NULL AUTO_INCREMENT,
            productId INT(255) NOT NULL,
            amount INT(255) NOT NULL,
            PRIMARY KEY (id)
        )ENGINE=InnoDB, DEFAULT CHARSET=UTF8";

        $createTable = $query->prepare($sql);
        $createTable->execute();
}

function calculateTotalList(){
    refreshTotalList();
    $actives = findData(Active::class);
    $recipes = findData(Recipe::class);

    foreach($actives as $active){
        foreach($recipes as $recipe){
            if($active['recipeId'] == $recipe['id']){
                $ingredients = findDataByCol(Ingredient::class, 'recipeId', $recipe['id']);
                foreach($ingredients as $ingredient){
                    $saveData = new TotalList;
                    $currentItems = findData(TotalList::class);
                    $amount = $ingredient['amount'] * $active['amount'];
                    $saveData->inputData(
                        $ingredient['productId'],
                        $amount,
                    );
                    $id = $saveData->check($currentItems);
                    $saveData->save($id);
                }
            }
        }
    }
}

function calculateAmount(){
    global $twig;
    $totalList = findData(TotalList::class);
    $inventory = findData(Inventory::class);
    $shopping = findData(Shopping::class);
    $totalId = [];
    $inventId = [];

    foreach($totalList as $value){
        $totalId[] = $value['productId'];
    }

    foreach($inventory as $value){
        $inventId[] = $value['productId'];
    }

    foreach($totalId as $totId){
        if(in_array($totId, $inventId)){

            $totalProduct = findDataByCol(TotalList::class, 'productId', $totId);
            $inventoryProduct = findDataByCol(Inventory::class, 'productId', $totId);

            if($totalProduct[0]['amount'] > $inventoryProduct[0]['amount']){
                $amount = $totalProduct[0]['amount'] - $inventoryProduct[0]['amount'];
                addShopping($totalProduct[0]['productId'], $amount);

                $product = findData(Product::class, $totalProduct[0]['productId']);
                echo $twig->render('sign/addShopping.twig', [
                    'productName' => $product['productName'],
                    'amount' => $amount,
                    'unit' => $product['unit'],
                ]); 
            }
        } else {
            $totalProduct = findDataByCol(TotalList::class, 'productId', $totId);
            $amount = $totalProduct[0]['amount'];

            addShopping($totalProduct[0]['productId'], $amount);
            $product = findData(Product::class, $totalProduct[0]['productId']);
            echo $twig->render('sign/addShopping.twig', [
                'productName' => $product['productName'],
                'amount' => $amount,
                'unit' => $product['unit'],
            ]); 
        }
    }
}