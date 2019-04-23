<?php
startblock('content');
echo "<pre>",var_export($data['sql']),"</pre>";
endblock();
include template('layout');
