<?php 
use yii\bootstrap\ActiveForm;
use app\models\MultiLang;		//Gói đa ngôn ngữ
use yii\helpers\ArrayHelper;
use app\models\Country;

$lang = MultiLang::viewLang("profile");
$lang_payment = MultiLang::viewLang("_payment");
?>
    <div id="center-home">
        <div class="v2-padding-content">
            <div class="v2-cart-new">
                <br>
                <div class="v2-title-detail-l">
                    Your Infomations
                </div>            
            </div> <!-- end v2-cart-new -->
            <div class="v2-box-cart">
                <div class="v2-border-box-cart">
                    <div class="v2-padding-top-info">
                        <div class="v2-left-info  no-float-1024 full-width-1024">
                            <p class="v2-title-info">Infomations</p>
                            <?php $form = ActiveForm::begin(['action' =>['/profile'],
                            'id' => 'profileForm',
                            'options' => ['enctype' => 'multipart/form-data'],
                                ]);
                                ?>
                                <div class="table-responsive css-table-info">
                                    <table class="table login-pay-table">
                                        <tr>
                                            <td>Email <span>(*)</span></td>
                                            <td>
                                                <!-- <span class="error">Email không đúng chuẩn</span> -->
                                                <input type="email" class="css-input-pay" value="<?=Yii::$app->session['email']?>" disabled>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Picture <span>(*)</span></td>
                                            <td>
                                                <div class="avarta-edit"><img src="<?=$avatar_path?>" class="img-responsive"></div>
                                                <div class="choose_file">
                                                    <input type="file" title="Choose File" class="style_file" name="ProfileForm[avatar]" id="avatar">
                                                    <span class="fileinput-new">No file chosen</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gender <span>(*)</span></td>
                                            <td>
                                                <span class="css-radio">
                                                    <input type="radio" name="ProfileForm[gender_id]" id="gender_id" <?=($profileForm["gender_id"] == '1' ? 'checked' : '')?> value="1"> Mr
                                                </span>
                                                <span>
                                                    <input type="radio" name="ProfileForm[gender_id]" id="gender_id" <?=($profileForm["gender_id"] == '2' ? 'checked' : '')?> value="2"> Ms
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Full Name <span>(*)</span></td>
                                            <td>
                                                <!-- khi thông tin bị lỗi sẽ hiện thị class error -->
                                                <!-- <span class="error">Tên sai định dạng</span> -->
                                                <?php echo $form->field($profileForm, 'full_name')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'full_name', 'maxlength' => 100))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phone <span>(*)</span></td>
                                            <td>
                                                <?php echo $form->field($profileForm, 'phone')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'phone', 'maxlength' => 20))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address <span>(*)</span></td>
                                            <td>
                                                 <?php echo $form->field($profileForm, 'address')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'address', 'maxlength' => 50))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>City <span>(*)</span></td>
                                            <td>
                                                <?php echo $form->field($profileForm, 'city')->textInput(
                                                        array('class' => 'css-input-pay', 'id' => 'city', 'maxlength' => 50))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Country <span>(*)</span></td>
                                            <td>
                                                <?php echo $form->field($profileForm, 'country_id')
                                             ->dropDownList(
                                                    ArrayHelper::map(Country::find()->asArray()->all(), 'country_id', 'country_name'),array('class'=>'form-control css-input-pay')
                                                    )->label(false); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a class="css-btn-update" onclick="document.getElementById('profileForm').submit();">Update</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>    
                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="v2-right-info  no-float-1024 full-width-1024">
                            <p class="v2-title-info">Change Password</p>
                            <?php $form = ActiveForm::begin(['action' =>['/profile'],
                            'id' => 'profileFormPassword',
                            'options' => ['enctype' => 'multipart/form-data'],
                                ]);
                                ?>
                                <div class="table-responsive css-table-info">
                                    <table class="table login-pay-table v2-width-td1">
                                        <tr>
                                            <td>Email <span>(*)</span></td>
                                            <td>
                                                <!-- <span class="error">Email không đúng chuẩn</span> -->
                                                <input type="email" class="css-input-pay" value="<?=Yii::$app->session['email']?>" disabled>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Curent password <span>(*)</span></td>
                                            <td>
                                                <!-- <span class="error">Sai định dạng</span> -->
                                                <?php echo $form->field($profileFormPassword, 'curr_password')->passwordInput(
                                                        array('class' => 'css-input-pay', 'id' => 'curr_password', 'maxlength' => 20))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>New password <span>(*)</span></td>
                                            <td>
                                                <?php echo $form->field($profileFormPassword, 'new_password')->passwordInput(
                                                        array('class' => 'css-input-pay', 'id' => 'new_password', 'maxlength' => 20))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Confirm new password <span>(*)</span></td>
                                            <td>
                                                <?php echo $form->field($profileFormPassword, 're_password')->passwordInput(
                                                        array('class' => 'css-input-pay', 'id' => 're_password', 'maxlength' => 20))->label(false);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a  onclick="document.getElementById('profileFormPassword').submit();" class="css-btn-change-pass">Change Password</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>    
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div> <!-- end v2-box-cart -->
        </div> <!-- end v2-padding-content -->
    </div> <!-- end center-home -->
	
	<script type="text/javascript">
    $(document).ready(function () {
        $('input[type=file]').bootstrapFileInput();
        $('.file-inputs').bootstrapFileInput();
    });
</script>