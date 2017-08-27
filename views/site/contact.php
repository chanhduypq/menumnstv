<?php 
use yii\bootstrap\ActiveForm;
use app\models\MultiLang;		//Gói đa ngôn ngữ

$lang = MultiLang::viewLang("profile");
$lang_payment = MultiLang::viewLang("_payment");
?>
    <div id="center-home">
        <div class="v2-padding-content v2-pad-con-plaza-land">
            <div class="v2-featured-digital">
                <div class="v2-title-f-d">
                    Contact Us
                </div>
                <div class="v2-content-f-d">
                    We're focusing on the menu for Food Restaurant, it will help increase of up to 33% in additional sales through the<br/>
                    use of digital signage. So not only is digital signage a great way to improve your brand awareness, but it's a great way to boost sales,<br/>
                    too - every marketers dream! Improving interaction with your customers is always going to have a positive outcome.
                </div>
            </div> <!-- end v2-featured-digital -->
            <div class="v2-box-contact">
                <div class="v2-col-3-contact">
                    <div class="v2-center-box-contact">
                        <div class="v2-box-icon-contact">
                            <a href="mailto:hello@mns.tv" class="box-img-contact v2-hover-mail"><img src="images/v2-icon-mail.png" class="img-responsive"></a>
                        </div>
                        <p class="v2-title-contact">Email</p>
                        <p>hello@mns.tv<span>&nbsp;|&nbsp;</span>sale@mns.tv</p>
                    </div>
                </div>
                <div class="v2-col-3-contact">
                    <div class="v2-center-box-contact">
                        <div class="v2-box-icon-contact">
                            <a href="tel:+84935500368" class="box-img-contact v2-hover-phone"><img src="images/v2-icon-phone.png" class="img-responsive"></a>
                        </div>
                        <p class="v2-title-contact">Phone</p>
                        <p>093 550 0368 (En)<span>&nbsp;|&nbsp;</span>097 848 1789 (Vi)</p>
                    </div>
                </div>
                <div class="v2-col-3-contact">
                    <div class="v2-center-box-contact">
                        <div class="v2-box-icon-contact">
                            <a href="https://www.google.com/maps/place/MNS/@10.7998364,106.647622,17z/data=!3m1!4b1!4m5!3m4!1s0x31752949074f6bd5:0xe7f3c1accc63e113!8m2!3d10.7998364!4d106.6498107" target="_blank" class="box-img-contact v2-icon-address"><img src="images/v2-icon-address.png" class="img-responsive"></a>
                        </div>
                        <p class="v2-title-contact">Address</p>
                        <p>#21, A4 St, Ward 12, Tan Binh Dist, SaiGon</p>
                    </div>
                </div>
            </div> <!-- end v2-box-contact -->
            <div class="v2-map-contact">
                <div class="v2-left-map-contact">
                    <p>mns tv</p>
                    <address>
                        <div class="v2-add-contact icon-add-mns_">
                            <i></i>
                            <p><span>#21, A4 Street, Ward 12, Tan Binh Dist,</span><br/><span>Saigon, SGN 700000, Vietnam</span></p>
                        </div>
                        <div class="v2-add-contact icon-mail-mns_">
                            <i></i>
                            <p><a href="mailto:hello@mns.tv">hello@mns.tv</a></p>
                        </div>
                        <div class="v2-add-contact icon-phone-mns_">
                            <i></i>
                            <p>+84 (0) 935 500 368 (En)<span>&nbsp;|&nbsp;</span> 097 848 1789 (Vi)</p>
                        </div>   
                    </address>   
                </div>
                <div class="v2-right-map-contact">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1498035970662!2d106.64762201538376!3d10.799836392305698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752949074f6bd5%3A0xe7f3c1accc63e113!2sMNS!5e0!3m2!1svi!2s!4v1490174304196" width="600" height="320" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div> <!-- end v2-padding-content v2-pad-con-plaza-land -->
        <div class="v2-form-contact">
            <div class="v2-padding-content v2-pad-con-plaza-land">
                <div class="v2-content-form">
                    <p>Form Contact</p>
                    <div class="v2-table-form-contact">
                         <?php $form = ActiveForm::begin(['action' =>['/contact'],
                            'id' => 'contactForm',
                            'options' => ['enctype' => 'multipart/form-data'],
                                ]);
                                ?>
                            <div class="table-responsive css-table-contact">
                                <table class="table login-pay-table v2-contact-table">
                                    <tr>
                                        <td>Gender <span>(*)</span></td>
                                        <td class="_css-color-white">
                                            <span class="css-radio">
                                                    <input type="radio" value="Mr"  name="ContactForm[contact_gender]" id="contact_gender" <?=((!$contactForm["contact_gender"] || $contactForm["contact_gender"] == 'Mr') ? 'checked' : '')?> > Mr
                                                </span>
                                                <span>
                                                    <input type="radio" value="Ms" name="ContactForm[contact_gender]" id="contact_gender" <?=($contactForm["contact_gender"] == 'Ms' ? 'checked' : '')?> > Ms
                                                </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Full Name <span>(*)</span></td>
                                        <td>
                                            <!-- khi thông tin bị lỗi sẽ hiện thị class error -->
                                            <!-- <span class="error">Tên sai định dạng</span> -->
                                            <?php echo $form->field($contactForm, 'contact_full_name')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'contact_full_name', 'maxlength' => 100))->label(false);
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email <span>(*)</span></td>
                                        <td>
                                            <!-- <span class="error">Email không đúng chuẩn</span> -->
                                            <?php echo $form->field($contactForm, 'contact_email')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'contact_email', 'maxlength' => 100))->label(false);
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="css-center-middle">Your message <span>(*)</span></td>
                                        <td>
                                            <?php echo $form->field($contactForm, 'contact_body')->textarea(
                                                        array('class' => 'css-input-pay v2-css-height-area', 'id' => 'contact_body'))->label(false);
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="Yes" <?=($contactForm["contact_receive_email"] == '' ? 'checked' : '')?>>
                                                Accept to receive our email instructions
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <a  onclick="document.getElementById('contactForm').submit();" class="css-btn-change-pass">Send Message</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>    
                        <?php ActiveForm::end(); ?>
                    </div> <!-- end v2-table-form-contact -->
                </div> <!-- end v2-content-form -->
            </div> <!-- end v2-padding-content v2-pad-con-plaza-land -->
        </div> <!-- end v2-form-contact -->
    </div> <!-- end center-home -->
    <script>
    $(document).ready(function () {
        var msg = "<?=$msg?>";

        if(msg.length > 1) jAlert(msg, "Message");
    });
</script>