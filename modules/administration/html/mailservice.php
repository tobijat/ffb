	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher Tobias, Musser Gerald">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>mailservice.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/mailservice.js"></script>
</head>
<body onload="initMailservice();">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Main">
		<div id="administration">
			<div id="admintitle">Mailservice</div>
			<?if(is_array($this->errors)) {?>
			        <div id="formerror">
			            <b>There are errors:</b><br>
			            <?foreach($this->errors as $error) {
			                echo '* '.$error.'<br>';
			            }?>
			        </div>
			<?}?>
			<?if($this->administration_answer) {?>
			    <div id="formanswer">
			        <?echo $this->administration_answer;?>
			    </div>
			<?}?>
		</div>
		<div id="ms_main">
			<div id="ms_mainleft">
				<div id="ms_leftselect">
					<div id="ms_search_game"></div>
					<div id="ms_search_choosen_game"></div>
					<div id="ms_search_matchround"></div>
					<div id="ms_search_mstype">
						<select id="ms_search_select_userstatus" style="width:100px;">
							<option value="">all Users</option>
							<option selected value="active">active Users</option>
							<option value="inactive">inactive Users</option>
							<option value="na">N/A Users</option>
						</select>
						Userstatus
					</div>
					<div id="ms_search_mstype">
						<select id="ms_search_select_mstype" style="width:100px;">
							<option value="">all Users</option>
							<option value="info_reminder">with INFO & REMINDER</option>
							<option value="info">with INFO</option>
							<option value="reminder">with REMINDER</option>
						</select>
						Mailservice
					</div>
					<div id="ms_search_requesttype"></div>
					<div id="ms_search_mstype">
						<input type="button" value="Get Users" onclick="javascript:retrieveUsers();">
					</div>
				</div>
				<div id="ms_leftuserlist">
					<div id="ms_search_userlist"></div>
				</div>
			</div>
			<div id="ms_mainright">
				<div id="ms_mail_to">
					Addresslist
				</div>
				<div id="ms_mail_subject">
					<input id="ms_input_subject" name="ms_input_subject" type="text" size="95" value="Subject..">
					<select id="ms_select_mailtype" name="ms_select_mailtype">
						<option disabled value="">Mailtype..</option>
						<option value="info">INFO</option>
						<option value="reminder">REMINDER</option>
						<option value="force">FORCE</option>
					</select>
				</div>
				<div id="ms_mail_text">
					<textarea id="ms_input_text" name="ms_input_text" rows="20" cols="85">Text..</textarea>
				</div>
				<div id="ms_answers"></div>
				<div id="ms_mail_send">
					<input type="button" onclick="javascript:checkMailSend();" value="Send Mail">
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>

	<div id="Main">
	<div id="ms_mainlist">
		<div id="ms_list_line">
			<div id="ms_list_date"><b>Date</b></div>
			<div id="ms_list_subject"><b>Subject</b></div>
			<div id="ms_list_to"><b>Reciepients</b></div>
			<div id="ms_list_type"><b>Type</b></div>
			<div id="ms_list_action"><b>Actions</b></div>
			<div style="clear:both;"></div>
		</div>
		<?foreach($this->mails as $item) {?>
		<div id="ms_list_line">
			<div id="ms_list_date"><?echo $item['mail_date'];?></div>
			<div id="ms_list_subject"><?echo $item['mail_subject'];?></div>
			<?if($item['mail_num_reciepients'] == 1) {?>
				<div id="ms_list_to"><?echo $item['mail_to'];?></div>
			<?} else {?>
				<div id="ms_list_to"><?echo $item['mail_num_reciepients'];?> Empf&auml;nger</div>
			<?}?>
			<div id="ms_list_type"><?echo $item['mail_criteria'];?></div>
			<div id="ms_list_type"><a title="Load Email" href="javascript:loadMail(<?echo $item['mail_id'];?>);"><img border="0" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH;?>symbols/change.png"></a></div>
			<div style="clear:both;"></div>
		</div>
		<?}?>

	</div>
	</div>

	<div id="Footer">
	    <?include(ADM_VIEWER_PATH.'footer.php')?>
	</div>
</div>
