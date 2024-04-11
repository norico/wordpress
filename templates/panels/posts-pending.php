<li>
<?php
printf('<span>%s</span>', human_time_diff(get_the_modified_time('U'), current_time('timestamp')) );
printf('<a href="%s">%s</a>', get_edit_post_link(), get_the_title() );
?>
</li>
