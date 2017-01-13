<?php

use components\rbac\CurrentUserRule;
use yii\db\Migration;

class m151124_131143_init_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        // добавляем правило "self"
        $currentUserRule = new CurrentUserRule();
        $auth->add($currentUserRule);

        // добавляем разрешение "updateUserPassword"
        $updateUserPassword = $auth->createPermission('updateUserPassword');
        $updateUserPassword->description = 'Обновить пароль пользователя';
        $auth->add($updateUserPassword);

        // добавляем разрешение "updateUserAccount"
        $updateUserAccount = $auth->createPermission('updateUserAccount');
        $updateUserAccount->description = 'Обновить аккаунт пользователя';
        $auth->add($updateUserAccount);
        $auth->addChild($updateUserAccount, $updateUserPassword);

        // добавляем разрешение "updateUserProfile"
        $updateUserProfile = $auth->createPermission('updateUserProfile');
        $updateUserProfile->description = 'Обновить профиль пользователя';
        $auth->add($updateUserProfile);

        // добавляем разрешение "showUserProfile"
        $showUserProfile = $auth->createPermission('showUserProfile');
        $showUserProfile->description = 'Просмотр профиля пользователя';
        $auth->add($showUserProfile);

        // добавляем разрешение "updateUser"
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Обновить пользователя';
        $auth->add($updateUser);
        $auth->addChild($updateUser, $updateUserAccount);
        $auth->addChild($updateUser, $updateUserProfile);
        $auth->addChild($updateUser, $showUserProfile);

        // добавляем разрешение "blockUser"
        $blockUser = $auth->createPermission('blockUser');
        $blockUser->description = 'Заблокировать пользователя';
        $auth->add($blockUser);

        // добавляем разрешение "deleteUser"
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Удалить пользователя';
        $auth->add($deleteUser);

        // добавляем разрешение "administrateUserCreate"
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Управление пользователями: Создать пользователя';
        $auth->add($createUser);

        // добавляем разрешение "administrateUser"
        $administrateUser = $auth->createPermission('administrateUser');
        $administrateUser->description = 'Управление пользователями';
        $auth->add($administrateUser);
        $auth->addChild($administrateUser, $updateUser);
        $auth->addChild($administrateUser, $createUser);
        $auth->addChild($administrateUser, $blockUser);
        $auth->addChild($administrateUser, $deleteUser);

        // добавляем разрешение "updateSelfPassword"
        $updateSelfPassword = $auth->createPermission('updateSelfPassword');
        $updateSelfPassword->description = 'Обновить свой пароль пользователя';
//        $updateSelfPassword->ruleName = $currentUserRule->name; //только для себя вообще
        $auth->add($updateSelfPassword);

        // добавляем разрешение "updateSelfAccount"
        $updateSelfAccount = $auth->createPermission('updateSelfAccount');
        $updateSelfAccount->description = 'Обновить свой аккаунт пользователя';
        $auth->add($updateSelfAccount);
        $auth->addChild($updateSelfAccount, $updateSelfPassword);

        // добавляем разрешение "updateSelfProfile"
        $updateSelfProfile = $auth->createPermission('updateSelfProfile');
        $updateSelfProfile->description = 'Обновить свой профиль пользователя';
//        $updateSelfProfile->ruleName = $currentUserRule->name; //только для себя вообще
        $auth->add($updateSelfProfile);

        // добавляем разрешение "showSelfProfile"
        $showSelfProfile = $auth->createPermission('showSelfProfile');
        $showSelfProfile->description = 'Просмотр своего профиля пользователя';
        $showSelfProfile->ruleName = $currentUserRule->name;
        $auth->add($showSelfProfile);

        // добавляем разрешение "updateSelf"
        $updateSelf = $auth->createPermission('updateSelf');
        $updateSelf->description = 'Обновить своего пользователя';
        $auth->add($updateSelf);
        $auth->addChild($updateSelf, $updateSelfAccount);
        $auth->addChild($updateSelf, $updateSelfProfile);
        $auth->addChild($updateSelf, $showSelfProfile);

        // добавляем разрешение "administrateRbac"
        $administrateRbac = $auth->createPermission('administrateRbac');
        $administrateRbac->description = 'Управление контролем доступа';
        $auth->add($administrateRbac);

        // добавляем роль "user" и даём роли разрешение "$updateUserPassword" "$updateSelfProfile"
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $updateSelf);

        // добавляем роль "admin" и даём роли разрешение "updatePost"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $administrateUser);
        $auth->addChild($admin, $administrateRbac);
        $auth->addChild($admin, $user);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        //Удаляем всё связанное с rbac
        // return Yii::$app->authManager->removeAll() | 1;

        // Находим роль
        $user = $auth->getRole('user');
        $admin = $auth->getRole('admin');
        // Находим правило
        $currentUserRule = $auth->getRule('isCurrentUser');


        // находим разрешение "updateUserPassword"
        $updateUserPassword = $auth->getPermission('updateUserPassword');

        // находим разрешение "updateUserAccount"
        $updateUserAccount = $auth->getPermission('updateUserAccount');
        $auth->removeChild($updateUserAccount, $updateUserPassword);

        // находим разрешение "updateUserProfile"
        $updateUserProfile = $auth->getPermission('updateUserProfile');

        // находим разрешение "showUserProfile"
        $showUserProfile = $auth->getPermission('showUserProfile');

        // находим разрешение "updateUser"
        $updateUser = $auth->getPermission('updateUser');
        $auth->removeChild($updateUser, $updateUserAccount);
        $auth->removeChild($updateUser, $updateUserProfile);
        $auth->removeChild($updateUser, $showUserProfile);

        // находим разрешение "blockUser"
        $blockUser = $auth->getPermission('blockUser');

        // находим разрешение "deleteUser"
        $deleteUser = $auth->getPermission('deleteUser');

        // находим разрешение "administrateUserCreate"
        $createUser = $auth->getPermission('createUser');

        // находим разрешение "administrateUser"
        $administrateUser = $auth->getPermission('administrateUser');
        $auth->removeChild($administrateUser, $updateUser);
        $auth->removeChild($administrateUser, $createUser);
        $auth->removeChild($administrateUser, $blockUser);
        $auth->removeChild($administrateUser, $deleteUser);

        // находим разрешение "updateSelfPassword"
        $updateSelfPassword = $auth->getPermission('updateSelfPassword');

        // находим разрешение "updateSelfAccount"
        $updateSelfAccount = $auth->getPermission('updateSelfAccount');
        $auth->removeChild($updateSelfAccount, $updateSelfPassword);

        // находим разрешение "updateSelfProfile"
        $updateSelfProfile = $auth->getPermission('updateSelfProfile');

        // находим разрешение "showSelfProfile"
        $showSelfProfile = $auth->getPermission('showSelfProfile');

        // находим разрешение "updateSelf"
        $updateSelf = $auth->getPermission('updateSelf');
        $auth->removeChild($updateSelf, $updateSelfAccount);
        $auth->removeChild($updateSelf, $updateSelfProfile);
        $auth->removeChild($updateSelf, $showSelfProfile);

        // находим разрешение "administrateRbac"
        $administrateRbac = $auth->getPermission('administrateRbac');

        // находим роль "admin" и отбираем у роли разрешение "updatePost"
        // а также все разрешения роли "author"
        $auth->removeChild($admin, $administrateUser);
        $auth->removeChild($admin, $administrateRbac);
        $auth->removeChild($admin, $user);

        // находим роль "user" и отбираем у роли разрешение "$updateUserPassword" "$updateSelfProfile"
        $auth->removeChild($user, $updateSelf);

        // удаляем разрешения
        $auth->remove($updateUserProfile);
        $auth->remove($showUserProfile);
        $auth->remove($updateUserPassword);
        $auth->remove($updateUserAccount);
        $auth->remove($updateUser);
        $auth->remove($createUser);
        $auth->remove($blockUser);
        $auth->remove($deleteUser);
        $auth->remove($administrateUser);

        $auth->remove($updateSelfPassword);
        $auth->remove($updateSelfAccount);
        $auth->remove($updateSelfProfile);
        $auth->remove($showSelfProfile);
        $auth->remove($updateSelf);

        $auth->remove($administrateRbac);

        // удаляем привязку
        $auth->revoke($admin, 1);
//        $auth->removeAllAssignments();

        // удаляем роли
        $auth->remove($admin);
        $auth->remove($user);

        // удаляем правило
        $auth->remove($currentUserRule);
    }
}
