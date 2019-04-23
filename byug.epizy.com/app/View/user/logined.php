<?php
startblock('content');
?>
Рады приветствовать Вас <strong><?=$data['user']['login']; ?></strong>!
<?php
endblock();
include template('layout');
