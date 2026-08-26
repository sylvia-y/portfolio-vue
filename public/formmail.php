    <form name="contactForm" id="contactForm" action="php/contact.php" method="POST" autocomplete="off" class="clearfix">


	<div id="contactForm_term">
        <div class="agree_text" readonly>
            <fieldset class="contactForm_agree2">
				<label for="agree">개인정보 제공 및 활용 동의서<br>본인은 개인정보 보호법 제15조에 의거하여 본인의 개인정보(이메일)를 제공할 것을 동의합니다.</label>
				<input type="checkbox" name="agree" value="1" id="agree">
			</fieldset>
       </div>
    </div>

    <div id="contactForm_form"  class="form_01">
		<div class="tbl_frm01 tbl_wrap">
			<ul>
				<li>
					<label for="contact_name" class="sound-only">이름 *</label>
					<input type="text" class="frm_input full_input required" id="contact_name" name="contact_name" title="Name" placeholder="Name">
				</li>
				<li>
					<label for="contact_email" class="sound-only">이메일 *</label>
					<input type="email" class="frm_input full_input required" id="contact_email" name="contact_email" title="Email" placeholder="Email">
				</li>
				<!--li>
					<label for="contact_phone" class="sound-only">연락처</label>
					<input type="text" class="frm_input full_input required" id="contact_phone" name="contact_phone" title="Phone" placeholder="Phone">
				</li-->
				<li>
					<label for="contact_subject" class="sound-only">제목 *</label>
					<input type="text" class="frm_input full_input required" id="contact_subject" name="contact_subject" title="Subject" placeholder="Subject">
				</li>
			</ul>
			<div class="contact-text">
					<label for="contact_message" class="sound-only">내용 *</label>
					<textarea id="contact_message" name="contact_message" rows="3" title="Message" style="" placeholder="저의 작업에 대해 궁금하신 점이 있으시면 언제든지 문의해 주세요."></textarea>

                	<input id="contact_submit" type="submit" class="btn_submit" value="SEND MAIL" style="">

			</div>
            
		</div>
	</div>
	<!-- MESSAGE SENT -->
	<div id="alertOk" class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<span id="alertOkResponse">메일을 보냈습니다. 빠른 시일 내에 회신드리겠습니다!</span>
	</div>
	<!-- /MESSAGE SENT -->

	<!-- MESSAGE NOT SENT -->
	<div id="alertErr" class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<span id="alertErrResponse">메일보내기에 실패했습니다. 다시 한 번 확인해 주세요.</span>
	</div>
	<!-- MESSAGE NOT SENT -->



    </form>

<script type="text/javascript" src="js/contact.js"></script>
