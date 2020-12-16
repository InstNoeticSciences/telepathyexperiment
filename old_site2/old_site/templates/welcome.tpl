<h1 class="header">{$welcome} {$logged_user->username}!</h1>
<p>{$follow_4_steps}</p>
<ul class="ul_start">
<li>{$step1}</li>
<li>{$step2}</li>
<li>{$step3}</li>
<li>{$step4}</li>
</ul>
<p class="mid"><a href="start_add_photo" class="continue">{$link_start}</a></p>
<p class="mid"><a href="profile/{$logged_user->username}">{$skip_setup}</a></p>