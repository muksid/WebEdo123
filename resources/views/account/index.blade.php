@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="text-center text-bold" style="color: white">
                Online hisob raqam ochish
                <small><button type="button" onclick="location.href='/'" class="btn btn-block btn-success btn-flat">Bosh sahifa</button></small>
            </h1>
        </section>
        <hr>
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="container">
            <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
        <!-- Display Validation Errors -->
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Mijoz ma`lumotlari</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="{{ url('account') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    <div class="row">

                            <!-- First row -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('acc_name') ? 'has-error' : '' }}">
                                <label>Filial<sup class="text-red">*</sup></label>
                                <select name="filial_code" class="form-control select2" style="width: 100%;">
                                    @foreach($filial as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->title_ru }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('acc_name') ? 'has-error' : '' }}">
                                <label>Tashkilot turi<sup class="text-red">*</sup></label>
                                <select name="acc_type" class="form-control select2" style="width: 100%;">
                                    @foreach($accType as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->title_ru }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('acc_name') ? 'has-error' : '' }}">
                                <label>Tashkilot nomi <span class=""></span></label>
                                <input type="text" id="acc_name" name="acc_name" value="{{ old('acc_name') }}"
                                       class="form-control">
                                @if ($errors->has('acc_name'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('acc_name') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                        <!-- /.col -->

                            <!-- Second row -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('acc_inn') ? 'has-error' : '' }}">
                                <label>STIR<sup class="text-red">*</sup></label>
                                <input type="text" name="acc_inn" value="{{ old('acc_inn') }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask-inn>
                                @if ($errors->has('acc_inn'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('acc_inn') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('type_activity') ? 'has-error' : '' }}">
                                <label>Faoliyat turi<sup class="text-red">*</sup></label>
                                <input type="text" name="type_activity" value="{{ old('type_activity') }}" class="form-control" placeholder="Enter ...">
                                @if ($errors->has('type_activity'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('type_activity') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="form-group {{ $errors->has('acc_address') ? 'has-error' : '' }}">
                                <label>Manzil<sup class="text-red">*</sup></label>
                                <input type="text" name="acc_address" value="{{ old('acc_address') }}" class="form-control" placeholder="Enter ...">
                                @if ($errors->has('acc_address'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('acc_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group {{ $errors->has('owner_fname') ? 'has-error' : '' }}">
                                <label>Ismi <span class="text-muted">(Tashkilot rahbari)</span><sup class="text-red">*</sup></label>
                                <input type="text" name="owner_fname" value="{{ old('owner_fname') }}" class="form-control" placeholder="Enter ...">
                                @if ($errors->has('owner_fname'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('owner_fname') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group {{ $errors->has('owner_lname') ? 'has-error' : '' }}">
                                <label>Familiyasi <span class="text-muted">(Tashkilot rahbari)</span><sup class="text-red">*</sup></label>
                                <input type="text" name="owner_lname" value="{{ old('owner_lname') }}" class="form-control" placeholder="Enter ...">
                                @if ($errors->has('owner_lname'))
                                    <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('owner_lname') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sharifi <span class="text-muted">(Tashkilot rahbari)</span></label>
                                <input type="text" name="owner_sname" class="form-control" placeholder="Enter ...">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <!-- phone mask -->
                            <div class="form-group">
                                <label>Telefon raqam:</label>

                                <div class="input-group {{ $errors->has('owner_phone') ? 'has-error' : '' }}">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                                    @if ($errors->has('owner_phone'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('owner_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.col -->
                            <!-- four row -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <!-- file attachment -->
                            <div class="form-group">
                                <div class="btn btn-success btn-file">
                                    <i class="fa fa-user"></i> Pasport
                                    <input type="file" name="account_file[]" multiple>
                                </div>
                                <p class="help-block">(Rahbar pasporti skaneri)</p>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <!-- file attachment -->
                            <div class="form-group">
                                <div class="btn btn-success btn-file">
                                    <i class="fa fa-paperclip"></i> Guvohnoma
                                    <input type="file" name="account_file[]" multiple>
                                </div>
                                <p class="help-block">(Tashkilot Guvohnomasi)</p>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <!-- file attachment -->
                            <div class="form-group">
                                <div class="btn btn-success btn-file">
                                    <i class="fa fa-paperclip"></i> Ma`lumotnoma
                                    <input type="file" name="account_file[]" multiple>
                                </div>
                                <p class="help-block">(Davlat Hizmatlari markazidan ma`lumotnoma)</p>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <!-- oferta -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="offerConfirm" class="flat-red">
                                    <span class="text-muted text-sm">Ommaviy oferta</span>
                                </label>
                                <button type="submit" class="btn btn-bitbucket disabled mycls"><i class="fa fa-send"></i> Jo`natish</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div id="offerModal" class="modal fade in">
                <div class="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close closeModal" data-dismiss="modal" aria-label=""><span>×</span></button>
                                    <h4 class="modal-title">ПУБЛИЧНАЯ ОФЕРТА И ОБЩИЕ УСЛОВИЯ ОБ ОТКРЫТИИ ДЕПОЗИТНОГО СЧЕТА
                                        ДО ВОСТРЕБОВАНИЯ В НАЦИОНАЛЬНОЙ ИЛИ ИНОСТРАННОЙ ВАЛЮТЕ
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <div style="height: 450px; overflow-y: scroll;" class="">
                                        <div>
                                            <p>Настоящий документ (далее «Оферта») в соотвествии со ст.
                                                367 Гражданского кодекса Республики Узбекистан является предложением
                                                АКБ “Универсал банк” Клиенту заключить Договор об открытии депозитного
                                                счета до востребования в национальной или иностранной валюте.</p>
                                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                В случае акцепта Оферты Клиентом, он считается заключившим с
                                                Банком Договор об открытии депозитного счета до востребования в национальной или
                                                иностранной валюте на условиях, предусмотренных настоящей Офертой (далее –«Договор»)</p>
                                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                АКБ “Универсал банк” (далее по тексту “Банк”), расположенный по адресу: Республика Узбекистан, Ферганская область,
                                                город Коканд, улица Шохрухобод, почтовый индекс 150700, идентификационный номер налогоплательщика 203556638.</p>
                                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Имеет лицензию №68 выданный Центральным банком Республики Узбекистан от 14.07.2018 года, официальный сайт Банка
                                                <a href="http://www.universalbank.uz">www.universalbank.uz</a>.</p><p>&nbsp;</p><p>
                                                <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ТЕРМИНЫ И ОПРЕДЕЛЕНИЯ:</strong></p>
                                            <p><strong>Оферта </strong>- настоящий документ «Публичный договор-оферта по оказанию услуг Клиенту посредством веб-сайта Банка в сети Интернет
                                                <a href="http://www.universalbank.uz">www.universalbank.uz</a>&nbsp; и мобильного приложения «Universal Mobile»;</p>
                                            <p><strong>Акцепт оферты </strong>- полное и безоговорочное принятие оферты путем осуществления</p>
                                            <p>Клиентом действий, указанных в настоящей Оферте;</p>
                                            <p><strong>Клиент </strong>– юридическое лицо или индивидуальный предприниматель, занимающийся предпринимательской деятельностью
                                                без образования юридического лица, осуществляющий акцепт оферты, и являющимся, таким образом, заказчиком услуг Банка по заключенному
                                                договору оферты;</p><p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Договор оферты </strong>- договор
                                                между Банком и Пользователем по оказанию услуг Клиенту посредством мобильного приложения «Universal Mobile» и веб-сайта Банка в сети
                                                Интернет www.universalbank.uz;</p><p><strong>Мобильное приложение</strong> «Universal Mobile» - информационная система, состоящая из
                                                комплекса специализированных компьютерных программных продуктов, предназначенных для информационного и технологического взаимодействия
                                                между Клиентом и Банком.</p><p><strong>Веб-сайт </strong>- официальный веб-сайт Банка в сети Интернет www.universalbank.uz;</p>
                                            <p><strong>Идентификация Клиента </strong>- определение коммерческим банком данных о клиентах, на основе предоставленных ими документов в
                                                целях осуществления надлежащей проверки пользователя. Все документы, позволяющие идентифицировать пользователя должны быть
                                                действительными на дату их предъявления;</p><p><strong>Банковский счет -</strong> (либо Счет) средство осуществления отношений,
                                                возникающих между Банком и Клиентом в результате заключения Договора банковского счета, по которому Банк обязуется принимать и
                                                зачислять поступающие на Счет Клиента (владельца Счета) денежные средства, выполнять распоряжения Клиента о перечислении и выдаче
                                                соответствующих сумм со Счета и проведении других операций по Счету;</p><p><strong>Депозитный счет до востребования </strong>- основной
                                                или вторичный Счет (расчетный счет) Клиента, на котором хранятся денежные средства Клиента (владельца Счета), предназначенные для его
                                                текущих нужд и в установленном порядке выдаваемых или перечисляемых по требованию Клиента;</p>
                                            <p><strong>Тарифы Банка </strong>- согласованные Сторонами размеры и ставки комиссионного вознаграждения Банка за оказанные услуги,
                                                указанные на веб-сайте www.universalbank.uz, являющейся неотъемлемой частью настоящейоферты;</p>
                                            <p>&nbsp;«<strong>Банковский день» </strong>- означает день, в который Банки Республики Узбекистан открыты для&nbsp; операций.</p>
                                            <p>&nbsp;</p><p><strong>ОБЩИЕ УСЛОВИЯ ОФЕРТЫ</strong></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Все, кто акцептует данную оферту (далее по тексту «Клиент»):</p><p>Заинтересованы в открытии депозитного счета до востребования в
                                                национальной или иностранной валюте;</p><p>Ознакомились со всеми условиями и сведениями, раскрытыми&nbsp; в
                                                “Публичной оферте и общих условиях в открытии депозитного счета до востребования в национальной или иностранной валюте;</p>
                                            <p>Стороны понимают, что действия сторон, связанные с в открытием депозитного счета до востребования в национальной или иностранной валюте,
                                                посредством интернет соединения, имеют оборудование, которое позволяет совершать действия через интернет и считают постоянный доступ в
                                                интернет для себя доступным;</p><p>Понимают, что письменные документы от банка могут быть предоставлены Клиенту в электронном виде в
                                                Личном кабинете и считают такую форму получения документов для себя доступной;</p><p>Сообщают, что, ознакомившись с офертой поняли все
                                                условия, предложенные в оферте.</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; По всем вопросам, связанным с
                                                условиями оферты можно связаться по телефону (371) 200-11-10, по электронной почте <a href="mailto:info@universalbank.uz">
                                                    info@universalbank.uz</a>.</p><p><strong>&nbsp;</strong></p><p><strong>ПОРЯДОК ЗАКЛЮЧЕНИЯ ДОГОВОРА (ПОРЯДОК НАПРАВЛЕНИЯ АКЦЕПТА)</strong></p>
                                            <p>Акцепт по настоящей Оферте производится в виде электронного сообщения, текст которого содержит согласие участника электронной коммерции
                                                заключить Договор открытия и обслуживания&nbsp; банковского счета.</p><p>Настоящая публичная оферта является Договором открытия и
                                                обслуживания&nbsp; банковского счета Клиента.</p><p>Акцепт считается полученным с момента отправления Банком уведомления об их получении,
                                                автоматически определяемого информационной системой, если иное не предусмотрено настоящим договором.</p><p><strong>&nbsp;</strong></p>
                                            <p><strong>ПОРЯДОК ОТЗЫВА АКЦЕПТА</strong></p><p>После получения акцепта Банк направляет Клиенту сообщение о получении акцепта.</p>
                                            <p>Акцепт не может быть отозван после получения Клиентом сообщения о получении Акцепта.</p>
                                            <p>Сообщением о получении акцепта является отправленное Клиенту сообщение в виде текста «Акцепт получен».</p>
                                            <p><strong>&nbsp;</strong></p><ol><li><strong>ПРЕДМЕТ ДОГОВОРА</strong></li></ol><p>&nbsp; Настоящим Банк берет на себя обязанности
                                                открыть &nbsp;Клиенту депозитный счет до востребования в национальной или иностранной валюте (далее «Счет»), принимать и вносить
                                                денежные поступления на открытый Клиенту счет, переводить и производить выплаты соответствующих сумм со счета Клиента, а также выполнять
                                                другие поручения о выполнении кассовых операций по счету, а Клиент в свою очередь обязуется оплачивать стоимость банковского обслуживания
                                                банковского счета по тарифам, установленным Банком на веб-сайте www.universalbank.uz.</p><p>&nbsp;</p>
                                            <ol start="2"><li><strong>ОБЯЗАННОСТИ СТОРОН</strong></li></ol><p>&nbsp;</p><ol start="2"><li>
                                                    1. <strong>ПРАВА И ОБЯЗАННОСТИ КЛИЕНТА</strong><ul><li><strong>Клиент вправе:</strong></li></ul></li></ol>
                                            <ul><li>самостоятельно распоряжаться своими денежными средствами, находящимися на счете в Банке, если иное не предусмотрено действующим
                                                    законодательством Республики Узбекистан;</li><li>получать от Банка сведения, необходимые для составления денежно — расчетных
                                                    документов и ведения банковских операций.</li><li>получать информацию о порядке оформления банковских безналичных расчетов;</li>
                                                <li>расторгнуть настоящий договор, в порядке, установленном законодательством РУз.
                                                    <ul><li><strong>Клиент обязуется:</strong></li></ul></li>
                                                <li>соблюдать установленный в Банке режим работы, порядок оформления и
                                                    предоставления платежных документов, требования действующих законодательных актов, а также нормативных актов Центрального банка
                                                    Республики Узбекистан;</li>
                                                <li>осуществлять расчеты по банковскому счёту в соответствии с действующим законодательством РУз;</li>
                                                <li>при получении информации о состоянии своего счёта проверять соответствие проведенных операций с расчётными документами. В случае
                                                    обнаружения отклонений, искажений или недостоверностей проведенных операций, письменно извещать об этом Банк не позднее 2-х рабочих
                                                    дней с момента получения такой информации.</li>
                                                <li>своевременно оплачивать услуги Банка, согласно устанавливаемых Банком тарифов за
                                                    обслуживание счета, действующих на день оказания услуг.</li></ul><p>&nbsp;</p>
                                            <ul>
                                                <li><strong>ПРАВА И ОБЯЗАННОСТИ БАНКА</strong>
                                                    <ul><li><strong>Банк вправе:</strong></li></ul></li>
                                                <li>использовать имеющиеся на банковском счете денежные средства Клиента как
                                                    источник финансовых ресурсов, гарантируя их наличие при предъявлении требований к счёту и право его владельца беспрепятственно
                                                    распоряжаться этими средствами в пределах суммы, находящихся на счете;</li></ul>
                                            <p>в безакцептном порядке списывать со счёта
                                                Клиента комиссионные вознаграждения, причитающиеся Банку согласно Тарифам Банка;</p>
                                            <ul>
                                                <li>приостанавливать операции платежей по
                                                    банковскому счету Клиента или отказать в их совершении при наличии фактов нарушения Клиентом действующего законодательства РУз,
                                                    законодательства по противодействию легализации доходов, полученных от преступной деятельности и финансирования терроризма, а
                                                    также нарушения порядка оформления расчетных документов и сроков их представления Банк.</li>
                                                <li>совершать иные действия, направленные на исполнение обязательств, возложенных на Банк законодательством Республики Узбекистана, а
                                                    также нормативными актами Центрального банка Республики Узбекистан.
                                                    <ul>
                                                        <li><strong>Банк обязуется:</strong></li></ul></li>
                                                <li>в порядке и на условиях предусмотренных настоящим договором осуществлять
                                                    прием и зачисление поступающих на банковский счет Клиента денежных средств, выполнять распоряжения Клиента о перечислении и выдаче
                                                    соответствующих сумм со счета, проводить другие операции по международным денежным переводам.</li><li>В случае поступления
                                                    платежного документа после окончания операционного дня, банк обязан осуществить платеж не позднее следующего рабочего дня.</li>
                                                <li>сохранять тайну операций по счету Клиента. Без согласия Клиента справки об операциях по счёту третьим лицам предоставляются только
                                                    в случаях, предусмотренных действующим законодательством РУз.</li>
                                                <li>размещать тарифы Банка на официальном сайте
                                                    Банка universalbank.uz для самостоятельного ознакомления Клиентом с тарифами.</li></ul>
                                            <p>предоставлять Клиенту бланки заявлений,
                                                объявлений на взнос наличных.</p>
                                            <p>&nbsp;</p><ol start="3"><li><strong>ОТВЕТСТВЕННОСТЬ СТОРОН</strong></li></ol><p>3.1. Если, после
                                                представления Клиентом всех соответствующих документов, предусмотренных нормативными актами Центрального банка, Банк необоснованно не
                                                открывает своевременно счет, либо отказывается открывать его, в т.ч. в случае требования при открытии счета лишних документов,
                                                Банк выплачивает штраф в размере 0,02% минимального размера от Уставного фонда.</p><p>3.2. Банк не несет ответственности за начисление
                                                и взимание пени или штрафов в пользу других кредиторов из-за невыполнения или ненадлежащего выполнения Клиентом своих обязательств,
                                                вытекающих из договоров, заключенных Клиентом с другими лицами.</p><p>3.3. Клиент несет ответственность за законность осуществляемых
                                                операций.</p>
                                            <ol start="4"><li><strong>ФОРС-МАЖОР</strong>
                                                    <ul><li>Стороны освобождаются от неисполнения или ненадлежащего исполнения
                                                            обязательств по настоящему договору, если это явилось следствием обстоятельств непреодолимой силы, возникших после заключения
                                                            настоящего договора, в результате событий чрезвычайного характера, которые стороны не могли ни предвидеть, ни предотвратить
                                                            разумными мерами.</li>
                                                        <li>В случае возникновения обстоятельств непреодолимой силы сроки выполнения обязательств по настоящему
                                                            договору переносятся соразмерно времени, в течение которого действуют такие обстоятельства и их последствия.
                                                        </li></ul></li></ol>
                                            <p>&nbsp;</p><ol start="5"><li><strong>СРОК ДЕЙСТВИЯ ДОГОВОРА</strong><ul>
                                                        <li>Настоящий Договор является
                                                            бессрочным и действует до момента его расторжения одной из сторон.</li>
                                                        <li>Настоящий договор, может быть, расторгнут по заявлению Клиента в любое время.</li>
                                                        <li>По требованию Банка настоящий договор, может быть, расторгнут в случаях, предусмотренных законодательством Республики Узбекистан.</li>
                                                        <li>Расторжение договора банковского счета является основанием для закрытия счета клиента.</li></ul></li>
                                                <li><strong>ДРУГИЕ УСЛОВИЯ ДОГОВОРА</strong>
                                                    <ul><li>Все споры, возникшие из настоящего Договора, разрешаются сторонами путем двусторонних
                                                            переговоров.
                                                            Разногласия, по которым стороны не достигли договоренности, разрешаются в соответствии с действующим законодательством Республики Узбекистан.</li>
                                                        <li>Акцептом настоящей Оферты Клиент подтверждает
                                                            своё безоговорочное согласие со всеми условиями Договора и обязуется исполнять его.</li></ul></li></ol></div></div>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- Select2 -->
        <script src="{{ asset ("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>
        <!-- InputMask -->
        <script src="{{ asset ("/admin-lte/plugins/input-mask/jquery.inputmask.js") }}"></script>
        <script src="{{ asset ("/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js") }}"></script>
        <!-- date-range-picker -->
        <script src="{{ asset ("/admin-lte/bootstrap/js/moment2.11.2/moment.min.js") }}"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="{{ asset ("/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
        <!-- iCheck 1.0.1 -->
        <script src="{{ asset ("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ("/admin-lte/dist/js/app.min.js") }}"></script>

        <script type="text/javascript">

            $(document).ready(function () {

                $('button.mycls').attr("disabled", true);
                $('#offerConfirm').click(function(){
                    if($(this).is(':checked')){
                        $('#offerModal').show();
                        $('button.mycls').removeClass("disabled");
                        $('button.mycls').attr("disabled", false);
                    } else {
                        $('button.mycls').attr("disabled", true);
                        $("#offerModal").hide();
                    }
                });

                // close Modal
                $('.closeModal').click(function () {

                    $('#offerModal').hide();

                });


                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();

                    //Money Euro
                    $("[data-mask]").inputmask("99-999-999-99-99");

                    $("[data-mask-inn]").inputmask("999-999-999");

                    //iCheck for checkbox and radio inputs
                    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                        checkboxClass: 'icheckbox_minimal-blue',
                        radioClass: 'iradio_minimal-blue'
                    });
                    //Red color scheme for iCheck
                    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                        checkboxClass: 'icheckbox_minimal-red',
                        radioClass: 'iradio_minimal-red'
                    });
                    //Flat red color scheme for iCheck
                    /*$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });*/
                });
            });
        </script>
    </section>
    <!-- /.content -->
</div>
@endsection
