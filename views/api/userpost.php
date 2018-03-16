<?php ?>
<form action="v1/users/login" method="post">
    <input type="text" name="name" /> <br>
    <input type="text" name="password" />
    <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
    <input type="submit" value="Login" />
</form>

