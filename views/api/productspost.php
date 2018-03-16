<?php ?>
<form action="v1/products" method="post">
    <label >Название</label>
    <input type="text" name="name" /> <br>
    <label >Цена</label>
    <input type="text" name="price" /> <br>
    <label >Количество</label>
    <input type="text" name="amount" />
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <input type="submit" value="Login" />
</form>

