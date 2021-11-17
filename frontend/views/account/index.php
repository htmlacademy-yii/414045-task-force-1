<?php

use frontend\models\User;

/**
 * @var User $user
 */

?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <form enctype="multipart/form-data" id="account" method="post">
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <img src="<?= $user->avatar_src ?? './img/user.png' ?>" width="156" height="156">
                    <input type="file" name="avatar" id="upload-avatar">
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <div class="field-container account__input account__input--name">
                        <label for="200">Ваше имя</label>
                        <input class="input textarea" id="200" name="" placeholder="<?= $user->name ?>" disabled>
                    </div>
                    <div class="field-container account__input account__input--email">
                        <label for="201">email</label>
                        <input class="input textarea" id="201" name="" placeholder="<?= $user->email ?>">
                    </div>
                    <div class="field-container account__input account__input--address">
                        <label for="202">Адрес</label>
                        <input class="input textarea" id="202" name="" placeholder="<?= $user->full_address ?>">
                    </div>
                    <div class="field-container account__input account__input--date">
                        <label for="203">День рождения</label>
                        <input id="203" class="input-middle input input-date" type="text" placeholder="<?= $user->birthday ?>">
                    </div>
                    <div class="field-container account__input account__input--info">
                        <label for="204">Информация о себе</label>
                        <textarea class="input textarea" rows="7" id="204" name="" placeholder="<?= $user->about ?? 'Place your text' ?>"></textarea>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Курьерские услуги</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Грузоперевозки</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                        <span>Перевод текстов</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Ремонт транспорта</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                        <span>Удалённая помощь</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" id="210" type="checkbox" name="" value="">
                        <span>Выезд на стрелку</span>
                    </label>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="field-container account__input">
                    <label for="211">Новый пароль</label>
                    <input class="input textarea" type="password" id="211" name="" value="moiparol">
                </div>
                <div class="field-container account__input">
                    <label for="212">Повтор пароля</label>
                    <input class="input textarea" type="password" id="212" name="" value="moiparol">
                </div>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="field-container account__input">
                    <label for="213">Телефон</label>
                    <input class="input textarea" type="tel" id="213" name="" placeholder="<?= $user->phone ?>">
                </div>
                <div class="field-container account__input">
                    <label for="214">Skype</label>
                    <input class="input textarea" type="password" id="214" name="" placeholder="<?= $user->skype ?>">
                </div>
                <div class="field-container account__input">
                    <label for="215">Другой мессенджер</label>
                    <input class="input textarea" id="215" name="" placeholder="<?= $user->over_messenger ?>">
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Новое сообщение</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Действия по заданию</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Новый отзыв</span>
                    </label>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                        <span>Показывать мои контакты только заказчику</span>
                    </label>
                    <label class="checkbox__legend">
                        <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                        <span>Не показывать мой профиль</span>
                    </label>
                </div>
            </div>
        </div>
        <button class="button" type="submit">Сохранить изменения</button>
    </form>
</section>
