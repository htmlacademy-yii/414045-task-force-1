<?php
/**
 * @var User $user ;
 * @var string $userAge ;
 * @var string $countUserTasksDone
 * @var ActiveDataProvider $dataProvider ;
 * @var string $lastActivity ;
 * @var bool $isFavorite ;
 * @var float $rating ;
 * @var bool $userIsExecutorForCurrentUser ;
 */

use Components\Constants\UserConstants;
use Components\Routes\Route;
use frontend\models\User;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="<?= $user->avatar_src ?? UserConstants::USER_DEFAULT_AVATAR_SRC ?>" width="120" height="120"
                 alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= $user->name ?></h1>
                <p><?= $user->full_address ?>, <?= $userAge ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="<?= ($rating <= $i) ? 'star-disabled' : '' ?>"></span>
                    <?php endfor; ?>
                    <b><?= $rating ?></b>
                </div>
                <b class="done-task">Выполнил <?= $countUserTasksDone ?> заказов</b><b
                        class="done-review">Получил <?= count($user->reviews) ?>
                    отзывов</b>
            </div>
            <div class="content-view__headline user__card-bookmark <?= $isFavorite ? 'user__card-bookmark--current' : '' ?>">
                <span><?= $lastActivity ?></span>
                <a href="<?= Route::addInFavorite($user->id) ?>"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $user->about ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->specialties as $specialty): ?>
                        <a href="<?= Route::getTasks($specialty->id) ?>"
                           class="link-regular"><?= $specialty->title ?></a>
                    <?php endforeach; ?>
                </div>
                <?php if (!$user->userSettings->is_hidden || $userIsExecutorForCurrentUser): ?>
                    <h3 class="content-view__h3">Контакты</h3>
                    <div class="user__card-link">
                        <a class="user__card-link--tel link-regular" href="#"><?= $user->phone ?></a>
                        <a class="user__card-link--email link-regular" href="#"><?= $user->email ?></a>
                        <?php if ($user->skype): ?>
                            <a class="user__card-link--skype link-regular" href="#"><?= $user->skype ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php foreach ($user->portfolios as $portfolio): ?>
                    <a href="<?= $portfolio->img_src ?>" target="_blank"><img src="<?= $portfolio->img_src ?>"
                                                                              width="85" height="86" alt="Фото работы"></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php if (count($user->reviews)): ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($user->reviews) ?>)</span></h2>
            <?php echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'reviewsList',
                'layout' => "{items}{pager}",
                'options' => [
                    'class' => 'content-view__feedback-wrapper reviews-wrapper'
                ],
                'itemOptions' => [
                    'class' => 'feedback-card__reviews',
                ],
            ]) ?>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
