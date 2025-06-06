<?php
session_start();
$messages = ['success' => '', 'info' => '', 'fio' => '', 'number' => '', 'email' => '', 'date' => '', 'radio' => '', 'language' => '', 'bio' => '', 'check' => ''];
$values = ['fio' => '', 'number' => '', 'email' => '', 'date' => '', 'radio' => '', 'bio' => '', 'check' => ''];
$errors = ['fio' => NULL, 'number' => NULL, 'email' => NULL, 'date' => NULL, 'radio' => NULL, 'language' => NULL, 'bio' => NULL, 'check' => NULL];
$languages = [];
$log = false;

if (!empty($_SESSION['login']) && !empty($_SESSION['user_id'])) {
    $log = true;
    
    $user = 'u68918'; 
    $pass = '7758388'; 
    $db = new PDO('mysql:host=localhost;dbname=u68918', $user, $pass,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    try {
  
        $stmt = $db->prepare("SELECT * FROM dannye WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data) {
            $values['fio'] = $user_data['fio'];
            $values['number'] = $user_data['number'];
            $values['email'] = $user_data['email'];
            $values['date'] = $user_data['dat'];
            $values['radio'] = $user_data['radio'];
            $values['bio'] = $user_data['bio'];
            
            $stmt = $db->prepare("SELECT al.name FROM form_dannd_l fdl 
                                JOIN all_languages al ON fdl.id_lang = al.id 
                                WHERE fdl.id_form = ?");
            $stmt->execute([$user_data['id']]);
            $languages = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            $_SESSION['form_id'] = $user_data['id'];
        }
    } catch (PDOException $e) {
        $messages['info'] = 'Ошибка загрузки данных: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="img\Drupal.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <link href="styleproject.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!--
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap"
      rel="stylesheet"
    />-->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <title>Drupal</title>
  </head>
  <body>
    <!--Header-->

    <section id="block-support-main" class="block blockh block-block-content block-block-content2f979322-24f2-4ba0-a45e-930ca3dc84a6 clearfix">
      <div class="">
        <div class="video">
          <div class="vid_bg"></div>
          <video autoplay="autoplay" loop="" class="fillWidth" playsinline preload="auto" muted>
            <source src="img/video.mp4" type="video/mp4" />
            Video lost
          </video>
          <img src="img/MaskGroup.png" class="mkg" />
          <div class="mt-5 mb-5 header">
            <div class="container4">
              <div class="mb-5 navbar t">
                <img class="rupal-coder" src="img/Group 9.png" />
                <a href="#" style="text-decoration: underline; color: #fff; text-decoration-color: #f14d34; text-decoration-thickness: 2px">ПОДДЕРЖКА DRUPAL</a>
                <div class="line1"></div>
                <div class="dropdown">
                  <button class="dropbtn">
                    АДМИНИСТРИРОВАНИЕ
                    <i class="fa fa-caret-down"></i>
                  </button>
                  <div class="dropdown-content">
                    <a href="#">Миграция</a>
                    <a href="#">Бэкапы</a>
                    <a href="#">Аудит безопасности</a>
                    <a href="#">Оптимизация скорости</a>
                    <a href="#">Переезд на HTTPS</a>
                  </div>
                </div>
                <a href="#">ПРОДВИЖЕНИЕ</a>
                <a href="#">РЕКЛАМА</a>

                <div class="dropdown">
                  <button class="dropbtn">
                    О НАС
                    <i class="fa fa-caret-down"></i>
                  </button>
                  <div class="dropdown-content">
                    <a href="#">Команда</a>
                    <a href="#">Drupalgive</a>
                    <a href="#">Блог</a>
                    <a href="#">Курсы Drupal</a>
                  </div>
                </div>
                <a href="#">ПРОЕКТ</a>
                <a href="#">КОНТАКТЫ</a>
              </div>
              <div class="content-flex">
                <div class="mx-auto mx-5 header_title">
                  <div class="ptitle">
                    Поддержка<br />сайтов на Drupal
                  </div>
                  <div class="ptext">Сопровождение и поддержка сайтов<br />на CMS Drupal любых версий и запущенности</div>

                  <div class = "button-container">
                    <button class="my-5 ml-1 header_button">
                      <div>
                        <a href="#tarif" style="color: #fff; text-decoration: none">ТАРИФЫ</a>
                      </div>
                    </button>
                  </div>
                </div>

                <div class="my-auto pt-4 header_box">
                  <div>
                    <div class="headbl header_block_text">#1</div>
                    <div class="headbltext">Drupal-разработчик  <br /> в России по версии  <br /> Рейтинга Рунета</div>
                  </div>
                  <div>
                    <div class="headbl">3+</div>
                    <div class="headbltext special-block">средний опыт <br /> специалистов более  <br /> 3 лет</div>
                  </div>
                  <div>
                    <div class="headbl">14</div>
                    <div class="headbltext special-block">лет опыта в сфере Drupal  <br />  <br />  <br /> </div>
                  </div>
                  <div>
                    <div class="headbl">50+</div>
                    <div class="headbltext">модулей и тем  <br /> в формате DrupalGive</div>
                  </div>
                  <div>
                    <div class="headbl">90 000+</div>
                    <div class="headbltext">часов поддержки  <br /> сайтов на Drupal</div>
                  </div>
                  <div>
                    <div class="headbl">300+</div>
                    <div class="headbltext">Проектов  <br /> на поддержке</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--Меню  для мобайл-->
    <div class="mobile_head_menu">
      <div class="container">
        <img src="img/Group 9.png" alt="" />
        <div class="mob_menu">
          <div class="mob_menu_1"></div>
        </div>
      </div>
    </div>

    <nav>
      <ul class="menu">
        <li><span class="highlight-text">ПОДДЕРЖКА DRUPAL</span></li>
        <li class="menu_podcat">
          <span>АДМИНИСТРИРОВАНИЕ</span>
          <ul>
            <li>МИГРАЦИЯ</li>
            <li>БЭКАПЫ</li>
            <li>АУДИТ БЕЗОПАСНОСТИ</li>
            <li>ОПТИМИЗАЦИЯ СКОРОСТИ</li>
            <li>ПЕРЕЕЗД НА HTTPS</li>
          </ul>
        </li>
        <li>ПРОДВИЖЕНИЕ</li>
        <li>РЕКЛАМА</li>
        <li class="menu_podcat">
          <span>О НАС</span>
          <ul>
            <li>КОМАНДА</li>
            <li>DRUPALGIVE</li>
            <li>БЛОГ</li>
            <li>КУРСЫ DRUPAL</li>
          </ul>
        </li>
        <li>ПРОЕКТЫ</li>
        <li>КОНТАКТЫ</li>
      </ul>
    </nav>

    <div id="main">
      <!-- Сетка 4х2-->
      <div>
        <section class="about">
          <div class="mt-5 container">
            <div class="row">
              <div class="col-6">
                <h2 class="text-left">13 лет совершенствуем компетенции в Друпал <br /> поддержке!</h2>
                <div class="pt-3 pb-5">
                  <p>Разрабатываем и оптимизируем модели, расширяем функциональность сайтов, обновляем дизайн</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-1.svg" alt="картинка 1" />
                </div>
                <p class="text-left">
                  Добавление <br />
                  информации на сайт, <br />создание новых <br />
                  разделов
                </p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-2.svg" alt="картинка 2" />
                </div>
                <p class="text-left">
                  Разработка <br />и оптимизация<br />
                  модулей сайта
                </p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-3.svg" alt="картинка 3" />
                </div>
                <p class="text-left">
                  Интеграция с CRM,<br />
                  1С, платежными <br />системами, любыми<br />
                  веб-сервисами
                </p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-4.svg" alt="картинка 4" />
                </div>
                <p class="text-left">
                  Любые доработки<br />
                  функционала <br />и дизайна
                </p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-5.svg" alt="картинка 5" />
                </div>
                <p class="text-left">Аудит и мониторинг <br />безопасности Drupal <br />сайтов</p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-6.svg" alt="картинка 6" />
                </div>
                <p class="text-left">Миграция, импорт <br />контента и апгрейд <br />Drupal</p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-7.svg" alt="картинка 7" />
                </div>
                <p class="text-left">Оптимизация <br />и ускорение <br />Drupal сайтов</p>
              </div>
              <div class="col-6 col-sm-3">
                <div class="dd1">
                  <img src="img/competency-8.svg" alt="картинка 8" />
                </div>
                <p class="text-left">Веб-маркетинг, <br />консультации <br />и работы по SEO</p>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- 8 Блоков -->

      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-4 mt-5">
            <h2 class="text-center">
              Поддержка <br />
              от Drupal-coder
            </h2>
          </div>
        </div>
        <div class="mb-5 row row-flex advantages-row poddr">
          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>01.</p>
            <h5>Постановка задачи по Email</h5>

            <div class="text">Удобная и привычная модель постановки задач, при которой задачи фиксируются и никогда не теряются.</div>
            <div class="iconss"><img src="img/support1.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>02.</p>
            <h5>Система Helpdesk – отчетность, прозрачность</h5>

            <div class="text">Возможность посмотреть все заявки в работе и отработанные часы в личном кабинете через браузер. Более 122737 тикетов уже выполнено!</div>
            <div class="iconss"><img src="img/support2.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>03.</p>
            <h5>Расширенная техническая поддержка</h5>

            <div class="text">Возможность организации расширенной техподдержки с 6:00 до 22:00 без выходных.</div>
            <div class="iconss"><img src="img/support3.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>04.</p>
            <h5>Персональный менеджер проекта</h5>

            <div class="text">Ваш менеджер проекта всегда в курсе текущего состояния проекта и в любой момент готов ответить на любые вопросы.</div>
            <div class="iconss"><img src="img/support4.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>05.</p>
            <h5>Удобные способы оплаты</h5>

            <div class="text">Безналичный расчет по договору или электронные деньги: WebMoney, Яндекс.Деньги, Paypal.</div>
            <div class="iconss"><img src="img/support5.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>06.</p>
            <h5>Работаем с SLA и NDA</h5>

            <div class="text">Работа в рамках соглашений о конфиденциальности и об уровне качества работ.</div>
            <div class="iconss"><img src="img/support6.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>07.</p>
            <h5>Штатные специалисты</h5>

            <div class="text">Надежные штатные специалисты, никаких фрилансеров.</div>
            <div class="iconss"><img src="img/support7.svg" /></div>
          </div>

          <div class="block col-sm-6 col-md-3 col-xs-12 mb-2 ml-2">
            <p>08.</p>
            <h5>Удобные каналы связи</h5>

            <div class="text">Консультации по телефону, скайпу, в мессенджерах.</div>
            <div class="iconss"><img src="img/support8.svg" /></div>
          </div>
        </div>
      </div>

      <div>
<div class="fon">
  <img src="img/MaskGroup.png" class="mkg" />
  <section class="laptop">
    <div class="container1">
      <div class="row d-flex ffw justify-content-between">
        <div>
          <img src="img/laptop.png" class="laptop_img" alt="laptop" />
        </div>
        <div class="row ffw2">
          <div class="pl-5 text-laptop col-12 col-sm-6">
            <h1>Экспертиза в Drupal, <br />опыт 14 лет!</h1>
            <div class="container">
              <div class="row">
                <div class="col-12 col-sm-6"> <!-- Меняем классы для адаптивности -->
                  <div class="mt-4 boxred">
                    <p>
                      Только системный подход - <br />контроль версий,<br />
                      резервирование <br />
                      и тестирование!
                    </p>
                  </div>
                </div>
                <div class="col-12 col-sm-6"> <!-- Меняем классы для адаптивности -->
                  <div class="mt-4 boxred">
                    <p>
                      Только в Drupal сайты,<br />
                      не берем на поддержку сайты<br />
                      на других CMS!
                    </p>
                  </div>
                </div>
                <div class="col-12 col-sm-6"> <!-- Меняем классы для адаптивности -->
                  <div class="mt-4 boxred">
                      <p>
                              Участвуем в разработке<br />
                              ядра Drupal и модулей на <br />
                              Drupal.org, разрабатываем <br />
                              свои модули Drupal
                              <span class="highlight-text">свои модули Drupal</span>
                      </p>
                  </div>
                </div>
                <div class="col-12 col-sm-6"> <!-- Меняем классы для адаптивности -->
                  <div class="mt-4 boxred">
                    <p>
                      Поддерживаем сайты на <br /> Drupal 5,6,7 и 8
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
        <!--Тарифы-->

        <section class="p-5 fon_tariff1">
          <h2 class="m-5 pt-5 block-title text-center">Тарифы</h2>
          <div id="tarif">
            <div class="tariffs">
              <div class="fon_tariff">
                <!-- <img src="img/D.jpg" /> -->
                <div class="container">
                  <div class="row row-flex tariffs-row">
                    <div class="mb-5 col-sm-4 col-xs-12">
                      <div class="tariff">
                        <div class="tariff-header">
                          <h3 class="tariff-title pt-5 pl-4">Стартовый</h3>
                          <div class="line2 bg2"></div>
                        </div>
                        <div class="tariff-body">
                          <ol>
                            <li>Консультации и работы по SEO</li>
                            <li>Услуги дизайнера</li>
                            <li>Неиспользованные оплаченные часы переносятся на следующий месяц</li>
                            <li>Предоплата от 6 000 рублей в месяц</li>
                          </ol>
                        </div>
                        <div class="pb-4 tariff-footer">
                          <a href="#footer" class="py-3 btn btn-itd text-uppercase">Свяжитесь с нами</a>
                        </div>
                      </div>
                    </div>

                    <div class="mb-5 col-sm-4 col-xs-12">
                      <div class="tariff">
                        <div class="tariff-header">
                          <h3 class="tariff-title pt-5 pl-4">Бизнес</h3>
                          <div class="line2 bg2"></div>
                        </div>
                        <div class="tariff-body">
                          <ol>
                            <li>Консультации и работы по SEO</li>
                            <li>Услуги дизайнера</li>
                            <li>Высокое время реакции – до 2 часов</li>
                            <li>Неиспользованные часы не </br> переносятся</li>
                            <li>Предоплата от 30 000 рублей в месяц</li>
                          </ol>
                        </div>
                        <div class="pb-4 tariff-footer">
                          <a href="#footer" class="py-3 btn btn-itd text-uppercase">Свяжитесь с нами</a>
                        </div>
                      </div>
                    </div>

                    <div class="mb-5 col-sm-4 col-xs-12">
                      <div class="tariff">
                        <div class="tariff-header">
                          <h3 class="tariff-title pt-5 pl-4">VIP</h3>
                          <div class="line2 bg2"></div>
                        </div>
                        <div class="tariff-body">
                          <ol>
                            <li>Консультации и работы по SEO</li>
                            <li>Услуги дизайнера</li>
                            <li>Максимальное время реакции - в день обращения</li>
                            <li>Неиспользованные часы не переносятся</li>
                            <li>Предоплата от 270 000 рублей в месяц</li>
                          </ol>
                        </div>
                        <div class="pb-4 tariff-footer">
                          <a href="#footer" class="py-3 btn btn-itd text-uppercase">Свяжитесь с нами</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-5 d-flex justify-content-center">
                    <div class="tariffs-ps">
                      Вам не подходят наши тарифы? Оставьте заявку и мы
                      <br />предложим вам индивидуальные условия!
                      <p>
                        <a href="#footer" class="tariff-individ">Получить индивидуальный тариф</a>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!--Картинки flex-->
        
        <!--  Задачи 3х1-->
        <div>
          <section class="tasks m-5 pt-5">
            <div class="container">
              <div class="mt-5 row">
                <div class="col-9">
                  <h2 class="text-left text-uppercase color1 mb-5">Наши профессиональные разработчики выполняют быстро любые задачи</h2>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-3 col-md-6 col-sm-12">
                  <img src="img/competency-20.svg" alt="картинка 1" />
                  <h2>от 1ч</h2>
                  <p class="text-left">Настройка события GA в интернет-магазине</p>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12">
                  <img src="img/competency-21.svg" alt="картинка 2" />
                  <h2>от 20ч</h2>
                  <p class="text-left">Разработка мобильной версии сайта</p>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12">
                  <img src="img/competency-22.svg" alt="картинка 3" />
                  <h2>от 8ч</h2>
                  <p class="text-left">Интеграция смодуля оплаты</p>
                </div>
              </div>
            </div>
          </section>
        </div>

        <!--Команда -->
        <div>
  <section class="tasks">
    <div class="mt-5 d-flex justify-content-center">
      <div class="comanda-title m-5">
        <h2>Команда</h2>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="mb-4 col-xl-4 col-md-6 col-sm-6 col-6">
          <img src="img/IMG_2472_0.jpg" alt="фото 1" />
          <div class="my-5">
            <h5>Сергей Синица</h5>
            <p class="text-left">Руководитель отдела веб-разработки, канд. техн. наук, заместитель директора</p>
          </div>
        </div>
        <div class="mb-4 col-xl-4 col-md-6 col-sm-6 col-6">
          <img src="img/IMG_2539_0.jpg" alt="фото 2" />
          <div class="my-5">
            <h5>Роман Агабеков</h5>
            <p class="text-left">Руководитель отдела DevOPS, директор</p>
          </div>
        </div>
        <div class="mb-4 col-xl-4 col-md-6 col-sm-6 col-6">
          <img src="img/IMG_2474_1.jpg" alt="фото 3" />
          <div class="my-5">
            <h5>Алексей Синица</h5>
            <p class="text-left">Руководитель отдела поддержки сайтов</p>
          </div>
        </div>
        <div class="mb-4 col-xl-4 col-md-6 col-sm-6 col-6">
          <img src="img/IMG_2522_0.jpg" alt="фото 4" />
          <div class="my-5">
            <h5>Дарья Бочкарёва</h5>
            <p class="text-left">Руководитель отдела продвижения, контекстной рекламы и контент-поддержки сайтов</p>
          </div>
        </div>
        <div class="mb-4 col-xl-4 col-md-6 col-sm-6 col-6">
          <img src="img/IMG_9971_16.jpg" alt="фото 5" />
          <div class="my-5">
            <h5>Ирина Торкунова</h5>
            <p class="text-left">Менеджер по работе с клиентами</p>
          </div>
        </div>
        
      </div>
    </div>
  </section>
</div>
<div class="text-center" style = "display: flex;justify-content: center;">
    <button class="my-5 header_button" style="background-color: white; color: black; padding: 10px 20px;border-radius: 2px;
  border-color: rgb(0, 0, 0);">
        <a href="#tarif" style="color: black; text-decoration: none;">ВСЯ КОМАНДА</a>
    </button>
</div>
<div class="container2">
          <div class="m-5">
            <h1 class="text-flex text-center pb-4">Последние кейсы</h1>
            <div class="fleximg">
              <ul>
                <li>
                  <img src="img/image 9.2.jpeg" alt="1" loading="lazy" />
                  <h4>
                    Настройка кэширования <br /> данных. Апгрейд сервера.<br /> Ускорение работы сайта в <br />30 раз!
                    Яндекс. Маркета
                  </h4>
                  <p>
                    22.04.2019<br />
                    Влияние скорости загрузки страниц сайта на отказы и <br />  конверсии.Кейс ускорения
                  </p>
                </li>
                <li style="width: 66%">
                  <div id="tekst_sverhu_kartinki">
                    <img src="img/image 11.1.png" alt="2" loading="lazy" />
                    <div class="tekst_sverhu_kartinki_1">
                      <h4>
                        Использование отчетов <br />
                        Ecommerce в Яндекс. Маркете
                      </h4>
                    </div>
                  </div>
                </li>
                <li>
                  <div id="tekst_sverhu_kartinki">
                    <img src="img/image 7.3.png" alt="3" loading="lazy" />
                    <div class="tekst_sverhu_kartinki_1">
                      <h4>
                        Повышение конверсии <br /> страницы с формой заявки <br /> с применением AB-тестирования<br />
                        Яндекс. Маркета
                      </h4>
                      <span>22.04.2019</span>
                    </div>
                  </div>
                </li>
                <li>
                  <div id="tekst_sverhu_kartinki">
                    <img src="img/image 8.3.png" alt="4" loading="lazy" />
                    <div class="tekst_sverhu_kartinki_1">
                      <h4>
                        Drupal 7: ускорение <br /> времени генерации <br /> страниц интернет-магазина <br /> на 32%
                        Яндекс. Маркета
                      </h4>

                      <span>22.04.2019</span>
                    </div>
                  </div>
                </li>
                <li>
                  <img src="img/image 6.2.png" alt="5" loading="lazy" />
                  <h4>
                    Обмен товарам и заказами <br /> интернет-магазинов на <br /> Drupal 7 с 1C:Предприятие, <br />
                    МойСклад, Класс365
                  </h4>
                  <p>
                    22.04.2019<br />
                    Опубликован релиз модуля <br />
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!--Слайдер-->
        <div class="container3">
          <div class="text-slider">
            <div class="mt-5 d-flex justify-content-center">
              <div class="comanda-title m-5">
                <h2>Отзывы</h2>
              </div>
            </div>
            <div class="container">
              <div class="txts">
                <div class="row">
                  <div class="">
                    <div class="a" style="overflow: hidden; transition: 0.5s">
                      <div class="aa">
                        <div class="">
                          <img src="img/logo_0.png" />

                          <div class="pt-3 slidertext_text">Долгие поиски единственного и неповторимого мастера на многострадальный сайт www.cielparfum.com, который был собран крайне некомпетентным программистом и раз в месяц стабильно грозил погибнуть, привели меня на сайт и, в итоге, к ребятам из Drupal-coder. И вот уже практически полгода как не проходит и дня, чтобы я не поудивлялась и не порадовалась своему везению! Починили все, что не работало - от поиска до отображения меню. Провели редизайн - не отходя от желаемого, но со своими существенными и качественными дополнениями. Осуществили ряд проектов - конкурсы, тесты и тд. А уж мелких починок и доработок - не счесть! И главное - все качественно и быстро (не взирая на не самый "быстрый" тариф). Есть вопросы - замечательный Алексей всегда подскажет, поддержит, отремонтирует и/или просто сделает с нуля. Есть задумка для реализации - замечательный Сергей обсудит и предложит идеальный вариант. Есть проблема - замечательные Надежда и Роман починят, поправят, сделают! Ребята доказали, что эта CMS - мощная и грамотная система управления. Надеюсь, что наше сотрудничество затянется надолго! Спасибо!!!</div>
                        </div>

                        <div class="">
                          <img src="img/logo.png" />

                          <div class="pt-3 slidertext_text">Сергей — профессиональный, высококвалифицированный программист с огромным опытом в ИТ. Я долгое время общался с топ-фрилансерами (вся первая двадцатка) на веблансере и могу сказать, что С СЕРГЕЕМ ОНИ И РЯДОМ НЕ ВАЛЯЛИСЬ. Работаем с Сергеем до сих пор. С ним приятно работать, я доволен.</div>
                        </div>

                        <div class="">
                          <img src="img/farbors_ru.jpg" />

                          <div class="pt-3 slidertext_text">Выражаю глубочайшую благодарность команде специалистов компании "Инитлаб" и лично Дмитрию Купянскому и Алексею Синице. Сайт был первоклассно перевёрстан из старой табличной модели в новую на базе Drupal с дополнительной функциональностью. Всё было сделано с высочайшим качеством и точно в сроки. Всем кому хочется без нервов и лишних вопросов разработать сайт рекомендую обращаться именно к этой команде профессионалов.</div>
                        </div>

                        <div class="">
                          <img src="img/nashagazeta_ch.png" />

                          <div class="pt-3 slidertext_text">Моя электронная газета www.nashagazeta.ch существует в Швейцарии уже 10 лет. За это время мы сменили 7 специалистов по техподдержке, и только сейчас, в последние несколько месяцев, с начала сотрудничества с Алексеем Синицей и его командой, я впервые почувствовала, что у меня есть надежный технический тыл. Без громких слов и обещаний, ребята просто спокойно и качественно делают работу, быстро реагируют, находят решения, предлагают конкретные варианты улучшения функционирования сайта и сами их оперативно осуществляют. Сотрудничество с ними – одно удовольствие!</div>
                        </div>

                        <div class="">
                          <img src="img/logo-estee.png" />

                          <div class="pt-3 slidertext_text">Наша компания Estee Design занимается дизайном интерьеров. Сайт сверстан на Drupal. Искали программистов под выполнение ряда небольших изменений и корректировок по сайту. Пообщавшись с Алексеем Синицей, приняли решение о начале работ с компанией Initlab/drupal-coder. Сотрудничеством довольны на 100%. Четкая и понятная система коммуникации, достаточно оперативное решение по задачам. Дали рекомендации по улучшению програмной части сайта, исправили ряд скрытых ошибок. Никогда не пишу отзывы (нет времени), но в данном случае, по просьбе Алексея, не могу не рекомендовать Initlab другим людям - действительно компания профессионалов.</div>
                        </div>

                        <div class="">
                          <img src="img/cableman_ru.png" />

                          <div class="pt-3 slidertext_text">Наша компания за несколько лет сменила несколько команд программистов и специалистов техподдержки, и почти отчаялась найти на российском рынке адекватное профессиональное предложение по разумной цене. Пока мы не начали работать с командой "Инитлаб", воплощающей в себе все наши представления о нормальной системе взаимодействия в сочетании с профессиональным неравнодушием. Впервые в моей деловой практике я чувствую надежно прикрытыми важнейшие задачи в жизни электронного СМИ, при том, что мои коллеги работают за сотни километров от нас и мы никогда не встречались лично.</div>
                        </div>

                        <div class="">
                          <img src="img/logo_2.png" />

                          <div class="pt-3 slidertext_text">За довольно продолжительный срок (2014 - 2016 годы) весьма плотной работы (интернет-магазин на безумно замороченном Drupal 6: устраняли косяки разработчиков, ускоряли сайт, сделали множество новых функций и т.п.) - только самые добрые эмоции от работы с командой Initlab / Drupal-coder: всегда можно рассчитывать на быструю и толковую помощь, поддержку, совет. Даже сейчас, не смотря на то, что мы почти год не работали на постоянной основе (банально закончились задачи), случайно возникшая проблема с сайтом была решена мгновенно! В общем, только самые искренние благодарности и рекомендации! Спасибо! )</div>
                        </div>

                        <div class="">
                          <img src="img/lpcma_rus_v4.jpg" />

                          <div class="pt-3 slidertext_text">Хотел поблагодарить за работу над нашими сайтами. За 4 месяца работы привели сайт в порядок, а самое главное получили инструмент, с помощью мы теперь можем быстро и красиво выставлять контент для образования и работы с предприятиями http://lpcma.tsu.ru/ru/post/reference_material</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="pt-5 row">
                    <div class="">
                      <div class="button_slide">
                        <button class="b1 b11">
                          <img src="img/arrow-left.svg" alt="arrow-left" />
                        </button>

                        <div class="ednum" style="font: 3em Montserrat">01</div>
                        <div style="font: 3em Montserrat">/</div>
                        <div style="font: 3em Montserrat">08</div>

                        <button class="b1 b22">
                          <img src="img/arrow-right.svg" alt="arrow-right" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!--Слайдер-->
        <div class="mt-5 d-flex justify-content-center">
          <div class="slider-title mb-4">
            <h2>С нами работают</h2>

            <p>
              Десятки компаний доверяют нам самое ценное, что у них есть в интернете - свои <br />
              сайты. Мы доверяем всё, чтобы наше сотрудничество было долгим
            </p>
          </div>
        </div>
 <div id="main" class="">
          <div class="slider autoplay2">
            <div class="mr-1">
              <img src="img/1.png" />
            </div>
            <div class="mr-1">
              <img src="img/2.png" />
            </div>
            <div class="mr-1">
              <img src="img/КУБГУ.png" />
            </div>
            <div class="mr-1">
              <img src="img/3.png" />
            </div>
            <div class="mr-1">
              <img src="img/ВТБ.png" />
            </div>
            <div class="mr-1">
              <img src="img/Росатом.png" />
            </div>
            <div class="mr-1">
              <img src="img/4.png" />
            </div>
            <div class="mr-1">
              <img src="img/газпром.png" />
            </div>
          </div>
        </div>

 <div id="main" class="">
          <div class="slider autoplay2">
            <div class="mr-1">
              <img src="img/4.png" />
            </div>
            <div class="mr-1">
              <img src="img/Росатом.png" />
            </div>
            <div class="mr-1">
              <img src="img/1.png" />
            </div>
            <div class="mr-1">
              <img src="img/газпром.png" />
            </div>
            <div class="mr-1">
              <img src="img/ВТБ.png" />
            </div>
            <div class="mr-1">
              <img src="img/1.png" />
            </div>
            <div class="mr-1">
              <img src="img/КУБГУ.png" />
            </div>
            <div class="mr-1">
              <img src="img/газпром.png" />
            </div>
          </div>
        </div>

        <!-- 12 Пунктов -->
        <div>
          <section class="punkt12">
            <div class="container">
              <div class="row p-5">
                <div class="col-12">
                  <h2 class="text-center text-uppercase">FAQ</h2>
                </div>
              </div>
              <div class="row mb-5">
                <div class="red col-md-12 col-sm-12">
                  <h5 class="color_red text-left">1. Кто непосредственно занимается поддержкой?</h5>
                  <p>Сайты поддерживают штатные сотрудники ООО "Инитлаб", г. Краснодар, прошедшие специальное обучение и имеющие опыт работы с Друпал от 4 до 15 лет: 8 web-разработчиков, 2 специалиста по SEO, 4 системных администратора.</p>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">2. Как организована работа поддержки?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">3. Что происходит, когда отработаны все предоплаченные часы за месяц?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">4. Что происходит, когда не отработаны все предоплаченные часы за месяц?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">5. Как происходит оценка и согласование планируемого времени на выполнение заявок?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">6. Сколько программистов выделяется на проект?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">7. Как подать заявку на внесение изменений на сайте?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">8. Как подать заявку на добавление пользователя, изменение настроек веб-сервера и других задач по администрированию?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">9. В течение какого времени начинается работа по заявке?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">10. В какое время работает поддержка?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">11. Подходят ли услуги поддержки, если необходимо произвести обновление ядра Drupal или модулей?</h5>
                </div>
                <div class="col-md-12 col-sm-12">
                  <h5 class="text-left">12. Можно ли пообщаться со специалистом голосом или в мессенджере?</h5>
                </div>
              </div>
            </div>
          </section>
        </div>

        <!--Footer-->
        <div id="footer">
          <footer class="footer">
            <div class="footer-container">
              <div class="text-content">
                <div class="slewa">
                  <div class="cont">
                    <div class="zayawka">
                      <b>
                        Оставить заявку на
                        <div>поддержку сайта</div>
                      </b>
                    </div>
                    <div class="podderjka">Срочно нужна поддержка сайта? Ваша команда не успевает справиться самостоятельно или предыдущий подрядчик не справился с работой? Тогда вам точно к нам! Просто оставьте заявку и наш менеджер с вами свяжется!</div>
                    <div style="text-align: left;">
                      <div class="nomertel">
                        <a href=""> 8 800 222-26-73</a>
                      </div>
                      <div class="pochta">
                        <a href=""> info@dpural-coder.ru</a>
                      </div>
                    </div>
                  </div>
                </div>
               
                 <form action="" method="post" class="form">
	<?php if($log && isset($_SESSION['form_id'])): ?>
        <input type="hidden" name="form_id" value="<?php echo $_SESSION['form_id']; ?>">
    <?php endif; ?>
      <div class="head">
        <h2><b>Форма обратной связи</b></h2>
      </div>

      <div class="mess"><?php if(isset($messages['success'])) echo $messages['success']; ?></div>
      <div class="mess mess_info"><?php if(isset($messages['info'])) echo $messages['info']; ?></div>
      <div>
        <label> <input name="fio" class="input <?php echo ($errors['fio'] != NULL) ? 'red' : ''; ?>" value="<?php echo $values['fio']; ?>" type="text" placeholder="ФИО" /> </label>
        <div class="error"> <?php echo $messages['fio']?> </div>
      </div>
      
      <div>
        <label> <input name="number" class="input <?php echo ($errors['number'] != NULL) ? 'red' : ''; ?>" value="<?php echo $values['number']; ?>" type="tel" placeholder="Номер телефона" /> </label>
        <div class="error"> <?php echo $messages['number']?> </div>
      </div>
      
      <div>
        <label> <input name="email" class="input <?php echo ($errors['email'] != NULL) ? 'red' : ''; ?>" value="<?php echo $values['email']; ?>" type="text" placeholder="Почта" /> </label>
        <div class="error"> <?php echo $messages['email']?> </div>
      </div>
      
      <div>
        <label>
          <input name="date" class="input <?php echo ($errors['date'] != NULL) ? 'red' : ''; ?>" value="<?php if(strtotime($values['date']) > 100000) echo $values['date']; ?>" type="date" />
          <div class="error"> <?php echo $messages['date']?> </div>
        </label>
      </div>
      
      <div>
        <div>Пол</div>
        <div class="mb-1">
          <label>
            <input name="radio" class="ml-2" type="radio" value="M" <?php if($values['radio'] == 'M') echo 'checked'; ?>/>
            <span class="<?php echo ($errors['radio'] != NULL) ? 'error' : ''; ?>"> Мужской </span>
          </label>
          <label>
            <input name="radio" class="ml-4" type="radio" value="W" <?php if($values['radio'] == 'W') echo 'checked'; ?>/>
            <span class="<?php echo ($errors['radio'] != NULL) ? 'error' : ''; ?>"> Женский </span>
          </label>
        </div>
        <div class="error"> <?php echo $messages['radio']?> </div>
      </div>
      
      <div>
        <div>Любимые языки программирования</div>
        <select class="my-2 <?php echo ($errors['language'] != NULL) ? 'red' : ''; ?>" name="language[]" multiple="multiple">
          <option value="Pascal" <?php echo (in_array('Pascal', $languages)) ? 'selected' : ''; ?>>Pascal</option>
          <option value="C" <?php echo (in_array('C', $languages)) ? 'selected' : ''; ?>>C</option>
          <option value="C++" <?php echo (in_array('C++', $languages)) ? 'selected' : ''; ?>>C++</option>
          <option value="JavaScript" <?php echo (in_array('JavaScript', $languages)) ? 'selected' : ''; ?>>JavaScript</option>
          <option value="PHP" <?php echo (in_array('PHP', $languages)) ? 'selected' : ''; ?>>PHP</option>
          <option value="Python" <?php echo (in_array('Python', $languages)) ? 'selected' : ''; ?>>Python</option>
          <option value="Java" <?php echo (in_array('Java', $languages)) ? 'selected' : ''; ?>>Java</option>
          <option value="Haskel" <?php echo (in_array('Haskel', $languages)) ? 'selected' : ''; ?>>Haskel</option>
          <option value="Clojure" <?php echo (in_array('Clojure', $languages)) ? 'selected' : ''; ?>>Clojure</option>
          <option value="Scala" <?php echo (in_array('Scala', $languages)) ? 'selected' : ''; ?>>Scala</option>
        </select>
        <div class="error"> <?php echo $messages['language']?> </div>
      </div>
      
      <div class="my-2">
        <div>Биография</div>
        <label>
          <textarea name="bio" class="input <?php echo ($errors['bio'] != NULL) ? 'red' : ''; ?>" placeholder="Биография"><?php echo $values['bio']; ?></textarea>
          <div class="error"> <?php echo $messages['bio']?> </div>
        </label>
      </div>
      
      <div>
         <label>
            <input name="check" type="checkbox" <?php echo ($log || $values['check'] != NULL) ? 'checked' : ''; ?>/>
              С контрактом ознакомлен(а)
          <div class="error"> <?php echo $messages['check']?> </div>
        </label>
      </div>

      <div class="form-buttons">
    <?php if($log): ?>
        <button class="button edbut" type="submit" name="edit_form">Изменить</button>
        <button class="button logout-btn" type="submit" name="logout_form" id="logout-btn">Выйти</button>
    <?php else: ?>
        <button class="button" type="submit" id="submit-btn">Отправить</button>
        <a class="btnlike" href="login.php" id="login-link">Войти</a>
    <?php endif; ?>
</div>
    </form>
              </div>
            </div>
            <div class="ss"></div>
            <div class="footer-container">
              <div class="podfooter">
                <div class="social">
                  <ul>
                    <li class="links">
                      <a href="" title="Facebook"><img src="img/facebook.png" /></a>
                    </li>
                    <li class="links">
                      <a href="" title="Вконтакте"><img src="img/vk.png" /></a>
                    </li>
                    <li class="links">
                      <a href="" title="Telegram"> <img src="img/telegram.png" /></a>
                    </li>
                    <li class="links">
                      <a href="" title="Youtube"><img src="img/youtube.png" /></a>
                    </li>
                  </ul>
                </div>
                <div class="podpod">
                  Проект ООО «Инитлаб», Краснодар, Россия. <br />
                  Drupal валяется зарегистрированной торговой маркой Dries Buytaert.
                </div>
              </div>
            </div>
          </footer>
        </div>

        <!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> -->
        <script src="jquery-3.4.1.min.js"></script>
        <script src="slick/slick.min.js"></script>
        <script src="project.js"></script>
      </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  </body>
</html>
