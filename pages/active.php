<form action='index.php?page=active' method='post'>

<?php

use Kw\Models\Model;
use Kw\Models\Active;
use Kw\Models\Inventory;
use Kw\Models\Product;

$currentPage = 'active';

if(isset($_POST['create'])){
    $saveData = new Active;
    $saveData->inputData($_POST['newRecipeId'], $_POST['newAmount']);
    $currentList = findData(Active::class);
    $id = $saveData->checkRecipe($currentList);
    $saveData->save($id);
    calculateTotalList();
}

if(isset($_POST['modify'])){
    $id = $_POST['modify'];
    $saveData = new Active;
    $saveData->inputData($_POST['recipeId'], $_POST['amount']);
    $saveData->save($id);
    calculateTotalList();
}

if(isset($_POST['delete'])){
    $id = $_POST['delete'];
    $deleteData = new Active;
    $deleteData->delete($id);
    calculateTotalList();
}

if(isset($_POST['done'])){
    $id = $_POST['done'];
    doneRecipe($id);
    $deleteData = new Active;
    $deleteData->delete($id);
    calculateTotalList();
}

if(isset($_POST['calculateAmount'])){
    calculateAmount();
}

include 'lists/activeList.php';
include 'lists/totalList.php';

if(isset($_POST['showInventory'])){
    include 'lists/inventoryList.php';
}

?>
</form>