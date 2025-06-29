<?php

require_once "includes/formHelpers.php";
?>
<div class="container">
    <h3>Add new item</h3>
    <p>Use the form below to add a new item to the system.</p>
    <p>This page is only accessible to logged-in users with admin privileges.</p>

            <?php include "_errorSummary.html.php"; ?>
                <form action="addItem.php" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend><strong>Add Item</strong></legend>
                        <label for="itemName">Item Name:</label>
                        <input type="text" id="itemName" name="itemName" required><br><br>
                         
                                <label for="photo">Photo:</label>
                                <!-- MAX_FILE_SIZE must precede the file input field -->
                                <input type="hidden" name="MAX_FILE_SIZE" value="<?= 5 * 1024 * 1024 ?>" />
                                <input type="file" id="photo" name="photo" <?= setValue("photo") ?>><br><br>
                    
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" required><br><br>
                        <label for="salePrice">Sale Price:</label>
                        <input type="number" id="salePrice" name="salePrice" step="0.01"><br><br>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea><br><br>
                        <label for="categoryId">Category ID:</label>
                        <input type="number" id="categoryId" name="categoryId" required><br><br>
                        <button type="submit" value="Add new item" name="submitAddItem">Add new item</button>
                    </fieldset>
                </form>
</div